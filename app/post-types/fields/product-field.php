<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Product_Field extends Base_Post_Field {
	use Traits\Product_Field_Helpers;

	protected $props = [
		'type' => 'product',
		'label' => 'Product',
		'product-type' => '',
		'recurring-date-field' => '',
	];

	public function get_models(): array {
		$choices = [];
		foreach ( \Voxel\Product_Type::get_all() as $product_type ) {
			$choices[ $product_type->get_key() ] = $product_type->get_label();
		}

		return [
			'label' => $this->get_label_model(),
			'key' => $this->get_key_model(),
			'description' => $this->get_description_model(),
			'product-type' => [
				'type' => Form_Models\Select_Model::class,
				'label' => 'Product type',
				'width' => '1/1',
				'choices' => $choices,
			],
			'recurring-date-field' => function() { ?>
				<div class="ts-form-group ts-col-1-1" v-if="$root.options.product_types[ field['product-type'] ]?.calendar_type === 'recurring-date'">
					<label>Get bookable instances from field:</label>
					<select v-model="field['recurring-date-field']">
						<option v-for="field in $root.getFieldsByType('recurring-date')" :value="field.key">
							{{ field.label }}
						</option>
					</select>
				</div>
			<?php },
			'required' => $this->get_required_model(),
		];
	}

	public function sanitize( $value ) {
		$product_type = $this->get_product_type();
		if ( ! $product_type ) {
			return null;
		}

		$is_using_price_id = $product_type->is_using_price_id();

		$sanitized = [];
		$sanitized['enabled'] = $this->is_required() ? true : ( (bool) ( $value['enabled'] ?? true ) );
		$sanitized['base_price'] = abs( (float) ( $value['base_price'] ?? 0 ) );

		// sanitize recurring price
		if ( $product_type->get_mode() === 'subscription' && ! $is_using_price_id ) {
			$interval_unit = \Voxel\from_list( $value['interval']['unit'] ?? null, [ 'day', 'week', 'month' ], 'month' );
			$interval_limit = ( $interval_unit === 'day' ? 365 : ( $interval_unit === 'week' ? 52 : 12 ) );
			$interval_count = \Voxel\clamp( absint( $value['interval']['count'] ?? 1 ), 1, $interval_limit );

			$sanitized['interval'] = [
				'unit' => $interval_unit,
				'count' => $interval_count,
			];
		}

		// sanitize calendar
		// @todo: validate excluded weekdays, days, timeslots
		if ( $product_type->config('calendar.type') === 'booking' ) {
			$calendar = $value['calendar'] ?? [];
			$sanitized['calendar'] = [];
			$sanitized['calendar']['make_available_next'] = absint( $calendar['make_available_next'] ?? null );
			$sanitized['calendar']['bookable_per_instance'] = absint( $calendar['bookable_per_instance'] ?? null );

			$weekday_indexes = \Voxel\get_weekday_indexes();
			$sanitized['calendar']['excluded_weekdays'] = array_filter(
				(array) ( $calendar['excluded_weekdays'] ?? [] ),
				function( $weekday ) use ( $weekday_indexes ) { return isset( $weekday_indexes[ $weekday ] ); }
			);

			$sanitized['calendar']['excluded_days'] = array_filter( array_map( function( $day ) {
				$timestamp = strtotime( $day );
				return $timestamp ? date( 'Y-m-d', $timestamp ) : null;
			}, (array) ( $calendar['excluded_days'] ?? [] ) ) );

			if ( $product_type->config('calendar.format') === 'slots' ) {
				$sanitized['calendar']['timeslots'] = array_filter( array_map( function( $slot ) {
					$timestamp = strtotime( $slot );
					return $timestamp ? date( 'H:i', $timestamp ) : null;
				}, (array) ( $calendar['timeslots'] ?? [] ) ) );
			}
		}

		if ( $product_type->config('calendar.type') === 'recurring-date' ) {
			$calendar = $value['calendar'] ?? [];
			$sanitized['calendar'] = [];
			$sanitized['calendar']['make_available_next'] = absint( $calendar['make_available_next'] ?? null );
			$sanitized['calendar']['bookable_per_instance'] = absint( $calendar['bookable_per_instance'] ?? null );
		}

		// sanitize additions
		if ( ! $is_using_price_id ) {
			if ( ! empty( $product_type->get_additions() ) ) {
				$sanitized['additions'] = [];
				foreach ( $product_type->get_additions() as $addition ) {
					$sanitized['additions'][ $addition->get_key() ] = $addition->sanitize_config(
						$value['additions'][ $addition->get_key() ] ?? []
					);
				}
			}
		}

		// sanitize vendor notes
		if ( $product_type->config('notes.enabled') ) {
			$sanitized['notes'] = sanitize_textarea_field( $value['notes'] ?? null );
		}

		if ( $is_using_price_id ) {
			$sanitized['price_id'] = sanitize_text_field( $value['price_id'] ?? null );
		}

		return $sanitized;
	}

	public function validate( $value ): void {
		$product_type = $this->get_product_type();
		if ( $product_type ) {
			if ( ! $product_type->is_using_price_id() ) {
				foreach ( $product_type->get_additions() as $addition ) {
					$addition->validate_config(
						$value['additions'][ $addition->get_key() ] ?? []
					);
				}
			}
		}
	}

	public function update( $value ): void {
		if ( $this->is_empty( $value ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			update_post_meta( $this->post->get_id(), $this->get_key(), wp_json_encode( $value ) );
		}

		// calculate and cache fully booked days
		$this->cache_fully_booked_days();
	}

	public function get_value_from_post() {
		return (array) json_decode( get_post_meta(
			$this->post->get_id(), $this->get_key(), true
		), ARRAY_A );
	}

	public static function is_repeatable(): bool {
		return false;
	}

	protected function frontend_props() {
		wp_enqueue_style( 'pikaday' );
		wp_enqueue_script( 'pikaday' );

		if ( ! ( $product_type = $this->get_product_type() ) ) {
			return [];
		}

		$config = $product_type->get_config();
		$notes = $config['notes'] ?? [];
		$value = $this->get_value();

		return [
			'mode' => $product_type->get_mode(),
			'is_using_price_id' => $product_type->is_using_price_id(),
			'calendar_type' => $config['calendar']['type'],
			'calendar_format' => $config['calendar']['format'],
			'recurring_date_field' => $this->props['recurring-date-field'],
			'weekdays' => \Voxel\get_weekdays(),
			'notes' => [
				'enabled' => $notes['enabled'] ?? true,
				'label' => $notes['label'] ?? '',
				'description' => $notes['description'] ?? '',
				'placeholder' => $notes['placeholder'] ?? '',
			],
			'additions' => array_values( array_map( function( $addition ) use ( $value ) {
				$props = $addition->get_props();
				$props['values'] = $addition->sanitize_config( [] );
				if ( $value && isset( $value['additions'][ $addition->get_key() ] ) ) {
					$props['values'] = $addition->sanitize_config( $value['additions'][ $addition->get_key() ]  );
				}

				return $props;
			}, $product_type->get_additions() ) ),
			'intervals' => [
				'day' => 'Day(s)',
				'week' => 'Week(s)',
				'month' => 'Month(s)',
			],
			'interval_limits' => [
				'day' => 365,
				'week' => 52,
				'month' => 12,
			],
		];
	}

	protected function editing_value() {
		$value = $this->get_value();
		if ( ! ( $product_type = $this->get_product_type() ) || ! $value ) {
			return null;
		}

		return [
			'enabled' => $value['enabled'] ?? true,
			'base_price' => $value['base_price'] ?? null,
			'notes' => $value['notes'] ?? null,
			'calendar' => $value['calendar'] ?? [],
			'interval' => $value['interval'] ?? [],
			'price_id' => $value['price_id'] ?? null,
		];
	}

	public function get_product_type() {
		return \Voxel\Product_Type::get( $this->props['product-type'] );
	}

	public function get_product_form_config() {
		$value = $this->get_value();
		if ( ! ( $product_type = $this->get_product_type() ) || ! $value ) {
			return null;
		}

		$calendar = $value['calendar'] ?? [];
		$additions = [];
		foreach ( $product_type->get_additions() as $addition ) {
			$addition->set_field( $this );
			if ( ! $addition->is_enabled() ) {
				continue;
			}

			$additions[ $addition->get_key() ] = $addition->get_product_form_config();
		}

		$fields = [];
		foreach ( $product_type->get_fields() as $field ) {
			$fields[ $field->get_key() ] = $field->get_frontend_config();
		}

		if ( $product_type->config('calendar.type') === 'recurring-date' ) {
			$recurring_date_field = $this->post->get_field( $this->props['recurring-date-field'] );
			if ( $recurring_date_field ) {
				$bookable_dates = \Voxel\Utils\Recurring_Date\get_upcoming(
					$recurring_date_field->get_value(),
					20,
					date('Y-m-d', \Voxel\utc()->modify( sprintf(
						'+%d days',
						$calendar['make_available_next'] ?? 180
					) )->getTimestamp() )
				);

				$bookable_dates = array_map( function( $date ) {
					$start = \Voxel\date_format( strtotime( $date['start'] ) );
					$end = \Voxel\date_format( strtotime( $date['end'] ) );
					$date['formatted'] = $start === $end ? $start : sprintf( '%s - %s', $start, $end );

					return $date;
				}, $bookable_dates );
			}
		}

		return [
			'mode' => $product_type->get_mode(),
			'enabled' => $value['enabled'],
			'base_price' => $value['base_price'],
			'additions' => $additions,
			'fields' => $fields,
			'calendar' => [
				'type' => $product_type->config('calendar.type'),
				'format' => $product_type->config('calendar.format'),
				'allow_range' => $product_type->config('calendar.allow_range'),
				'make_available_next' => $calendar['make_available_next'] ?? 180,
				'excluded_weekdays' => $calendar['excluded_weekdays'] ?? [],
				'excluded_days' => $calendar['excluded_days'] ?? [],
				'timeslots' => $calendar['timeslots'] ?? [],
			],
			'recurring_date' => [
				'bookable' => $bookable_dates ?? [],
			],
			'is_user_logged_in' => is_user_logged_in(),
			'auth_url' => \Voxel\get_auth_url(),
		];
	}


	public function exports() {
		$additions = [];
		if ( $product_type = $this->get_product_type() ) {
			foreach ( $product_type->get_additions() as $addition ) {
				$addition->set_field( $this );
				if ( $exports = $addition->exports() ) {
					$additions[ $addition->get_key() ] = $exports;
				}
			}
		}

		return [
			'type' => \Voxel\T_OBJECT,
			'label' => $this->get_label(),
			'properties' => [
				'base_price' => [
					'label' => 'Base price',
					'type' => \Voxel\T_STRING,
					'callback' => function() {
						$value = $this->get_value();
						return $value['base_price'] ?? '';
					},
				],
				'additions' => [
					'type' => \Voxel\T_OBJECT,
					'label' => 'Additions',
					'properties' => $additions,
				],
			],
		];
	}
}
