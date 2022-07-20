<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Work_Hours_Field extends Base_Post_Field {

	protected $props = [
		'type' => 'work-hours',
		'label' => 'Work Hours',
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'key' => $this->get_key_model(),
			'description' => $this->get_description_model(),
			'required' => $this->get_required_model(),
		];
	}

	public function sanitize( $value ) {
		$weekdays = \Voxel\get_weekdays();
		$used_days = [];
		$sanitized = [];
		foreach ( (array) $value as $group ) {
			$days = $group['days'] ?? [];
			foreach ( $days as $group_index => $day ) {
				if ( ! isset( $weekdays[ $day ] ) ) {
					unset( $days[ $group_index ] );
				}

				if ( isset( $used_days[ $day ] ) ) {
					unset( $days[ $group_index ] );
				}
			}

			if ( empty( $days ) ) {
				continue;
			}

			$status = $group['status'] ?? '';
			if ( ! in_array( $status, [ 'hours', 'open', 'closed', 'appointments_only' ], true ) ) {
				continue;
			}

			$hours = [];
			if ( $status === 'hours' ) {
				$hours = $group['hours'] ?? [];
				foreach ( $hours as $slot_index => $slot ) {
					$from = strtotime( $slot['from'] ?? null );
					$to = strtotime( $slot['to'] ?? null );
					if ( ! ( $from && $to ) ) {
						unset( $hours[ $slot_index ] );
					}
				}

				if ( empty( $hours ) ) {
					continue;
				}
			}

			foreach ( $days as $day ) {
				$used_days[ $day ] = true;
			}

			$data = [];
			$data['days'] = $days;
			$data['status'] = $status;
			$data['hours'] = $hours;

			$sanitized[] = $data;
		}

		if ( empty( $sanitized ) ) {
			return null;
		}

		return $sanitized;
	}

	public function update( $value ): void {
		if ( $this->is_empty( $value ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			update_post_meta( $this->post->get_id(), $this->get_key(), wp_json_encode( $value ) );
		}

		global $wpdb;
		$wpdb->delete( $wpdb->prefix.'voxel_work_hours', [
			'post_id' => $this->post->get_id(),
			'field_key' => $this->get_key(),
		] );

		$ranges = \Voxel\merge_ranges( $this->get_open_ranges() );
		foreach ( $ranges as $range ) {
			$rows[] = $wpdb->prepare(
				'(%d,%s,%s,%d,%d)',
				$this->post->get_id(),
				$this->post->post_type->get_key(),
				$this->get_key(),
				$range[0],
				$range[1]
			);
		}

		if ( ! empty( $rows ) ) {
			$query = "INSERT INTO {$wpdb->prefix}voxel_work_hours
				(`post_id`, `post_type`, `field_key`, `start`, `end`) VALUES ";
			$query .= implode( ',', $rows );
			$wpdb->query( $query );
		}
	}

	public function get_value_from_post() {
		if ( ! $this->post ) {
			return null;
		}

		$value = (array) json_decode( get_post_meta(
			$this->post->get_id(), $this->get_key(), true
		), ARRAY_A );

		if ( empty( $value ) ) {
			return null;
		}

		return $value;
	}

	public static function is_repeatable(): bool {
		return false;
	}

	protected function editing_value() {
		$value = $this->get_value();
		return ! is_null( $value ) ? $value : [];
	}

	protected function frontend_props() {
		return [
			'weekdays' => \Voxel\get_weekdays(),
			'statuses' => [
				'hours' => _x( 'Enter hours', 'work hours', 'voxel' ),
				'open' => _x( 'Open all day', 'work hours', 'voxel' ),
				'closed' => _x( 'Closed all day', 'work hours', 'voxel' ),
				'appointments_only' => _x( 'Appointments only', 'work hours', 'voxel' ),
			],
		];
	}

	public function get_schedule() {
		$value = $this->get_value();
		if ( $value === null ) {
			return null;
		}

		$schedule = [];
		foreach ( $value as $group ) {
			foreach ( $group['days'] as $day ) {
				$schedule[ $day ] = [
					'status' => $group['status'],
					'hours' => $group['hours'],
				];
			}
		}

		return $schedule;
	}

	public function get_open_ranges() {
		$ranges = [];
		$schedule = $this->get_schedule();
		if ( $schedule === null ) {
			return [];
		}

		$indexes = \Voxel\get_weekday_indexes();

		// day length in minutes
		$day_length = 1440;
		foreach ( $schedule as $day => $data ) {
			$index = $indexes[ $day ];
			$day_start = ( $day_length * $index ) + $day_length;

			// open all day
			if ( $data['status'] === 'open' ) {
				$ranges[] = [ $day_start, $day_start + $day_length ];

				if ( $day === 'mon' ) {
					$ranges[] = [ 11520, 12960 ];
				}

				if ( $day === 'sun' ) {
					$ranges[] = [ 0, 1440 ];
				}
			}

			if ( $data['status'] === 'hours' ) {
				foreach ( $data['hours'] as $slot ) {
					$from = \DateTime::createFromFormat( 'H:i', $slot['from'] ?? null );
					$to = \DateTime::createFromFormat( 'H:i', $slot['to'] ?? null );
					if ( $from && $to ) {
						$from_minute = $day_start + ( absint( $from->format('H') ) * 60 ) + absint( $from->format('i') );
						$to_minute = $day_start + ( absint( $to->format('H') ) * 60 ) + absint( $to->format('i') );

						// handle overnight schedules, e.g. 19:00 - 03:00
						if ( $to_minute <= $from_minute ) {
							$to_minute += $day_length;
						}

						// handle overnight schedules going from sunday to monday
						if ( $to_minute > 11520 ) {
							$monday_minutes = $to_minute - 11520;
							$ranges[] = [ 1440, 1440 + $monday_minutes ];
							$ranges[] = [ 11520, 11520 + $monday_minutes ];

							$to_minute = 11520;
						}

						$ranges[] = [ $from_minute, $to_minute ];

						// store monday minutes post sunday
						if ( $from_minute >= 1440 && $from_minute < 2880 ) {
							$ranges[] = [ $from_minute + 10080, min( $to_minute + 10080, 12960 ) ];
						}

						// store sunday minutes pre monday
						if ( $from_minute >= 10080 && $from_minute < 11520 ) {
							$ranges[] = [ $from_minute - 10080, min( $to_minute - 10080, 1440 ) ];
						}
					}
				}
			}
		}

		return $ranges;
	}

	public function is_open_now(): bool {
		$ranges = $this->get_open_ranges();

		$now = new \DateTime( 'now', $this->post->get_timezone() );
		$day_index = absint( $now->format('N') ) - 1;
		$day_start = ( $day_index * 1440 ) + 1440;
		$minute_of_week = $day_start + ( absint( $now->format('H') ) * 60 ) + absint( $now->format('i') );

		foreach ( $ranges as $range ) {
			if ( $minute_of_week >= $range[0] && $minute_of_week <= $range[1] ) {
				return true;
			}
		}

		return false;
	}
}
