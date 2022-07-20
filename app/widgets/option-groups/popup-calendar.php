<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Calendar {

	public static function controls( $widget ) {
		
		$widget->start_controls_section(
			'ts_sf_popup_calendar',
			[
				'label' => __( 'Popup: Calendar', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
			$widget->add_control(
				'calendar_months',
				[
					'label' => __( 'Months', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'months_typo',
					'label' => __( 'Typography' ),
					'selector' => '.ts-field-popup .pika-label',
				]
			);

			$widget->add_control(
				'months_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup .pika-label'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$widget->add_control(
				'days_of_week',
				[
					'label' => __( 'Days of the week', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_calendary_days_typo',
					'label' => __( 'Typography' ),
					'selector' => '.ts-field-popup .pika-table abbr[title]',
				]
			);

			$widget->add_control(
				'ts_calendary_days_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup .pika-table abbr[title]'
						=> 'color: {{VALUE}}',
					],

				]
			);



			$widget->add_control(
				'available_date',
				[
					'label' => __( 'Dates (available)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_calendary_number_typo',
					'label' => __( 'Typography' ),
					'selector' => '.ts-field-popup td:not(.is-disabled) .pika-button',
				]
			);

			$widget->add_control(
				'ts_calendary_number_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup td:not(.is-disabled) .pika-button'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$widget->add_control(
				'ts_calendary_number_color_h',
				[
					'label' => __( 'Color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup td:not(.is-disabled) .pika-button:hover'
						=> 'color: {{VALUE}}',
					],

				]
			);


			$widget->add_control(
				'ts_calendary_number_bg_h',
				[
					'label' => __( 'Background (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup td:not(.is-disabled) .pika-button:hover'
						=> 'background-color: {{VALUE}}',
					],

				]
			);



			$widget->add_control(
				'days_range_h',
				[
					'label' => __( 'Dates (Range)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);


			$widget->add_control(
				'range_nm_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup .is-inrange:not(.is-disabled) .pika-button'
						=> 'color: {{VALUE}} !important;',
					],

				]
			);

			$widget->add_control(
				'range_nm_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup  .is-inrange:not(.is-disabled) .pika-button'
						=> 'background-color: {{VALUE}} !important;',
					],

				]
			);

			$widget->add_control(
				'days_sides_h',
				[
					'label' => __( 'Dates (Range start and end)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);


			$widget->add_control(
				'sides_nm_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup .ts-booking-date .is-startrange .pika-button, .ts-booking-date .is-endrange .pika-button'
						=> 'color: {{VALUE}} !important;',
					],

				]
			);

			$widget->add_control(
				'sides_nm_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup  .ts-booking-date .is-startrange .pika-button,.ts-booking-date .is-endrange .pika-button'
						=> 'background-color: {{VALUE}} !important;',
					],

				]
			);

			$widget->add_control(
				'selected_date',
				[
					'label' => __( 'Dates (Selected - Single date)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_control(
				'slected_nm_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup .pika-single:not(.pika-range) .is-selected .pika-button'
						=> 'color: {{VALUE}} !important;',
					],

				]
			);

			$widget->add_control(
				'selected_nm_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup .pika-single:not(.pika-range) .is-selected .pika-button'
						=> 'background-color: {{VALUE}} !important;',
					],

				]
			);

			$widget->add_control(
				'unavailable_date',
				[
					'label' => __( 'Dates (unavailable)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'unvailable_date_t',
					'label' => __( 'Typography' ),
					'selector' => ' .ts-field-popup td.is-disabled .pika-button',
				]
			);

			$widget->add_control(
				'unvailable_date_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup td.is-disabled .pika-button'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$widget->add_control(
				'days_settings',
				[
					'label' => __( 'Other settings', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_responsive_control(
				'days_border_radius',
				[
					'label' => __( 'Border radius', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'.ts-field-popup .pika-button' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);


		$widget->end_controls_section();
	}
}
