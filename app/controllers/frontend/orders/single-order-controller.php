<?php

namespace Voxel\Controllers\Frontend\Orders;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Single_Order_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_orders.view', '@view_order' );
	}

	protected function view_order() {
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

			$customer = $order->get_customer();
			$post = $order->get_post();
			$product_type = $order->get_product_type();
			$product_field = $order->get_product_field();
			$details = $order->get_details();
			$object_details = $order->get_object_details();
			$currency = $details['pricing']['currency'];

			$is_author = $post && $post->get_author_id() === get_current_user_id();
			$is_customer = $customer && $customer->get_id() === get_current_user_id();

			// prepare pricing details
			$pricing = [
				'period' => $order->get_price_period_for_display(),
				'base_price' => \Voxel\currency_format( $details['pricing']['base_price'], $currency, false ),
				'total' => \Voxel\currency_format( $details['pricing']['total'], $currency, false ),
				'additions' => [],
			];

			foreach ( ( $details['additions'] ?? [] ) as $addition_key => $_data ) {
				$addition = $product_type->get_addition( $addition_key );
				$label = $addition ? $addition->get_label() : $addition_key;

				if ( $_data['type'] === 'numeric' ) {
					$price = $_data['price'];
					$pricing['additions'][] = [
						'label' => sprintf(
							'%s × %s',
							$label,
							number_format_i18n( $_data['units'] )
						),
						'price' => \Voxel\currency_format( $price, $currency, false ),
					];
				} elseif ( $_data['type'] === 'checkbox' ) {
					$price = $_data['price'];
					$pricing['additions'][] = [
						'label' => $label,
						'price' => \Voxel\currency_format( $price, $currency, false ),
					];
				} elseif ( $_data['type'] === 'select' ) {
					$price = $_data['price'];
					$pricing['additions'][] = [
						'label' => $label,
						'price' => \Voxel\currency_format( $price, $currency, false ),
					];
				}
			}

			// prepare booking
			$booking = null;
			if ( ! empty( $details['booking'] ) ) {
				$check_in = strtotime( $details['booking']['checkin'] ?? null );
				$check_out = strtotime( $details['booking']['checkout'] ?? null );
				$timeslot = $details['booking']['timeslot'] ?? [];
				$slot_from = strtotime( $timeslot['from'] ?? null );
				$slot_to = strtotime( $timeslot['to'] ?? null );

				if ( $check_in && $check_out ) {
					$booking = [
						'type' => 'date_range',
						'from' => date_i18n( get_option( 'date_format' ), $check_in ),
						'to' => date_i18n( get_option( 'date_format' ), $check_out ),
					];
				} elseif ( $check_in && $slot_from && $slot_to ) {
					$booking = [
						'type' => 'timeslot',
						'date' => date_i18n( get_option( 'date_format' ), $check_in ),
						'from' => date_i18n( get_option( 'time_format' ), $slot_from ),
						'to' => date_i18n( get_option( 'time_format' ), $slot_to ),
					];
				} elseif ( $check_in ) {
					$booking = [
						'type' => 'single_date',
						'date' => date_i18n( get_option( 'date_format' ), $check_in ),
					];
				}
			}

			// prepare addition details
			$additions = [];
			$_additions = $details['additions'] ?? [];
			foreach ( $product_type->get_additions() as $addition ) {
				$a = $_additions[ $addition->get_key() ] ?? null;

				if ( $addition->get_type() === 'numeric' ) {
					$content = number_format_i18n( $a ? $a['units'] : 0 );
				} elseif ( $addition->get_type() === 'checkbox' ) {
					$content = $a ? _x( 'Yes', 'addition enabled', 'voxel' ) : _x( 'No', 'addition enabled', 'voxel' );
				} elseif ( $addition->get_type() === 'select' ) {
					$content = $a['choice'] ?? null;
					if ( $choice = $addition->get_choice_by_key( $a['choice'] ?? null ) ) {
						$content = $choice['label'];
					}
				}

				$additions[] = [
					'label' => $addition->get_label(),
					'content' => $content,
					'icon' => \Voxel\get_icon_markup( $addition->get_prop('icon') ),
				];
			}

			// prepare field details
			$fields = [];
			$_fields = $details['fields'] ?? [];
			foreach ( $product_type->get_fields() as $field ) {
				$content = $field->prepare_for_display( $_fields[ $field->get_key() ] ?? null );
				if ( is_null( $content ) ) {
					continue;
				}

				$fields[] = [
					'label' => $field->get_label(),
					'content' => $content,
				];
			}

			$notes = \Voxel\Order_Note::query( [
				'order_id' => $order->get_id(),
			] );

			// actions
			$actions = [];
			if ( $order->get_mode() === 'payment' ) {
				if ( $is_author ) {
					if ( $order->get_status() === \Voxel\Order::STATUS_PENDING_APPROVAL ) {
						$actions[] = 'author.decline';
					} elseif ( $order->get_status() === \Voxel\Order::STATUS_COMPLETED ) {
						$actions[] = 'receipt';
					} elseif ( $order->get_status() === \Voxel\Order::STATUS_REFUND_REQUESTED ) {
						$actions[] = 'author.approve_refund';
						$actions[] = 'author.decline_refund';
					}
				}

				if ( $is_customer ) {
					if ( $order->get_status() === \Voxel\Order::STATUS_PENDING_APPROVAL ) {
						$actions[] = 'customer.cancel';
					} elseif ( $order->get_status() === \Voxel\Order::STATUS_COMPLETED ) {
						$actions[] = 'receipt';
						$actions[] = 'customer.request_refund';
					} elseif ( $order->get_status() === \Voxel\Order::STATUS_REFUND_REQUESTED ) {
						$actions[] = 'customer.cancel_refund_request';
					}

					$actions[] = 'customer.portal';
				}
			}

			$subscription_details = [
				'exists' => false,
			];

			if ( $order->get_mode() === 'subscription' ) {
				$subscription_details = [
					'exists' => ! empty( $object_details ),
				];

				if ( $subscription_details['exists'] ) {
					$subscription_details['status'] = $object_details['status'] ?? null;
					$subscription_details['cancel_at_period_end'] = $object_details['cancel_at_period_end'] ?? null;
					$subscription_details['current_period_end'] = \Voxel\date_format( $object_details['current_period_end'] ?? null );
					$subscription_details['trial_end'] = \Voxel\date_format( $object_details['trial_end'] ?? null );
				}

				if ( $is_customer ) {
					if ( $object_details['cancel_at_period_end'] ?? null ) {
						$actions[] = 'customer.subscriptions.reactivate';
					} elseif ( in_array( $order->get_status(), [ 'sub_incomplete', 'sub_past_due', 'sub_unpaid' ], true ) ) {
						$actions[] = 'customer.subscriptions.finalize_payment';
					}

					if ( ! in_array( $order->get_status(), [ 'sub_canceled', 'sub_incomplete_expired', 'pending_payment' ], true ) && ! ( $object_details['cancel_at_period_end'] ?? null ) ) {
						$actions[] = 'customer.subscriptions.cancel';
					}

					$actions[] = 'customer.portal';
				}
			}

			// house rules
			$vendor_rules = null;
			if ( $product_field ) {
				$product_field_config = (array) $product_field->get_value();
				if ( is_array( $product_field_config ) ) {
					$vendor_rules = $product_field_config['notes'] ?? null;
				}
			}

			$data = [
				'id' => $order->get_id(),
				'mode' => $order->get_mode(),
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
				'subscription' => $subscription_details ?? null,
				'pricing' => $pricing,
				'booking' => $booking,
				'additions' => $additions,
				'fields' => $fields,
				'notes' => array_map( function( $note ) {
					return $note->prepare();
				}, $notes ),
				'role' => [
					'is_author' => $is_author,
					'is_customer' => $is_customer,
				],
				'actions' => array_values( array_unique( $actions ) ),
				'vendor_rules' => $vendor_rules,
			];

			// dd($order, $data);
			return wp_send_json( [
				'success' => true,
				'data' => apply_filters( 'voxel/view-order/get-data', $data, $order ),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}
}
