<?php

namespace Voxel\Post_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Availability_Filter extends Base_Filter {

	protected $props = [
		'type' => 'availability',
		'label' => 'Availability',
		'source' => '',
		'input_mode' => 'date-range',
		'range_matching' => 'lazy',
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'description' => $this->get_description_model(),
			'key' => $this->get_key_model(),
			'icon' => $this->get_icon_model(),
			'source' => $this->get_source_model( 'product' ),
			'input_mode' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'label' => 'Input mode',
				'width' => '1/1',
				'choices' => [
					'date-range' => 'Date range',
					'single-date' => 'Single date',
				],
			],
			'range_matching' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'v-if' => 'filter.input_mode === \'date-range\'',
				'label' => 'Date range matching',
				'width' => '1/1',
				'choices' => [
					'lazy' => 'Lazy: Match posts having at least one available day in given range',
					'greedy' => 'Greedy: Match posts having every day available in given range',
				],
			],
		];
	}

	public function setup( \Voxel\Post_Types\Index_Table $table ): void {
		$srid = ! \Voxel\is_using_mariadb() ? 'SRID 0' : '';
		$table->add_column( sprintf( '`%s` MULTILINESTRING NOT NULL %s', esc_sql( $this->db_key() ), $srid ) );
		$table->add_key( sprintf( 'SPATIAL KEY(`%s`)', esc_sql( $this->db_key() ) ) );

		// wd => weekdays
		$table->add_column( sprintf( '`%s` MULTILINESTRING NOT NULL %s', esc_sql( $this->db_key().'__wd' ), $srid ) );
		$table->add_key( sprintf( 'SPATIAL KEY(`%s`)', esc_sql( $this->db_key().'__wd' ) ) );
	}

	public function index( \Voxel\Post $post ): array {
		$weekdays = 'MULTILINESTRING((0 0,6 0))';
		$excluded_days = 'MULTILINESTRING((-0.1 0,-0.1 0))';
		$field = $post->get_field( $this->props['source'] );
		if ( $field && $field->get_type() === 'product' ) {
			$_weekdays = $field->_get_weekday_linestring();
			if ( $_weekdays !== null ) {
				$weekdays = $_weekdays;
			}

			$_excluded_days = $field->_get_excluded_days_linestring();
			if ( $_excluded_days !== null ) {
				$excluded_days = $_excluded_days;
			}
		}

		return [
			$this->db_key() => sprintf( 'ST_GeomFromText( \'%s\', 0 )', $excluded_days ),
			$this->db_key().'__wd' => sprintf( 'ST_GeomFromText( \'%s\', 0 )', $weekdays ),
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		$value = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $value === null ) {
			return;
		}

		global $wpdb;

		// query agains main column
		$start_day = date_diff( \Voxel\epoch(), new \DateTime( $value['start'], new \DateTimeZone('UTC') ) )->days;
		$end_day = date_diff( \Voxel\epoch(), new \DateTime( $value['end'], new \DateTimeZone('UTC') ) )->days;
		$linestring = sprintf( 'LINESTRING(%s 0,%s 0)', $start_day / 1000, $end_day / 1000 );

		if ( $this->props['input_mode'] === 'date-range' && $this->props['range_matching'] === 'greedy' ) {
			$query->where( sprintf(
				'( ST_Disjoint( ST_GeomFromText( \'%s\', 0 ), `%s` ) )',
				$linestring,
				esc_sql( $this->db_key() )
			) );
		} else {
			$query->where( sprintf(
				'( ST_Disjoint( ST_GeomFromText( \'%s\', 0 ), `%s` ) OR ST_Overlaps( ST_GeomFromText( \'%s\', 0 ), `%s` ) )',
				$linestring,
				esc_sql( $this->db_key() ),
				$linestring,
				esc_sql( $this->db_key() )
			) );
		}

		// query against weekday exceptions
		$indexes = [];
		$date = strtotime( $value['start'] );
		$end = strtotime( $value['end'] );
		do {
			$index = absint( date( 'N', $date ) ) - 1;
			$indexes[] = [ $index, $index ];
			if ( count( $indexes ) >= 7 ) {
				break;
			}

			$date = strtotime( '+1 day', $date );
		} while ( $date <= $end );

		$indexes = \Voxel\merge_ranges( $indexes );

		if ( count( $indexes ) === 1 ) {
			$linestring = sprintf( 'LINESTRING(%s 0,%s 0)', $indexes[0][0], $indexes[0][1] );
		} else {
			$strings = array_map( function( $range ) {
				return sprintf( '(%s 0,%s 0)', $range[0], $range[1] );
			}, $indexes );
			$linestring = sprintf( 'MULTILINESTRING(%s)', join( ',', $strings ) );
		}

		if ( $this->props['input_mode'] === 'date-range' && $this->props['range_matching'] === 'greedy' ) {
			$query->where( sprintf(
				'( ST_Within( ST_GeomFromText( \'%s\', 0 ), `%s` ) )',
				$linestring,
				esc_sql( $this->db_key() ).'__wd'
			) );
		} else {
			$query->where( sprintf(
				'( ST_Intersects( ST_GeomFromText( \'%s\', 0 ), `%s` ) )',
				$linestring,
				esc_sql( $this->db_key() ).'__wd'
			) );
		}

		// recurring date match
		$field = $this->post_type->get_field( $this->props['source'] );
		if ( $field && $field->get_type() === 'product' ) {
			$product_type = $field->get_product_type();
			$recurring_date_field = $this->post_type->get_field( $field->get_prop('recurring-date-field') );

			if ( $product_type && $product_type->config('calendar.type') === 'recurring-date' && $recurring_date_field ) {
				$range_start = esc_sql( $value['start'] );
				$range_end = esc_sql( $value['end'] );
				$join_key = esc_sql( $this->db_key() ).'__events';
				$post_type_key = esc_sql( $this->post_type->get_key() );
				$field_key = esc_sql( $recurring_date_field->get_key() );
				$where_clause = \Voxel\Utils\Recurring_Date\get_where_clause(
					$value['start'],
					$value['end'],
					$this->props['input_mode'],
					false
				);

				$query->join( "
					INNER JOIN (
						SELECT post_id FROM {$wpdb->prefix}voxel_events
						WHERE `post_type` = '{$post_type_key}' AND `field_key` = '{$field_key}' AND ( {$where_clause} )
					) AS `{$join_key}` ON `{$query->table->get_escaped_name()}`.post_id = `{$join_key}`.post_id
				" );

				$query->groupby( "`{$query->table->get_escaped_name()}`.post_id" );
			}
		}
	}

	public function parse_value( $value ) {
		if ( ! is_string( $value ) || empty( $value ) ) {
			return null;
		}

		$parts = explode( '..', $value );
		$start_stamp = strtotime( $parts[0] );
		$end_stamp = strtotime( $parts[1] ?? null );
		if ( ! $end_stamp || $this->props['input_mode'] === 'single-date' ) {
			$end_stamp = $start_stamp;
		}

		if ( ! ( $start_stamp && $end_stamp ) ) {
			return null;
		}

		// make sure start stamp is always lower than end stamp
		if ( $start_stamp > $end_stamp ) {
			$tmp = $end_stamp;
			$end_stamp = $start_stamp;
			$start_stamp = $tmp;
		}

		return [
			'start' => date( 'Y-m-d', $start_stamp ),
			'end' => date( 'Y-m-d', $end_stamp ),
		];
	}

	public function frontend_props() {
		wp_enqueue_style( 'pikaday' );
		wp_enqueue_script( 'pikaday' );

		$value = $this->parse_value( $this->get_value() );
		return [
			'inputMode' => $this->props['input_mode'],
			'value' => [
				'start' => $value ? $value['start'] : null,
				'end' => $value ? $value['end'] : null,
			],
			'displayValue' => [
				'start' => $value ? \Voxel\date_format( strtotime( $value['start'] ) ) : null,
				'end' => $value ? \Voxel\date_format( strtotime( $value['end'] ) ) : null,
			],
			'l10n' => [
				'checkIn' => _x( 'Check-in', 'availability filter', 'voxel' ),
				'checkOut' => _x( 'Check-out', 'availability filter', 'voxel' ),
				'pickDate' => _x( 'Choose date', 'availability filter', 'voxel' ),
			],
		];
	}

	public function get_elementor_controls(): array {
		if ( $this->props['input_mode'] === 'single-date' ) {
			return [
				'value' => [
					'label' => _x( 'Default value', 'date filter', 'voxel' ),
					'type' => \Elementor\Controls_Manager::DATE_TIME,
				],
			];
		}

		return [
			'start' => [
				'label' => _x( 'Default start date', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
				'classes' => 'ts-half-width',
			],
			'end' => [
				'label' => _x( 'Default end date', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::DATE_TIME,
				'classes' => 'ts-half-width',
			],
		];
	}

	public function get_default_value_from_elementor( $controls ) {
		if ( $this->props['input_mode'] === 'single-date' ) {
			$timestamp = strtotime( $controls['value'] ?? null );
			return $timestamp ? date( 'Y-m-d', $timestamp ) : null;
		}

		$start = strtotime( $controls['start'] ?? null );
		$end = strtotime( $controls['end'] ?? null );
		return ( $start && $end )
			? sprintf( '%s..%s', date( 'Y-m-d', $start ), date( 'Y-m-d', $end ) )
			: null;
	}
}
