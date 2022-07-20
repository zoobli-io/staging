<?php

namespace Voxel\Post_Types\Filters\Traits;

if ( ! defined('ABSPATH') ) {
	exit;
}

trait Date_Filter_Helpers {

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
				'from' => _x( 'From', 'date filter', 'voxel' ),
				'to' => _x( 'To', 'date filter', 'voxel' ),
				'pickDate' => _x( 'Choose date', 'date filter', 'voxel' ),
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
