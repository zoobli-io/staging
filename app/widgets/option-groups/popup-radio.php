<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Radio {

	public static function controls( $widget ) {
		
		$widget->start_controls_section(
			'auth_radio_section',
			[
				'label' => __( 'Popup: Radio', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->add_responsive_control(
				'radio_size',
				[
					'label' => __( 'Radio size', 'plugin-domain' ),
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
						'.container-radio .checkmark' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_responsive_control(
				'radio_radius',
				[
					'label' => __( 'Radio radius', 'plugin-domain' ),
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
						'.container-radio .checkmark' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'radio_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '.container-radio .checkmark',
				]
			);

			$widget->add_responsive_control(
				'unchecked_radio_bg',
				[
					'label' => __( 'Background color (unchecked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.container-radio .checkmark' => 'background-color: {{VALUE}}',
					],

				]
			);

			$widget->add_responsive_control(
				'checked_radio_bg',
				[
					'label' => __( 'Background color (checked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.container-radio input:checked ~ .checkmark' => 'background-color: {{VALUE}}',
					],

				]
			);

			$widget->add_responsive_control(
				'checked_radio_border',
				[
					'label' => __( 'Border-color (checked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.container-radio input:checked ~ .checkmark' => 'border-color: {{VALUE}}',
					],

				]
			);



		$widget->end_controls_section();
	}
}
