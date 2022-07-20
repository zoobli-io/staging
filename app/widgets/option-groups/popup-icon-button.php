<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Icon_Button {

	public static function controls( $widget ) {

		$widget->start_controls_section(
			'pg_icon_button',
			[
				'label' => __( 'Popup: Icon button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->start_controls_tabs(
				'pg_icon_button_tabs'
			);

				/* Normal tab */

				$widget->start_controls_tab(
					'pg_icon_button_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					
					$widget->add_control(
						'ib_styling',
						[
							'label' => __( 'Button styling', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_responsive_control(
						'ts_number_btn_size',
						[
							'label' => __( 'Button size', 'plugin-domain' ),
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
							'selectors' => [
								'.ts-field-popup .ts-icon-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_number_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-icon-btn i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_responsive_control(
						'ts_number_btn_icon_size',
						[
							'label' => __( 'Button icon size', 'plugin-domain' ),
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
							'selectors' => [
								'.ts-field-popup .ts-icon-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_number_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-icon-btn' => 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_number_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '.ts-field-popup .ts-icon-btn',
						]
					);

					$widget->add_responsive_control(
						'ts_number_btn_radius',
						[
							'label' => __( 'Button border radius', 'plugin-domain' ),
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
							'selectors' => [
								'.ts-field-popup .ts-icon-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ib_icons',
						[
							'label' => __( 'Button icons', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					

				$widget->end_controls_tab();


				/* Hover tab */

				$widget->start_controls_tab(
					'pg_icon_button_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$widget->add_control(
						'ts_popup_number_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-icon-btn:hover i'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$widget->add_control(
						'ts_number_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-icon-btn:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$widget->add_control(
						'ts_button_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-icon-btn:hover'
								=> 'border-color: {{VALUE}};',
							],

						]
					);

				$widget->end_controls_tab();

			$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

}
