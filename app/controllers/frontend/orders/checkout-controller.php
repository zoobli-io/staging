<?php

namespace Voxel\Controllers\Frontend\Orders;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Checkout_Controller extends \Voxel\Controllers\Base_Controller {

	private $post, $field, $product_type, $product_config;

	protected function hooks() {
		$this->on( 'voxel_ajax_checkout', '@handle' );
		$this->on( 'voxel_ajax_stripe.checkout.successful', '@checkout_successful' );
		$this->on( 'voxel_ajax_stripe.checkout.canceled', '@checkout_canceled' );
	}

	protected function handle() {
		$post_id = $_GET['post_id'] ?? null;
		$field_key = $_GET['field_key'] ?? null;
		$errors = [];
		if ( ! ( $post_id && $field_key ) ) {
			die;
		}

		try {
			$stripe = \Voxel\Stripe::getClient();
			$user = \Voxel\current_user();
			$post = \Voxel\Post::get( $_GET['post_id'] );
			if ( ! ( $post && $post->get_status() === 'publish' ) ) {
				throw new \Exception( _x( 'This item has not been published.', 'checkout', 'voxel' ) );
			}

			$author = $post->get_author();
			if ( ! $author ) {
				throw new \Exception( _x( 'This item cannot be purchased.', 'checkout', 'voxel' ) );
			}

			$field = $post->get_field( $field_key );
			if ( ! ( $field && $field->get_type() === 'product' && ( $product_type = $field->get_product_type() ) ) ) {
				throw new \Exception( _x( 'Product field has not been configured.', 'checkout', 'voxel' ) );
			}

			$product_config = $field->get_value();
			if ( empty( $product_config ) || ! $product_config['enabled'] ) {
				throw new \Exception( _x( 'Product is not available.', 'checkout', 'voxel' ) );
			}

			$this->post = $post;
			$this->field = $field;
			$this->product_type = $product_type;
			$this->product_config = $product_config;
			$is_using_price_id = $this->product_type->config('settings.payments.pricing') === 'price_id';

			$transfer_destination = $this->product_type->config( 'settings.payments.transfer_destination', 'vendor_account' );
			$account = $author->get_stripe_account_details();
			if ( $transfer_destination === 'vendor_account' && ! $account->exists ) {
				throw new \Exception( _x( 'This seller is not available at the moment.', 'checkout', 'voxel' ) );
			}

			/**
			 * Generate order details
			 */
			$order_details = [];

			$booking_details = $this->_prepare_booking_details();
			if ( ! empty( $booking_details ) ) {
				$order_details['booking'] = $booking_details;
			}

			$addition_details = $this->_prepare_addition_details();
			if ( ! empty( $addition_details ) ) {
				$order_details['additions'] = $addition_details;
			}

			$field_details = $this->_prepare_field_details();
			if ( ! empty( $field_details ) ) {
				$order_details['fields'] = $field_details;
			}

			$order_details['pricing'] = $this->_prepare_pricing_details();

			// stripe checkout
			$customer = $user->get_or_create_stripe_customer();
			$mode = $this->product_type->get_mode();

			$success_url = add_query_arg( [
				'vx' => 1,
				'action' => 'stripe.checkout.successful',
				'session_id' => '{CHECKOUT_SESSION_ID}',
			], home_url('/') );

			$cancel_url = add_query_arg( [
				'vx' => 1,
				'action' => 'stripe.checkout.canceled',
				'session_id' => '{CHECKOUT_SESSION_ID}',
			], home_url('/') );

			$customer_update = [
				'address' => 'auto',
				'name' => 'auto',
				'shipping' => 'auto',
			];

			$allow_promotion_codes = !! $this->product_type->config( 'checkout.promotion_codes.enabled' );

			if ( $mode === 'subscription' ) {
				$args = [
					'customer' => $customer->id,
					'mode' => 'subscription',
					'success_url' => $success_url,
					'cancel_url' => $cancel_url,
					'customer_update' => $customer_update,
					'allow_promotion_codes' => $allow_promotion_codes,
					'subscription_data' => [
						'metadata' => [
							'voxel:payment_for' => 'vendor_product',
						],
					],
				];

				if ( $is_using_price_id ) {
					$args['line_items'] = [ [
						'price' => $this->product_config['price_id'] ?? null,
						'quantity' => 1,
					] ];
				} else {
					$unit_amount = $order_details['pricing']['total'];
					$currency = \Voxel\get( 'settings.stripe.currency', 'USD' );
					if ( ! \Voxel\Stripe\Currencies::is_zero_decimal( $currency ) ) {
						$unit_amount *= 100;
					}

					$args['line_items'] = [ [
						'price_data' => [
							'currency' => $currency,
							'unit_amount' => $unit_amount,
							'recurring' => [
								'interval' => $this->product_config['interval']['unit'] ?? null,
								'interval_count' => $this->product_config['interval']['count'] ?? null,
							],
							'product_data' => [
								'name' => $post->get_title(),
								// 'description' => 'Some product description...', // @todo
								// 'images' => [], // @todo
							],
							'tax_behavior' => 'exclusive',
						],
						'quantity' => 1,
					] ];
				}

				if ( $transfer_destination === 'vendor_account' ) {
					$args['subscription_data']['application_fee_percent'] = $this->product_type->calculate_fee( $unit_amount );
					$args['subscription_data']['transfer_data'] = [ 'destination' => $account->id ];
				}
			} else {
				$args = [
					'customer' => $customer->id,
					'mode' => 'payment',
					'success_url' => $success_url,
					'cancel_url' => $cancel_url,
					'customer_update' => $customer_update,
					'allow_promotion_codes' => $allow_promotion_codes,
					'payment_intent_data' => [
						'capture_method' => $this->product_type->config( 'settings.payments.capture_method', 'manual' ),
						'metadata' => [
							'voxel:payment_for' => 'vendor_product',
						],
					],
					'expand' => [ 'payment_intent' ],
				];

				if ( $is_using_price_id ) {
					$args['line_items'] = [ [
						'price' => $this->product_config['price_id'] ?? null,
						'quantity' => 1,
					] ];
				} else {
					$unit_amount = $order_details['pricing']['total'];
					$currency = \Voxel\get( 'settings.stripe.currency', 'USD' );
					if ( ! \Voxel\Stripe\Currencies::is_zero_decimal( $currency ) ) {
						$unit_amount *= 100;
					}

					$args['line_items'] = [ [
						'price_data' => [
							'currency' => $currency,
							'unit_amount' => $unit_amount,
							'product_data' => [
								'name' => $post->get_title(),
								'description' => 'Some product description...', // @todo
								'images' => [], // @todo
							],
							'tax_behavior' => 'exclusive',
						],
						'quantity' => 1,
					] ];
				}

				if ( $transfer_destination === 'vendor_account' ) {
					$args['payment_intent_data']['application_fee_amount'] = $this->product_type->calculate_fee( $unit_amount );
					$args['payment_intent_data']['transfer_data'] = [ 'destination' => $account->id ];
				}
			}

			// tax configuration
			$tax_mode = $this->product_type->config( 'checkout.tax.mode' );
			if ( $tax_mode === 'auto' ) {
				$tax_code = $product_type->config( 'checkout.tax.auto.tax_code' );
				$tax_behavior = $product_type->config( 'checkout.tax.auto.tax_behavior' );
				$tax_id_collection = !! $product_type->config( 'checkout.tax.auto.tax_id_collection' );

				$args['automatic_tax'] = [ 'enabled' => true ];
				$args['tax_id_collection'] = [ 'enabled' => $tax_id_collection ];
				$args['line_items'][0]['price_data']['tax_behavior'] = $tax_behavior;
				$args['line_items'][0]['price_data']['product_data']['tax_code'] = $tax_code;
			} elseif ( $tax_mode === 'manual' ) {
				$tax_rates = \Voxel\Stripe::is_test_mode()
					? (array) $product_type->config( 'checkout.tax.manual.test_tax_rates' )
					: (array) $product_type->config( 'checkout.tax.manual.tax_rates' );

				if ( ! empty( $tax_rates ) ) {
					$args['line_items'][0]['tax_rates'] = $tax_rates;
				}
			}

			// shipping configuration
			if ( !! $this->product_type->config( 'checkout.shipping.enabled' ) ) {
				$allowed_countries = $this->product_type->config( 'checkout.shipping.allowed_countries' );
				$shipping_rates = \Voxel\Stripe::is_test_mode()
					? (array) $product_type->config( 'checkout.shipping.test_shipping_rates' )
					: (array) $product_type->config( 'checkout.shipping.shipping_rates' );

				if ( ! empty( $allowed_countries ) ) {
					$args['shipping_address_collection'] = [ 'allowed_countries' => $allowed_countries ];
				}

				if ( ! empty( $shipping_rates ) ) {
					$args['shipping_options'] = [];
					foreach ( $shipping_rates as $shipping_rate ) {
						$args['shipping_options'][] = [ 'shipping_rate' => $shipping_rate ];
					}
				}
			}

			$session = \Stripe\Checkout\Session::create( $args );

			$order_details['checkout'] = \Voxel\Order::get_session_details( $session );

			// in subscription mode, subscription is not yet created at this point, so no id/data are available
			$object = $mode === 'subscription' ? null : $session->payment_intent;

			// create order
			$order = \Voxel\Order::create( apply_filters( 'voxel/checkout/order-args', [
				'post_id' => $this->post->get_id(),
				'product_type' => $product_type->get_key(),
				'product_key' => $this->field->get_key(),
				'customer_id' => $user->get_id(),
				'details' => $order_details,
				'status' => \Voxel\Order::STATUS_PENDING_PAYMENT,
				'session_id' => $session->id,
				'mode' => $mode,
				'object_id' => $object->id ?? null,
				'object_details' => $object ?? null,
			] ) );

			return wp_send_json( [
				'success' => true,
				'message' => _x( 'Redirecting to checkout...', 'checkout', 'voxel' ),
				'redirect_url' => $session->url,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	/**
	 * Booking information
	 *
	 * @todo check if selected date/date ranges/timeslot are available for booking
	 */
	private function _prepare_booking_details() {
		if ( $this->product_type->config( 'calendar.type' ) === 'none' ) {
			return [];
		}

		$raw_booking_details = json_decode( stripslashes( $_POST['booking'] ?? '' ), true );
		$booking_details = [];
		$check_in = strtotime( $raw_booking_details['checkIn'] ?? null );
		$check_out = strtotime( $raw_booking_details['checkOut'] ?? null );
		$timeslot = $raw_booking_details['timeslot'] ?? [];
		$slot_from = strtotime( $timeslot['from'] ?? null );
		$slot_to = strtotime( $timeslot['to'] ?? null );

		if ( $this->product_type->config('calendar.type') === 'booking' ) {
			if ( $this->product_type->config( 'calendar.format' ) === 'days' && $this->product_type->config( 'calendar.allow_range' ) ) {
				if ( ! ( $check_in && $check_out ) ) {
					throw new \Exception( _x( 'Please choose check-in and check-out dates.', 'checkout', 'voxel' ) );
				}

				$booking_details = [
					'checkin' => date( 'Y-m-d', $check_in ),
					'checkout' => date( 'Y-m-d', $check_out ),
				];
			} elseif ( $this->product_type->config( 'calendar.format' ) === 'slots' ) {
				if ( ! ( $check_in && $slot_from && $slot_to ) ) {
					throw new \Exception( _x( 'Please choose check-in date and timeslot.', 'checkout', 'voxel' ) );
				}

				$booking_details = [
					'checkin' => date( 'Y-m-d', $check_in ),
					'timeslot' => [
						'from' => date( 'H:i', $slot_from ),
						'to' => date( 'H:i', $slot_to ),
					],
				];
			} else {
				if ( ! $check_in ) {
					throw new \Exception( _x( 'Please choose check-in date.', 'checkout', 'voxel' ) );
				}

				$booking_details = [
					'checkin' => date( 'Y-m-d', $check_in ),
				];
			}
		} elseif ( $this->product_type->config('calendar.type') === 'recurring-date' ) {
			$booking_details = [
				'checkin' => date( 'Y-m-d', $check_in ),
				'checkout' => date( 'Y-m-d', $check_out ),
				'timeslot' => [
					'from' => date( 'H:i', $check_in ),
					'to' => date( 'H:i', $check_out ),
				],
			];
		}

		return $booking_details;
	}

	/**
	 * Additions
	 */
	private function _prepare_addition_details() {
		$details = [];
		$posted_values = json_decode( stripslashes( $_POST['additions'] ?? '' ), true );
		$day_count = $this->_get_repeat_day_count();

		foreach ( $this->product_type->get_additions() as $addition ) {
			$addition->set_field( $this->field );
			if ( ! $addition->is_enabled() ) {
				continue;
			}

			if ( ! isset( $posted_values[ $addition->get_key() ] ) ) {
				continue;
			}

			$value = $addition->sanitize( $posted_values[ $addition->get_key() ] );
			$addition->validate( $value );

			if ( $value === null ) {
				continue;
			}

			if ( $addition->get_type() === 'numeric' ) {
				if ( $value < 1 ) {
					continue;
				}

				$price_per_unit = $addition->get_price_per_unit();
				$price_per_day = $price_per_unit * $value;
				$price = $price_per_day;
				if ( !! $addition->get_prop('repeat') ) {
					$price = $price_per_day * $day_count;
				}

				$details[ $addition->get_key() ] = [
					'type' => 'numeric',
					'price_per_unit' => $price_per_unit,
					'units' => $value,
					'price_per_day' => $price_per_day,
					'price' => $price,
				];
			} elseif ( $addition->get_type() === 'checkbox' ) {
				if ( $value !== true ) {
					continue;
				}

				$price_per_day = $addition->get_price();
				$price = $price_per_day;
				if ( !! $addition->get_prop('repeat') ) {
					$price = $price_per_day * $day_count;
				}

				$details[ $addition->get_key() ] = [
					'type' => 'checkbox',
					'price_per_day' => $price_per_day,
					'price' => $price,
				];
			} elseif ( $addition->get_type() === 'select' ) {
				$price_per_day = $addition->get_price_for_choice( $value );
				$price = $price_per_day;
				if ( $price !== null ) {
					if ( !! $addition->get_prop('repeat') ) {
						$price = $price_per_day * $day_count;
					}

					$details[ $addition->get_key() ] = [
						'type' => 'select',
						'choice' => $value,
						'price_per_day' => $price_per_day,
						'price' => $price,
					];
				}
			}
		}

		return $details;
	}

	/**
	 * Information fields
	 */
	private function _prepare_field_details() {
		$raw_field_details = json_decode( stripslashes( $_POST['fields'] ?? '' ), true );
		$fields = $this->product_type->get_fields();
		$field_details = [];

		foreach ( $fields as $field ) {
			$field_details[ $field->get_key() ] = null;
			if ( isset( $raw_field_details[ $field->get_key() ] ) ) {
				$field_details[ $field->get_key() ] = $field->sanitize( $raw_field_details[ $field->get_key() ] );
			}

			$field->check_validity( $field_details[ $field->get_key() ] );
		}

		foreach ( $fields as $field ) {
			$field_details[ $field->get_key() ] = $field->prepare_for_storage( $field_details[ $field->get_key() ] );
			if ( is_null( $field_details[ $field->get_key() ] ) ) {
				unset( $field_details[ $field->get_key() ] );
			}
		}

		return $field_details;
	}

	private function _prepare_pricing_details() {
		$is_using_price_id = $this->product_type->config('settings.payments.pricing') === 'price_id';
		if ( $is_using_price_id ) {
			$stripe = \Voxel\Stripe::getClient();
			$price_id = $this->product_config['price_id'] ?? null;
			$price = $stripe->prices->retrieve( $price_id );

			$total = $price->unit_amount;
			$currency = $price->currency;

			if ( ! \Voxel\Stripe\Currencies::is_zero_decimal( $currency ) ) {
				$total /= 100;
			}

			$details = [
				'base_price' => $total,
				'total' => $total,
				'currency' => $currency,
			];

			if ( $this->product_type->get_mode() === 'subscription' ) {
				$details['interval'] = [
					'unit' => $price->recurring->interval ?? null,
					'count' => $price->recurring->interval_count ?? null,
				];
			}

			return $details;
		}

		$day_count = $this->_get_repeat_day_count();
		$base_price = $this->product_config['base_price'] * $day_count;
		$total = $base_price;
		$addition_details = $this->_prepare_addition_details();
		$currency = \Voxel\get( 'settings.stripe.currency', 'USD' );

		foreach ( $addition_details as $addition_key => $addition ) {
			$total += $addition['price'];
		}

		$details = [
			'base_price' => $base_price,
			'total' => $total,
			'currency' => $currency,
		];

		if ( $this->product_type->get_mode() === 'subscription' ) {
			$details['interval'] = [
				'unit' => $this->product_config['interval']['unit'] ?? null,
				'count' => $this->product_config['interval']['count'] ?? null,
			];
		}

		return $details;
	}

	protected function _get_repeat_day_count(): int {
		$calendar_type = $this->product_type->config('calendar.type');
		$calendar_format = $this->product_type->config('calendar.format');
		$allow_range = $this->product_type->config('calendar.allow_range');
		if ( ! ( $calendar_type === 'booking' && $calendar_format === 'days' && $allow_range ) ) {
			return 1;
		}

		$booking = $this->_prepare_booking_details();
		$checkin = strtotime( $booking['checkin'] ?? null );
		$checkout = strtotime( $booking['checkout'] ?? null );
		if ( ! ( $checkin && $checkout ) ) {
			return 1;
		}

		return abs( floor( ( $checkout - $checkin ) / 86400 ) ) + 1;
	}

	protected function checkout_successful() {
		$session_id = $_REQUEST['session_id'] ?? null;
		if ( ! $session_id ) {
			die;
		}

		$order = \Voxel\Order::find( [
			'session_id' => $session_id,
			'customer_id' => get_current_user_id(),
		] );

		if ( $order ) {
			wp_safe_redirect( $order->get_link() );
			die;
		}

		wp_safe_redirect( home_url( '/' ) );
		die;
	}

	protected function checkout_canceled() {
		$session_id = $_REQUEST['session_id'] ?? null;
		if ( ! ( $session_id ) ) {
			die;
		}

		$order = \Voxel\Order::find( [
			'session_id' => $session_id,
			'customer_id' => get_current_user_id(),
		] );

		if ( $order ) {
			$order->update( 'status', \Voxel\Order::STATUS_CANCELED );
			$order->note( \Voxel\Order_Note::CHECKOUT_CANCELED );

			wp_safe_redirect( $order->get_link() );
			die;
		}

		wp_safe_redirect( home_url( '/' ) );
		die;
	}
}
