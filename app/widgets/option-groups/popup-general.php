<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_General {

	public static function controls( $widget ) {
		$widget->start_controls_section(
			'popup_general_section',
			[
				'label' => __( 'Popup: General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


			$widget->add_control(
				'pg_general',
				[
					'label' => __( 'General', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_control(
				'pg_width',
				[
					'label' => __( 'Min width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'description' => __( 'Does not affect mobile', 'plugin-domain' ),
					'size_units' => [ 'px', '%'],
					'range' => [
						'px' => [
							'min' => 200,
							'max' => 800,
							'step' => 1,
						],
					],

					'selectors' => [
						'.ts-field-popup' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_control(
				'pg_max_width',
				[
					'label' => __( 'Max width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'description' => __( 'Does not affect mobile', 'plugin-domain' ),
					'size_units' => [ 'px', '%'],
					'range' => [
						'px' => [
							'min' => 200,
							'max' => 800,
							'step' => 1,
						],
					],

					'selectors' => [
						'.ts-field-popup' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);





			

			$widget->add_control(
				'pg_background',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup' => 'background-color: {{VALUE}}',
					],
				]
			);

			$widget->add_control(
				'pg_backdrop',
				[
					'label' => __( 'Backdrop background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-field-popup-container:after' => 'background-color: {{VALUE}} !important',
					],
				]
			);


			$widget->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'pg_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '.ts-field-popup',
				]
			);


			$widget->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'pg_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '.ts-field-popup',
				]
			);

			$widget->add_responsive_control(
				'pg_radius',
				[
					'label' => __( 'Border radius', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'.ts-field-popup' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_control(
				'pg_scroll-color',
				[
					'label' => __( 'Scroll background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.min-scroll' => '--ts-scroll-color: {{VALUE}}',
					],
				]
			);


			$widget->add_control(
				'pg_notf_container',
				[
					'label' => __( 'Popup content', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_responsive_control(
				'pg_spacing_value',
				[
					'label' => __( 'Spacing', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'.ts-field-popup .ts-form-group' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}; padding-top: {{SIZE}}{{UNIT}};',
						'.ts-popup-controller' => 'padding: {{SIZE}}{{UNIT}};',
						'.ts-popup-content-wrapper .ts-form-group:last-child' => 'padding-bottom: {{SIZE}}{{UNIT}};',
						'.ts-view-conversation > div' => 'padding: {{SIZE}}{{UNIT}};',
					],

				]
			);

			$widget->add_control(
				'pg_height',
				[
					'label' => __( 'Max height', 'plugin-domain' ),
					'description' => __( 'Does not affect mobile', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%'],
					'range' => [
						'px' => [
							'min' => 250,
							'max' => 800,
							'step' => 1,
						],
					],
					'selectors' => [
						'.ts-list-container,  .ts-term-dropdown-list, .ts-conversation-body' => 'max-height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			

			


		$widget->end_controls_section();

	}
}
