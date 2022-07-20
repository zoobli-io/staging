<?php

namespace Voxel\Controllers\Async;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Membership_Actions extends \Voxel\Controllers\Base_Controller {

	protected function authorize() {
		return current_user_can( 'manage_options' );
	}

	protected function hooks() {
		$this->on( 'voxel_ajax_membership.update_plan', '@update_plan' );
		$this->on( 'voxel_ajax_membership.archive_plan', '@archive_plan' );
		$this->on( 'voxel_ajax_membership.create_price', '@create_price' );
		$this->on( 'voxel_ajax_membership.sync_prices', '@sync_prices' );
		$this->on( 'voxel_ajax_membership.toggle_price', '@toggle_price' );
	}

	protected function update_plan() {
		try {
			$data = $_POST['plan'] ?? [];
			$key = sanitize_text_field( trim( $data['key'] ?? '' ) );
			$plan = \Voxel\Membership\Plan::get( $key );
			if ( ! $plan ) {
				throw new \Exception( _x( 'Plan not found.', 'membership plans', 'voxel' ) );
			}

			$submissions = [];
			foreach ( (array) ( $data['submissions'] ?? [] ) as $post_type_key => $post_type_limit ) {
				if ( post_type_exists( $post_type_key ) ) {
					$submissions[ $post_type_key ] = absint( $post_type_limit );
				}
			}

			$plan->update( [
				'label' => sanitize_text_field( trim( $data['label'] ) ),
				'description' => sanitize_textarea_field( $data['description'] ),
				'submissions' => $submissions,
			] );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'errors' => [ $e->getMessage() ],
			] );
		}
	}

	protected function archive_plan() {
		try {
			$data = $_POST['plan'] ?? [];
			$key = sanitize_text_field( trim( $data['key'] ?? '' ) );
			$plan = \Voxel\Membership\Plan::get( $key );
			if ( ! $plan ) {
				throw new \Exception( _x( 'Plan not found.', 'membership plans', 'voxel' ) );
			}

			$plan->update( 'archived', ! $plan->is_archived() );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'errors' => [ $e->getMessage() ],
			] );
		}
	}


	protected function create_price() {
		try {
			$plan = \Voxel\Membership\Plan::get( $_POST['plan'] );
			if ( ! $plan ) {
				throw new \Exception( _x( 'Plan not found.', 'membership plans', 'voxel' ) );
			}

			$pricing = $plan->get_pricing();
			$mode = ( $_POST['mode'] ?? 'test' ) === 'test' ? 'test' : 'live';
			$client = ( $mode === 'live' )
				? \Voxel\Stripe::getLiveClient()
				: \Voxel\Stripe::getTestClient();

			// create stripe product if it doesn't exist
			if ( empty( $pricing[ $mode ] ) ) {
				$args = [
					'name' => $plan->get_label(),
					'metadata' => [
						'product_type' => 'membership\plan',
					],
				];

				if ( ! empty( $plan->get_description() ) ) {
					$args['description'] = $plan->get_description();
				}

				$product = $client->products->create( $args );

				$pricing[ $mode ] = [
					'product_id' => $product->id,
					'prices' => [],
				];

				$plan->update( 'pricing', $pricing );
			}

			$product_id = $pricing[ $mode ]['product_id'];
			$data = $_POST['price'] ?? [];
			$amount = isset( $data['amount'] ) ? absint( $data['amount'] ) : null;
			$currency = isset( $data['currency'] ) ? sanitize_text_field( $data['currency'] ) : null;
			$type = isset( $data['type'] ) ? sanitize_text_field( $data['type'] ) : null;
			$interval = isset( $data['interval'] ) ? sanitize_text_field( $data['interval'] ) : null;
			$intervalCount = isset( $data['intervalCount'] ) ? absint( $data['intervalCount'] ) : null;

			if ( $currency === null || $amount === null ) {
				throw new \Exception( _x( 'Please provide an amount and a currency.', 'membership plans', 'voxel' ) );
			}

			if ( ! \Voxel\Stripe\Currencies::is_zero_decimal( $currency ) ) {
				$amount *= 100;
			}

			$args = [
				'currency' => $currency,
				'product' => $product_id,
				'active' => true,
				'unit_amount' => $amount,
				'metadata' => [
					'pricing_type' => 'membership_pricing',
				],
			];

			if ( $type === 'recurring' ) {
				$args['recurring'] = [
					'interval' => $interval,
					'interval_count' => $intervalCount,
				];
			}

			$price = $client->prices->create( $args );

			$pricing[ $mode ]['prices'][ $price->id ] = [
				'currency' => $price->currency,
				'type' => $price->type,
				'amount' => $price->unit_amount,
				'active' => $price->active,
			];

			if ( $price->type === 'recurring' ) {
				$pricing[ $mode ]['prices'][ $price->id ]['recurring'] = [
					'interval' => $price->recurring->interval,
					'interval_count' => $price->recurring->interval_count,
				];
			}

			$plan->update( 'pricing', $pricing );

			return wp_send_json( [
				'success' => true,
				'pricing' => $plan->get_editor_config()['pricing'],
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'errors' => [ $e->getMessage() ],
			] );
		}
	}

	protected function sync_prices() {
		try {
			$plan = \Voxel\Membership\Plan::get( $_GET['plan'] );
			if ( ! $plan ) {
				throw new \Exception( _x( 'Plan not found.', 'membership plans', 'voxel' ) );
			}

			$pricing = $plan->get_pricing();
			$mode = ( $_GET['mode'] ?? 'test' ) === 'test' ? 'test' : 'live';
			$client = ( $mode === 'live' )
				? \Voxel\Stripe::getLiveClient()
				: \Voxel\Stripe::getTestClient();

			$product_id = $pricing[ $mode ]['product_id'];
			$prices = $client->prices->all( [
				'product' => $product_id,
				'limit' => 100,
			] );

			$pricing[ $mode ]['prices'] = [];
			foreach ( $prices->data as $price) {
				$pricing[ $mode ]['prices'][ $price->id ] = [
					'currency' => $price->currency,
					'type' => $price->type,
					'amount' => $price->unit_amount,
					'active' => $price->active,
				];

				if ( $price->type === 'recurring' ) {
					$pricing[ $mode ]['prices'][ $price->id ]['recurring'] = [
						'interval' => $price->recurring->interval,
						'interval_count' => $price->recurring->interval_count,
					];
				}
			}

			$plan->update( 'pricing', $pricing );

			return wp_send_json( [
				'success' => true,
				'pricing' => $plan->get_editor_config()['pricing'],
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'errors' => [ $e->getMessage() ],
			] );
		}
	}

	protected function toggle_price() {
		try {
			$plan = \Voxel\Membership\Plan::get( $_GET['plan'] );
			if ( ! $plan ) {
				throw new \Exception( _x( 'Plan not found.', 'membership plans', 'voxel' ) );
			}

			$pricing = $plan->get_pricing();
			$mode = ( $_GET['mode'] ?? 'test' ) === 'test' ? 'test' : 'live';
			$priceId = sanitize_text_field( $_GET['price'] ?? null );

			if ( empty( $pricing[ $mode ]['prices'][ $priceId ] ) ) {
				throw new \Exception( _x( 'Price not found.', 'membership plans', 'voxel' ) );
			}

			$client = ( $mode === 'live' )
				? \Voxel\Stripe::getLiveClient()
				: \Voxel\Stripe::getTestClient();

			$isActive = (bool) $pricing[ $mode ]['prices'][ $priceId ]['active'];
			$prices = $client->prices->update( $priceId, [
				'active' => ! $isActive,
			] );

			$pricing[ $mode ]['prices'][ $priceId ]['active'] = ! $isActive;
			$plan->update( 'pricing', $pricing );

			return wp_send_json( [
				'success' => true,
				'pricing' => $plan->get_editor_config()['pricing'],
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'errors' => [ $e->getMessage() ],
			] );
		}
	}
}
