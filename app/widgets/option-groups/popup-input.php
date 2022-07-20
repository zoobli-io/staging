<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Input {

	public static function controls( $widget ) {
		$widget->start_controls_section(
			'ts_sf_popup_input',
			[
				'label' => __( 'Popup: Input styling', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->start_controls_tabs(
				'ts_popup_input_tabs'
			);

				/* Normal tab */

				$widget->start_controls_tab(
					'ts_sfi_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$widget->add_control(
						'ts_popup_input',
						[
							'label' => __( 'Input', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'ts_sf_popup_input_height',
						[
							'label' => __( 'Input height', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 100,
									'step' => 1,
								],
								'%' => [
									'min' => 0,
									'max' => 100,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 45,
							],
							'selectors' => [
								'.ts-field-popup input' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_sf_popup_input_radius',
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
								'.ts-field-popup input' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_popup_input_font',
							'label' => __( 'Typography' ),
							'selector' => '.ts-field-popup input',
						]
					);

					$widget->add_control(
						'ts_input_padding',
						[
							'label' => __( 'Input padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'.ts-field-popup input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_popup_input_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '.ts-field-popup input',
						]
					);

					$widget->add_control(
						'ts_sf_popup_input_bg',
						[
							'label' => __( 'Input background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup input' => 'background: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_sf_popup_input_background_filled',
						[
							'label' => __( 'Input background color (Focus)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup input:focus' => 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_sf_popup_input_value_col',
						[
							'label' => __( 'Input value color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup input' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_sf_popup_input_placeholder_color',
						[
							'label' => __( 'Input placeholder color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup input::-webkit-input-placeholder' => 'color: {{VALUE}}',
								'.ts-field-popup input:-moz-placeholder' => 'color: {{VALUE}}',
								'.ts-field-popup input::-moz-placeholder' => 'color: {{VALUE}}',
								'.ts-field-popup input:-ms-input-placeholder' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_sf_input_popup_icon',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-input-icon > i' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_popup_input_icon_size',
						[
							'label' => __( 'Input icon size', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 40,
									'step' => 1,
								],
								'%' => [
									'min' => 0,
									'max' => 100,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 22,
							],
							'selectors' => [
								'.ts-field-popup .ts-input-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_popup_input_icon_size_m',
						[
							'label' => __( 'Input icon left margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 40,
									'step' => 1,
								],
								'%' => [
									'min' => 0,
									'max' => 100,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 15,
							],
							'selectors' => [
								'.ts-field-popup .ts-input-icon > i' => 'left: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$widget->end_controls_tab();


				/* Hover tab */

				$widget->start_controls_tab(
					'ts_sfi_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);


					$widget->add_control(
						'ts_popup_input_h',
						[
							'label' => __( 'Input', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'ts_sf_popup_input_bg_h',
						[
							'label' => __( 'Input background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup input:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_popup_input_h_border',
						[
							'label' => __( 'Input border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup input:hover' => 'border-color: {{VALUE}}',
							],

						]
					);


				$widget->end_controls_tab();

			$widget->end_controls_tabs();

		$widget->end_controls_section();

	}

}
