<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Order {
	use \Voxel\Product_Types\Order_Singleton_Trait;

	const STATUS_PENDING_PAYMENT = 'pending_payment';
	const STATUS_PENDING_APPROVAL = 'pending_approval';
	const STATUS_COMPLETED = 'completed';
	const STATUS_CANCELED = 'canceled';
	const STATUS_DECLINED = 'declined';
	const STATUS_REFUND_REQUESTED = 'refund_requested';
	const STATUS_REFUNDED = 'refunded';

	private
		$id,
		$post_id,
		$product_type,
		$product_key,
		$customer_id,
		$details,
		$status,
		$mode,
		$object_id,
		$object_details,
		$created_at;

	public function __construct( array $data ) {
		$this->id = absint( $data['id'] );
		$this->post_id = absint( $data['post_id'] );
		$this->customer_id = absint( $data['customer_id'] );
		$this->product_type = $data['product_type'];
		$this->product_key = $data['product_key'];
		$this->status = $data['status'];
		$this->mode = $data['mode'];
		$this->object_id = $data['object_id'];
		$this->created_at = $data['created_at'];
		$this->details = is_string( $data['details'] ) ? json_decode( $data['details'], ARRAY_A ) : $data['details'];
		$this->object_details = is_string( $data['object_details'] ) ? json_decode( $data['object_details'], ARRAY_A ) : $data['object_details'];
		// dd($this);
	}

	public function get_id() {
		return $this->id;
	}

	public function get_status() {
		return $this->status;
	}

	public function get_mode() {
		return $this->mode;
	}

	public function get_object_id() {
		return $this->object_id;
	}

	public function get_object() {
		$stripe = \Voxel\Stripe::getClient();
		return ( $this->get_mode() === 'subscription' )
			? $stripe->subscriptions->retrieve( $this->get_object_id() )
			: $stripe->paymentIntents->retrieve( $this->get_object_id() );
	}

	public function get_object_details() {
		return $this->object_details;
	}

	public function get_link() {
		return add_query_arg(
			'order_id',
			$this->get_id(),
			get_permalink( \Voxel\get( 'templates.orders' ) )
		);
	}

	public function get_details() {
		return $this->details;
	}

	public function get_status_label() {
		$labels = $this->get_status_labels();
		return $labels[ $this->status ] ?? _x( 'Unknown', 'order status', 'voxel' );
	}

	public function get_customer_id() {
		return $this->customer_id;
	}

	public function get_customer() {
		return \Voxel\User::get( $this->get_customer_id() );
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function get_post() {
		return \Voxel\Post::get( $this->get_post_id() );
	}

	public function get_product_type() {
		return \Voxel\Product_Type::get( $this->product_type );
	}

	public function get_product_key() {
		return $this->product_key;
	}

	public function get_product_field() {
		$post = $this->get_post();
		$field = $post ? $post->get_field( $this->product_key ) : null;
		if ( ! ( $field && $field->get_type() === 'product' ) ) {
			return null;
		}

		return $field;
	}

	public function get_price_for_display() {
		$price = $this->details['pricing']['total'];
		$currency = $this->details['pricing']['currency'];
		return \Voxel\currency_format( $price, $currency, false );
	}

	public function get_price_period_for_display() {
		if ( $this->get_mode() !== 'subscription' ) {
			return null;
		}

		$interval = $this->details['pricing']['interval']['unit'] ?? null;
		$count = $this->details['pricing']['interval']['count'] ?? null;
		if ( $interval === null || $count === null ) {
			return null;
		}

		return \Voxel\interval_format( $interval, $count );
	}

	public function get_customer_name_for_display() {
		$customer = $this->get_customer();
		return $customer
			? $customer->get_display_name()
			: _x( '(deleted account)', 'deleted user account', 'voxel' );
	}

	public function get_time_for_display() {
		$from = strtotime( $this->created_at ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		$to = current_time( 'timestamp' );
		$diff = (int) abs( $to - $from );
		if ( $diff < WEEK_IN_SECONDS ) {
			return sprintf( _x( '%s ago', 'order created at', 'voxel' ), human_time_diff( $from, $to ) );
		}

		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $from );
	}

	public function get_post_title_for_display() {
		$post = $this->get_post();
		return $post
			? $post->get_title()
			: _x( '(deleted item)', 'deleted order post', 'voxel' );
	}

	public static function get_status_labels() {
		return [
			static::STATUS_PENDING_PAYMENT => _x( 'Pending Payment', 'order status', 'voxel' ),
			static::STATUS_PENDING_APPROVAL => _x( 'Pending Approval', 'order status', 'voxel' ),
			static::STATUS_CANCELED => _x( 'Canceled', 'order status', 'voxel' ),
			static::STATUS_COMPLETED => _x( 'Completed', 'order status', 'voxel' ),
			static::STATUS_DECLINED => _x( 'Declined', 'order status', 'voxel' ),
			static::STATUS_REFUND_REQUESTED => _x( 'Refund Requested', 'order status', 'voxel' ),
			static::STATUS_REFUNDED => _x( 'Refunded', 'order status', 'voxel' ),

			// trialing, active, incomplete, incomplete_expired, past_due, canceled, unpaid
			'sub_trialing' => _x( 'Trialing', 'subscription status', 'voxel' ),
			'sub_active' => _x( 'Active', 'subscription status', 'voxel' ),
			'sub_incomplete' => _x( 'Incomplete', 'subscription status', 'voxel' ),
			'sub_incomplete_expired' => _x( 'Expired', 'subscription status', 'voxel' ),
			'sub_past_due' => _x( 'Past due', 'subscription status', 'voxel' ),
			'sub_canceled' => _x( 'Canceled', 'subscription status', 'voxel' ),
			'sub_unpaid' => _x( 'Unpaid', 'subscription status', 'voxel' ),
		];
	}

	public function note( $type, $details = null ) {
		return \Voxel\Order_Note::create( [
			'order_id' => $this->get_id(),
			'type' => $type,
			'details' => $details,
		] );
	}

	public function update( $data_or_key, $value = null ) {
		global $wpdb;

		if ( is_array( $data_or_key ) ) {
			$data = $data_or_key;
		} else {
			$data = [];
			$data[ $data_or_key ] = $value;
		}

		$data['id'] = $this->get_id();
		$wpdb->query( \Voxel\Product_Types\Order_Repository::_generate_insert_query( $data ) );

		do_action( 'voxel/order.updated', $this, $data );
	}

	public static function get_intent_details( \Stripe\PaymentIntent $payment_intent ): array {
		return [
			'id' => $payment_intent->id,
			'amount' => $payment_intent->amount,
			'currency' => $payment_intent->currency,
			'application_fee_amount' => $payment_intent->application_fee_amount,
			'shipping' => $payment_intent->shipping,
			'status' => $payment_intent->status,
		];
	}

	public static function get_subscription_details( \Stripe\Subscription $subscription ): array {
		// $subscription->status: trialing, active, incomplete, incomplete_expired, past_due, canceled, unpaid
		return [
			'id' => $subscription->id,
			'status' => $subscription->status,
			'trial_end' => $subscription->trial_end,
			'current_period_end' => $subscription->current_period_end,
			'cancel_at_period_end' => $subscription->cancel_at_period_end,
			'amount' => $subscription->plan->amount,
			'currency' => $subscription->plan->currency,
			'interval' => $subscription->plan->interval,
			'interval_count' => $subscription->plan->interval_count,
			'application_fee_percent' => $subscription->application_fee_percent,
		];
	}

	public static function get_session_details( \Stripe\Checkout\Session $session ): array {
		return [
			'id' => $session->id,
			'currency' => $session->currency,
			'amount_subtotal' => $session->amount_subtotal,
			'amount_total' => $session->amount_total,
			'total_details' => $session->total_details,
		];
	}
}
