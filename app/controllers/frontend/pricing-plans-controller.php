<?php

namespace Voxel\Controllers\Frontend;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Pricing_Plans_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_plans.choose_plan', '@choose_plan' );
		$this->on( 'voxel_ajax_plans.retry_payment', '@retry_payment' );
		$this->on( 'voxel_ajax_plans.cancel_plan', '@cancel_plan' );
		$this->on( 'voxel_ajax_plans.reactivate_plan', '@reactivate_plan' );
		$this->on( 'voxel/membership/subscription-updated', '@subscription_updated' );
		$this->on( 'voxel/membership/subscription-deleted', '@subscription_deleted' );
	}

	protected function choose_plan() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_choose_plan' );

			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$membership = $user->get_membership();
			$customer = $user->get_or_create_stripe_customer();
			$price_key = sanitize_text_field( $_GET['plan'] ?? '' );

			$price_id = substr( strrchr( $price_key, '@' ), 1 );
			$plan_key = str_replace( '@'.$price_id, '', $price_key );
			$mode = substr( $price_id, 0, 5 ) === 'test:' ? 'test' : 'live';
			$price_id = str_replace( 'test:', '', $price_id );

			$plan = \Voxel\Membership\Plan::get( $plan_key );
			if ( ! $plan ) {
				throw new \Exception( _x( 'Plan does not exist.', 'pricing plans', 'voxel' ) );
			}

			$pricing = $plan->get_pricing();
			if ( empty( $pricing[ $mode ] ) || empty( $pricing[ $mode ]['prices'][ $price_id ] ) ) {
				throw new \Exception( _x( 'Price does not exist.', 'pricing plans', 'voxel' ) );
			}

			$price = $pricing[ $mode ]['prices'][ $price_id ];
			if ( ! $price['active'] ) {
				throw new \Exception( _x( 'Price is not available.', 'pricing plans', 'voxel' ) );
			}

			$mode = $price['type'] === 'recurring' ? 'subscription' : 'payment';

			// handle subscription switch
			if ( $membership->get_type() === 'subscription' && $mode === 'subscription' && $membership->is_switchable() ) {
				if ( $membership->get_price_id() === $price_id ) {
					throw new \Exception( _x( 'You are already on this plan.', 'pricing plans', 'voxel' ) );
				}

				$subscription = \Stripe\Subscription::retrieve( $membership->get_subscription_id() );
				$updatedSubscription = \Stripe\Subscription::update( $subscription->id, [
					'items' => [ [
						'id' => $subscription->items->data[0]->id,
						'price' => $price_id,
						'quantity' => 1,
					] ],
					'metadata' => [
						'voxel:payment_for' => 'membership',
						'voxel:plan' => $plan->get_key(),
					],
					'payment_behavior' => apply_filters( 'voxel/update-subscription/payment-behavior', 'allow_incomplete' ),
					'proration_behavior' => \Voxel\get( 'settings.stripe.update.proration_behavior', 'always_invoice' ),
				] );

				do_action( 'voxel/membership/subscription-updated', $updatedSubscription );

				return wp_send_json( [
					'success' => true,
					'message' => _x( 'Subscription updated.', 'pricing plans', 'voxel' ),
					'redirect_to' => get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/'),
				] );
			} else {
				$welcome_redirect = wp_validate_redirect( $_REQUEST['redirect_to'] ?? '' );
				if ( ! empty( $_REQUEST['redirect_to'] ) && $welcome_redirect ) {
					if ( \Voxel\get( 'settings.membership.after_registration' ) === 'welcome_step' ) {
						$success_url = add_query_arg( [
							'welcome' => '',
							'redirect_to' => $welcome_redirect,
						], get_permalink( \Voxel\get( 'templates.auth' ) ) ?: home_url('/') );
					} else {
						$success_url = $welcome_redirect ?: home_url('/');
					}
				} else {
					$success_url = add_query_arg( [
						'success' => 1,
						'_wpnonce' => wp_create_nonce( 'vx_pricing_checkout' ),
						'session_id' => '{CHECKOUT_SESSION_ID}',
					], get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/') );
				}

				$args = [
					'mode' => $mode,
					'customer' => $customer->id,
					'line_items' => [ [
						'price' => $price_id,
						'quantity' => 1,
					] ],
					'success_url' => $success_url,
					'cancel_url' => add_query_arg( 'canceled', 1, get_permalink( \Voxel\get( 'templates.pricing' ) ) ?: home_url('/') ),
				];

				if ( $mode === 'subscription' ) {
					$trial_enabled = \Voxel\get( 'settings.membership.trial.enabled', false );
					$trial_days = absint( \Voxel\get( 'settings.membership.trial.period_days', 0 ) );
					$trial_allowed = $membership->get_type() === 'default'; // only allow free trial on first plan sign-up

					$args['subscription_data'] = [
						'payment_behavior' => apply_filters( 'voxel/create-subscription/payment-behavior', 'allow_incomplete' ),
						'trial_period_days' => ( $trial_allowed && $trial_enabled && $trial_days ) ? $trial_days : null,
						'metadata' => [
							'voxel:payment_for' => 'membership',
							'voxel:plan' => $plan->get_key(),
						],
					];
				} else {
					$args['payment_intent_data'] = [
						'metadata' => [
							'voxel:payment_for' => 'membership',
							'voxel:plan' => $plan->get_key(),
						],
					];
				}

				$session = \Stripe\Checkout\Session::create( $args );

				return wp_send_json( [
					'success' => true,
					'redirect_to' => $session->url,
				] );
			}
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function retry_payment() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_retry_payment' );

			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$membership = $user->get_membership();
			if ( $membership->get_type() !== 'subscription' || ! in_array( $membership->get_status(), [ 'incomplete', 'past_due', 'unpaid' ], true ) ) {
				throw new \Exception( _x( 'Request not valid.', 'retry subscription payment', 'voxel' ) );
			}

			$subscription = \Stripe\Subscription::retrieve( $membership->get_subscription_id() );

			if ( $membership->get_status() === 'unpaid' ) {
				$stripe->invoices->finalizeInvoice( $subscription->latest_invoice, [
					'auto_advance' => true,
				] );
			}

			$stripe->invoices->pay( $subscription->latest_invoice );

			return wp_send_json( [
				'success' => true,
				'message' => _x( 'Invoice was paid successfully.', 'retry subscription payment', 'voxel' ),
				'redirect_to' => get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/'),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function cancel_plan() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_cancel_plan' );

			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$membership = $user->get_membership();
			if ( $membership->get_type() !== 'subscription' || in_array( $membership->get_status(), [ 'canceled', 'incomplete_expired' ], true ) ) {
				throw new \Exception( _x( 'Request not valid.', 'retry subscription payment', 'voxel' ) );
			}

			if ( \Voxel\get( 'settings.membership.cancel.behavior', 'at_period_end' ) === 'immediately' ) {
				$subscription = $stripe->subscriptions->cancel( $membership->get_subscription_id() );
				do_action( 'voxel/membership/subscription-updated', $subscription );
			} else {
				$subscription = \Stripe\Subscription::update( $membership->get_subscription_id(), [
					'cancel_at_period_end' => true,
				] );
				do_action( 'voxel/membership/subscription-updated', $subscription );
			}

			return wp_send_json( [
				'success' => true,
				'redirect_to' => get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/'),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function reactivate_plan() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_reactivate_plan' );

			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$membership = $user->get_membership();
			if ( $membership->get_type() !== 'subscription' || ! $membership->will_cancel_at_period_end() ) {
				throw new \Exception( _x( 'Request not valid.', 'retry subscription payment', 'voxel' ) );
			}

			$subscription = \Stripe\Subscription::update( $membership->get_subscription_id(), [
				'cancel_at_period_end' => false,
			] );

			do_action( 'voxel/membership/subscription-updated', $subscription );

			return wp_send_json( [
				'success' => true,
				'message' => _x( 'Subscription has been reactivated.', 'pricing plans', 'voxel' ),
				'redirect_to' => get_permalink( \Voxel\get( 'templates.current_plan' ) ) ?: home_url('/'),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function subscription_updated( $subscription ) {
		$plan_key = $subscription->metadata['voxel:plan'];
		$plan = \Voxel\Membership\Plan::get( $plan_key );
		if ( ! $plan ) {
			throw new \Exception( sprintf( 'Plan "%s" not found for subscription "%s"', $plan_key, $subscription->id ) );
		}

		$user = \Voxel\User::get_by_customer_id( $subscription->customer );
		if ( ! $user ) {
			throw new \Exception( sprintf( 'Customer ID "%s" does not belong to any registered user (subscription "%s")', $subscription->customer, $subscription->id ) );
		}

		// $subscription->status: trialing, active, incomplete, incomplete_expired, past_due, canceled, unpaid
		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_plan' : 'voxel:plan';
		update_user_meta( $user->get_id(), $meta_key, wp_json_encode( [
			'plan' => $plan->get_key(),
			'type' => 'subscription',
			'subscription_id' => $subscription->id,
			'price_id' => $subscription->plan->id,
			'status' => $subscription->status,
			'trial_end' => $subscription->trial_end,
			'current_period_end' => $subscription->current_period_end,
			'cancel_at_period_end' => $subscription->cancel_at_period_end,
			'amount' => $subscription->plan->amount,
			'currency' => $subscription->plan->currency,
			'interval' => $subscription->plan->interval,
			'interval_count' => $subscription->plan->interval_count,
		] ) );

		$user->get_membership( $refresh_cache = true );

		// incomplete_expired and canceled are terminal states
		if ( $subscription->status === 'incomplete_expired' ) {
			// @todo trigger notification
		} elseif ( $subscription->status === 'canceled' ) {
			// @todo trigger notification
		}
	}

	protected function subscription_deleted( $subscription ) {
		$user = \Voxel\User::get_by_customer_id( $subscription->customer );
		if ( ! $user ) {
			throw new \Exception( sprintf( 'Customer ID "%s" does not belong to any registered user (subscription "%s")', $subscription->customer, $subscription->id ) );
		}

		// @todo trigger notification
	}
}
