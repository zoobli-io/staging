<?php

namespace Voxel\Controllers\Frontend\Orders;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Orders_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_orders.get', '@get_orders' );
		$this->on( 'voxel_ajax_orders.post_comment', '@post_comment' );

		$this->on( 'voxel/orders/payment_intent.amount_capturable_updated', '@payment_intent_amount_capturable_updated', 10, 2 );
		$this->on( 'voxel/orders/payment_intent.canceled', '@payment_intent_canceled', 10, 2 );
		$this->on( 'voxel/orders/payment_intent.succeeded', '@payment_intent_succeeded', 10, 2 );
		$this->on( 'voxel/orders/charge.refunded', '@charge_refunded', 10, 2 );
		$this->on( 'voxel/orders/checkout.session.completed', '@checkout_session_completed', 10, 3 );

		$this->on( 'voxel/order.updated', '@order_updated', 10, 2 );
		$this->on( 'voxel/orders/subscription-updated', '@subscription_updated', 10, 2 );
	}

	protected function get_orders() {
		$page = absint( $_GET['page'] ?? 1 );
		$per_page = 10;
		$type = sanitize_text_field( $_GET['type'] ?? 'all' );
		$status = sanitize_text_field( $_GET['status'] ?? 'all' );
		$search = trim( sanitize_text_field( $_GET['search'] ?? '' ) );

		$args = [
			'limit' => $per_page + 1,
		];

		if ( $type === 'incoming' ) {
			$args['author_id'] = get_current_user_id();
		} elseif ( $type === 'outgoing' ) {
			$args['customer_id'] = get_current_user_id();
		} else {
			$args['party_id'] = get_current_user_id();
		}

		if ( $status && isset( \Voxel\Order::get_status_labels()[ $status ] ) ) {
			$args['status'] = $status;
		}

		if ( $page > 1 ) {
			$args['offset'] = ( $page - 1 ) * $per_page;
		}

		if ( ! empty( $search ) ) {
			$args['search'] = $search;
		}

		$orders = \Voxel\Order::query( $args );
		$has_more = count( $orders ) > $per_page;
		if ( $has_more ) {
			array_pop( $orders );
		}

		$data = [];
		foreach ( $orders as $order ) {
			$customer = $order->get_customer();
			$post = $order->get_post();
			$data[] = [
				'id' => $order->get_id(),
				'price' => $order->get_price_for_display(),
				'time' => $order->get_time_for_display(),
				'status' => [
					'slug' => $order->get_status(),
					'label' => $order->get_status_label(),
				],
				'customer' => [
					'name' => $order->get_customer_name_for_display(),
					'avatar' => $customer ? $customer->get_avatar_markup() : null,
					'link' => $customer ? $customer->get_link() : null,
				],
				'post' => [
					'title' => $order->get_post_title_for_display(),
					'link' => $post ? $post->get_link() : null,
				],
			];
		}

		return wp_send_json( [
			'success' => true,
			'data' => $data,
			'has_more' => $has_more,
		] );
	}

	protected function post_comment() {
		try {
			$order_id = absint( $_GET['order_id'] ?? null );
			if ( ! $order_id ) {
				throw new \Exception( _x( 'Missing order id.', 'orders', 'voxel' ) );
			}

			$order = \Voxel\Order::find( [
				'id' => $order_id,
				'party_id' => get_current_user_id(),
			] );

			if ( ! $order ) {
				throw new \Exception( _x( 'Could not find order.', 'orders', 'voxel' ) );
			}

			$values = json_decode( stripslashes( $_POST['fields'] ), true );

			$message_field = new \Voxel\Product_Types\Order_Comments\Comment_Message_Field;
			$sanitized_message = $message_field->sanitize( $values['message'] ?? '' );
			$message_field->validate( $sanitized_message );

			$file_field = new \Voxel\Product_Types\Order_Comments\Comment_Files_Field;
			$sanitized_files = $file_field->sanitize( $values['files'] ?? [] );
			$file_field->validate( $sanitized_files );
			$file_ids = $file_field->prepare_for_storage( $sanitized_files );

			$details = [];
			$details['user_id'] = get_current_user_id();

			if ( ! empty( $sanitized_message ) ) {
				$details['message'] = $sanitized_message;
			}

			if ( ! empty( $file_ids ) ) {
				$details['files'] = $file_ids;
			}

			if ( empty( $sanitized_message ) && empty( $file_ids ) ) {
				throw new \Exception( _x( 'Comment cannot be empty.', 'orders', 'voxel' ) );
			}

			$comment = $order->note( \Voxel\Order_Note::COMMENT, wp_json_encode( $details ) );

			return wp_send_json( [
				'success' => true,
				'comment' => $comment->prepare(),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function payment_intent_amount_capturable_updated( $payment_intent, $order ) {
		$order->update( [
			'object_id' => $payment_intent->id,
			'object_details' => $payment_intent,
			'status' => \Voxel\Order::STATUS_PENDING_APPROVAL,
		] );

		$order->note( \Voxel\Order_Note::PAYMENT_AUTHORIZED );
	}

	protected function payment_intent_canceled( $payment_intent, $order ) {
		$order->update( [
			'object_id' => $payment_intent->id,
			'object_details' => $payment_intent,
			'status' => \Voxel\Order::STATUS_CANCELED,
		] );
	}

	protected function payment_intent_succeeded( $payment_intent, $order ) {
		$order->update( [
			'object_id' => $payment_intent->id,
			'object_details' => $payment_intent,
			'status' => \Voxel\Order::STATUS_COMPLETED,
		] );
	}

	protected function charge_refunded( $charge, $order ) {
		$order->update( [
			'status' => \Voxel\Order::STATUS_REFUNDED,
		] );
	}

	protected function checkout_session_completed( $session, $payment_intent, $order ) {
		$details = $order->get_details();
		$details['checkout'] = \Voxel\Order::get_session_details( $session );

		$order->update( [
			'object_id' => $payment_intent->id,
			'object_details' => $payment_intent,
			'details' => $details,
		] );
	}

	protected function order_updated( $order, $new_data ) {
		if ( ! isset( $new_data['status'] ) || $order->get_status() === $new_data['status'] ) {
			return;
		}

		if (
			$new_data['status'] === \Voxel\Order::STATUS_COMPLETED
			|| $order->get_status() === \Voxel\Order::STATUS_COMPLETED
		) {
			$field = $order->get_product_field();
			if ( $field ) {
				$field->cache_fully_booked_days();
			}

			// @todo: reindex availability data for $order->get_post()
		}
	}

	protected function subscription_updated( $subscription, $order ) {
		// $subscription->status: trialing, active, incomplete, incomplete_expired, past_due, canceled, unpaid
		$order->update( [
			'object_id' => $subscription->id,
			'object_details' => $subscription,
			'status' => sprintf( 'sub_%s', $subscription->status ),
		] );

		// incomplete_expired and canceled are terminal states
		if ( $subscription->status === 'incomplete_expired' ) {
			// @todo trigger notification
		} elseif ( $subscription->status === 'canceled' ) {
			// @todo trigger notification
		}
	}
}
