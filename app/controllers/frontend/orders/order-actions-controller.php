<?php

namespace Voxel\Controllers\Frontend\Orders;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Order_Actions_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_orders.author.approve', '@author_approve_order' );
		$this->on( 'voxel_ajax_orders.author.decline', '@author_decline_order' );
		$this->on( 'voxel_ajax_orders.author.approve_refund', '@author_approve_refund' );
		$this->on( 'voxel_ajax_orders.author.decline_refund', '@author_decline_refund' );

		$this->on( 'voxel_ajax_orders.customer.portal', '@customer_portal' );
		$this->on( 'voxel_ajax_orders.customer.cancel', '@customer_cancel_order' );
		$this->on( 'voxel_ajax_orders.customer.request_refund', '@customer_request_refund' );
		$this->on( 'voxel_ajax_orders.customer.cancel_refund_request', '@customer_cancel_refund_request' );

		$this->on( 'voxel_ajax_orders.customer.subscriptions.reactivate', '@subscriptions_reactivate' );
		$this->on( 'voxel_ajax_orders.customer.subscriptions.retry_payment', '@subscriptions_retry_payment' );
		$this->on( 'voxel_ajax_orders.customer.subscriptions.cancel', '@subscriptions_cancel' );

		$this->on( 'voxel_ajax_orders.receipt', '@get_receipt' );
	}

	protected function author_approve_order() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'author_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_PENDING_APPROVAL,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$payment_intent = $order->get_object();
			$payment_intent->capture();

			$order->update( 'status', \Voxel\Order::STATUS_COMPLETED );
			$order->note( \Voxel\Order_Note::AUTHOR_APPROVED );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function author_decline_order() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'author_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_PENDING_APPROVAL,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$stripe = \Voxel\Stripe::getClient();
			$stripe->paymentIntents->cancel( $order->get_object_id() );

			$order->update( 'status', \Voxel\Order::STATUS_DECLINED );
			$order->note( \Voxel\Order_Note::AUTHOR_DECLINED );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function author_approve_refund() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'author_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_REFUND_REQUESTED,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$stripe = \Voxel\Stripe::getClient();
			$stripe->refunds->create( [
				'payment_intent' => $order->get_object_id(),
				'reason' => 'requested_by_customer',
				'refund_application_fee' => true, // @todo: test with false
				'reverse_transfer' => true, // @todo: test with false
			] );

			$order->update( 'status', \Voxel\Order::STATUS_REFUNDED );
			$order->note( \Voxel\Order_Note::AUTHOR_REFUND_APPROVED );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function author_decline_refund() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'author_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_REFUND_REQUESTED,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$order->update( 'status', \Voxel\Order::STATUS_COMPLETED );
			$order->note( \Voxel\Order_Note::AUTHOR_REFUND_DECLINED );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function customer_cancel_order() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'customer_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_PENDING_APPROVAL,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$stripe = \Voxel\Stripe::getClient();
			$stripe->paymentIntents->cancel( $order->get_object_id(), [
				'cancellation_reason' => 'requested_by_customer',
			] );

			$order->update( 'status', \Voxel\Order::STATUS_DECLINED );
			$order->note( \Voxel\Order_Note::CUSTOMER_CANCELED );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function customer_request_refund() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'customer_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_COMPLETED,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$order->update( 'status', \Voxel\Order::STATUS_REFUND_REQUESTED );
			$order->note( \Voxel\Order_Note::CUSTOMER_REFUND_REQUESTED );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function customer_cancel_refund_request() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'customer_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_REFUND_REQUESTED,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$order->update( 'status', \Voxel\Order::STATUS_COMPLETED );
			$order->note( \Voxel\Order_Note::CUSTOMER_REFUND_REQUEST_CANCELED );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function get_receipt() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'party_id' => get_current_user_id(),
				'status' => \Voxel\Order::STATUS_COMPLETED,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			// @todo

			return wp_send_json( [
				// 'pdf' => $pdf,
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function customer_portal() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$stripe = \Voxel\Stripe::getClient();
			$session = $stripe->billingPortal->sessions->create( [
				'customer' => \Voxel\current_user()->get_stripe_customer_id(),
				'configuration' => \Voxel\get( 'settings.stripe.configuration_id' ),
				'return_url' => $order->get_link(),
			] );

			return wp_send_json( [
				'redirect_to' => $session->url,
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function subscriptions_reactivate() {
		try {
			// \Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_reactivate_plan' );
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'customer_id' => get_current_user_id(),
				'mode' => 'subscription',
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$stripe = \Voxel\Stripe::getClient();
			$subscription_details = $order->get_object();

			if ( ! ( $subscription_details['cancel_at_period_end'] ?? null ) ) {
				throw new \Exception( _x( 'Request not valid.', 'orders', 'voxel' ) );
			}

			$subscription = \Stripe\Subscription::update( $order->get_object_id(), [
				'cancel_at_period_end' => false,
			] );

			do_action( 'voxel/orders/subscription-updated', $subscription, $order );

			return wp_send_json( [
				'success' => true,
				'message' => _x( 'Subscription has been reactivated.', 'orders', 'voxel' ),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function subscriptions_retry_payment() {
		try {
			// \Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_retry_payment' );
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'customer_id' => get_current_user_id(),
				'mode' => 'subscription',
				'status' => [ 'sub_incomplete', 'sub_past_due', 'sub_unpaid' ],
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$stripe = \Voxel\Stripe::getClient();
			$subscription = $order->get_object();

			if ( $order->get_status() === 'unpaid' ) {
				$stripe->invoices->finalizeInvoice( $subscription->latest_invoice, [
					'auto_advance' => true,
				] );
			}

			$stripe->invoices->pay( $subscription->latest_invoice );

			return wp_send_json( [
				'success' => true,
				'message' => _x( 'Invoice was paid successfully.', 'orders', 'voxel' ),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function subscriptions_cancel() {
		try {
			// \Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_cancel_plan' );
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'customer_id' => get_current_user_id(),
				'mode' => 'subscription',
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$stripe = \Voxel\Stripe::getClient();
			if ( in_array( $order->get_status(), [ 'sub_canceled', 'sub_incomplete_expired' ], true ) ) {
				throw new \Exception( _x( 'Request not valid.', 'orders', 'voxel' ) );
			}

			// @todo: add as setting in wp-admin
			$cancel_behavior = apply_filters( 'voxel/orders/subscription_cancel_behavior', 'at_period_end' );

			if ( $cancel_behavior === 'immediately' ) {
				$subscription = $stripe->subscriptions->cancel( $order->get_object_id() );
				do_action( 'voxel/orders/subscription-updated', $subscription, $order );
			} else {
				$subscription = \Stripe\Subscription::update( $order->get_object_id(), [
					'cancel_at_period_end' => true,
				] );
				do_action( 'voxel/orders/subscription-updated', $subscription, $order );
			}

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}
}
