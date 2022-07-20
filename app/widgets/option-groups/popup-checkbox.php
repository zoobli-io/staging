<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Checkbox {

	public static function controls( $widget ) {
		
		$widget->start_controls_section(
			'auth_checkbox_section',
			[
				'label' => __( 'Popup: Checkbox', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->add_responsive_control(
				'check_size',
				[
					'label' => __( 'Checkbox size', 'plugin-domain' ),
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
						'.container-checkbox .checkmark' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_responsive_control(
				'check_radius',
				[
					'label' => __( 'Checkbox radius', 'plugin-domain' ),
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
						'.container-checkbox .checkmark' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'check_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '.container-checkbox .checkmark',
				]
			);

			$widget->add_responsive_control(
				'unchecked_bg',
				[
					'label' => __( 'Background color (unchecked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.container-checkbox .checkmark' => 'background-color: {{VALUE}}',
					],

				]
			);

			$widget->add_responsive_control(
				'checked_bg',
				[
					'label' => __( 'Background color (checked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.container-checkbox input:checked ~ .checkmark' => 'background-color: {{VALUE}}',
					],

				]
			);

			$widget->add_responsive_control(
				'checked_border',
				[
					'label' => __( 'Border-color (checked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.container-checkbox input:checked ~ .checkmark' => 'border-color: {{VALUE}}',
					],

				]
			);



		$widget->end_controls_section();
	}
}
