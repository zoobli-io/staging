<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Recurring_Date_Field extends Base_Post_Field {

	protected $props = [
		'type' => 'recurring-date',
		'label' => 'Recurring Date',
		'allow_multiple' => true,
		'max_date_count' => 3,
		'allow_recurrence' => true,
		'enable_timepicker' => true,
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'key' => $this->get_key_model(),
			'description' => $this->get_description_model(),
			'required' => $this->get_required_model(),
			'allow_multiple' => [
				'type' => Form_Models\Switcher_Model::class,
				'label' => 'Enable multiple dates',
				'description' => 'Allow users to enter multiple dates',
			],
			'max_date_count' => [
				'v-if' => 'field.allow_multiple',
				'type' => Form_Models\Number_Model::class,
				'label' => 'Maximum number of dates allowed',
			],
			'allow_recurrence' => [
				'type' => Form_Models\Switcher_Model::class,
				'label' => 'Enable recurring dates',
				'description' => 'Allow users to repeat a date at regular intervals (e.g. every 2 weeks, every 6 months, etc.)',
			],
			'enable_timepicker' => [
				'type' => Form_Models\Switcher_Model::class,
				'label' => 'Enable timepicker',
				'description' => 'Set whether users can also select the time of day when adding a date.',
			],
		];
	}

	public function sanitize( $value ) {
		$sanitized = [];
		$allowed_units = ['day', 'week', 'month', 'year'];

		foreach ( (array) $value as $date ) {
			$start_date = strtotime( $date['startDate'] ?? null );
			$end_date = strtotime( $date['endDate'] ?? null );
			if ( ! ( $start_date && $end_date ) ) {
				continue;
			}

			if ( $this->props['enable_timepicker'] ) {
				$start_time = strtotime( $date['startTime'] ?? null );
				$end_time = strtotime( $date['endTime'] ?? null );
				if ( ! ( $start_time && $end_time ) ) {
					continue;
				}

				$start_date += 60 * (
					( absint( date( 'H', $start_time ) ) * 60 ) + absint( date( 'i', $start_time ) )
				);

				$end_date += 60 * (
					( absint( date( 'H', $end_time ) ) * 60 ) + absint( date( 'i', $end_time ) )
				);
			}

			if ( $end_date < $start_date ) {
				continue;
			}

			$is_recurring = false;
			if ( $this->props['allow_recurrence'] && ! empty( $date['repeat'] ) ) {
				$is_recurring = true;

				$unit = $date['unit'] ?? null;
				if ( ! in_array( $unit, $allowed_units, true ) ) {
					continue;
				}

				$frequency = absint( $date['frequency'] ?? null );
				if ( $frequency < 1 ) {
					continue;
				}

				$until = strtotime( $date['until'] ?? null );
				if ( ! $until ) {
					continue;
				}
			}

			if ( $is_recurring ) {
				$sanitized[] = [
					'start' => date( 'Y-m-d H:i:s', $start_date ),
					'end' => date( 'Y-m-d H:i:s', $end_date ),
					'frequency' => $frequency,
					'unit' => $unit,
					'until' => date( 'Y-m-d', $until ),
				];
			} else {
				$sanitized[] = [
					'start' => date( 'Y-m-d H:i:s', $start_date ),
					'end' => date( 'Y-m-d H:i:s', $end_date ),
				];
			}
		}

		if ( empty( $sanitized ) ) {
			return null;
		}

		return $sanitized;
	}

	public function validate( $value ): void {
		if ( ! $this->props['allow_multiple'] && count( $value ) > 1 ) {
			throw new \Exception( sprintf(
				_x( 'Only one entry allowed in %s field', 'field validation', 'voxel' ),
				$this->get_label()
			) );
		}

		if ( $this->props['allow_multiple'] && count( $value ) > $this->props['max_date_count'] ) {
			throw new \Exception( sprintf(
				_x( 'Only up to %d entries allowed in %s field', 'field validation', 'voxel' ),
				$this->props['max_date_count'],
				$this->get_label()
			) );
		}
	}

	public function update( $value ): void {
		global $wpdb;

		if ( empty( $value ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			// delete previous dates
			$wpdb->delete( $wpdb->prefix.'voxel_events', [
				'post_id' => $this->post->get_id(),
				'field_key' => $this->get_key(),
			] );

			// prepare and insert new dates
			$rows = [];
			$reference_date = new \DateTime( '2020-01-01 00:00:00', $this->post->get_timezone() );
			$timezone_offset = $reference_date->format('P');

			foreach ( $value as $date ) {
				$date['tz'] = $timezone_offset;

				$rows[] = $wpdb->prepare(
					// post_id, post_type, field_key, details
					'(%d,%s,%s,%s)',
					$this->post->get_id(),
					$this->post->post_type->get_key(),
					$this->get_key(),
					wp_json_encode( $date )
				);
			}

			// update database with new values
			if ( ! empty( $rows ) ) {
				$query = "INSERT INTO {$wpdb->prefix}voxel_events
					(post_id, post_type, field_key, details) VALUES ";
				$query .= implode( ',', $rows );
				$wpdb->query( $query );
			}
		}
	}

	public function get_value_from_post() {
		global $wpdb;

		$rows = $wpdb->get_col( $wpdb->prepare( "
			SELECT details FROM {$wpdb->prefix}voxel_events
			WHERE post_id = %d AND field_key = %s
			ORDER BY id ASC
		", $this->post->get_id(), $this->get_key() ) );

		return array_filter( array_map( function( $details ) {
			return json_decode( $details, ARRAY_A );
		}, $rows ) );
	}

	protected function editing_value() {
		return array_filter( array_map( function( $date ) {
			if ( ! isset( $date['start'], $date['end'] ) ) {
				return null;
			}

			return [
				'startDate' => date( 'Y-m-d', strtotime( $date['start'] ) ),
				'startTime' => date( 'H:i', strtotime( $date['start'] ) ),
				'endDate' => date( 'Y-m-d', strtotime( $date['end'] ) ),
				'endTime' => date( 'H:i', strtotime( $date['end'] ) ),
				'repeat' => ( $date['unit'] ?? null ) !== null,
				'frequency' => $date['frequency'] ?? null,
				'unit' => $date['unit'] ?? null,
				'until' => date( 'Y-m-d', strtotime( $date['until'] ?? null ) ),
			];
		}, (array) $this->get_value() ) );
	}

	protected function frontend_props() {
		wp_enqueue_style( 'pikaday' );
		wp_enqueue_script( 'pikaday' );

		return [
			'max_date_count' => $this->props['allow_multiple'] ? $this->props['max_date_count'] : 1,
			'allow_recurrence' => $this->props['allow_recurrence'],
			'enable_timepicker' => $this->props['enable_timepicker'],
			'units' => [
				'day' => _x( 'Day(s)', 'recurring date unit', 'voxel' ),
				'week' => _x( 'Week(s)', 'recurring date unit', 'voxel' ),
				'month' => _x( 'Month(s)', 'recurring date unit', 'voxel' ),
				'year' => _x( 'Year(s)', 'recurring date unit', 'voxel' ),
			],
		];
	}

	public function exports() {
		return [
			'label' => $this->get_label(),
			'type' => \Voxel\T_STRING,
			'callback' => function() {
				return $this->get_value();
			},
		];
	}
}
