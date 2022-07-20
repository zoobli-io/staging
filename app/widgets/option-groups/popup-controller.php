<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Controller {

	public static function controls( $widget ) {

		$widget->start_controls_section(
			'ts_sf_popup_controls',
			[
				'label' => __( 'Popup: Control buttons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->start_controls_tabs(
				'ts_popup_control_tabs'
			);

				/* Normal tab */

				$widget->start_controls_tab(
					'ts_sfc_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$widget->add_control(
						'ts_popup_btn_general',
						[
							'label' => __( 'General', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_popup_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '.ts-popup-controller .ts-btn',
						]
					);

					$widget->add_control(
						'ts_popup_btn_padding',
						[
							'label' => __( 'Button padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								' .ts-popup-controller .ts-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_popup_btn_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
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
								'.ts-popup-controller .ts-btn' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_popup_btn_radius',
						[
							'label' => __( 'Border radius', 'plugin-domain' ),
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
								'.ts-popup-controller .ts-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_popup_controller_border',
						[
							'label' => __( 'Separator color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-popup-controller' => 'border-color: {{VALUE}}',
							],
						]
					);

					$widget->add_control(
						'ts_popup_clear',
						[
							'label' => __( 'Clear button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$widget->add_control(
						'ts_popup_button_1',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-1' => 'background: {{VALUE}}',
							],
						]
					);

					$widget->add_control(
						'ts_popup_button_1_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-1' => 'color: {{VALUE}}',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_popup_button_1_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '.ts-popup-controller .ts-btn-1',
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_popup_button_1_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '.ts-popup-controller .ts-btn-2',
						]
					);

					$widget->add_control(
						'ts_popup_submit',
						[
							'label' => __( 'Submit button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'ts_popup_button_2',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-2' => 'background: {{VALUE}}',
							],
						]
					);

					$widget->add_control(
						'ts_popup_button_2_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-2' => 'color: {{VALUE}}',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_popup_button_2_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '.ts-popup-controller .ts-btn-2',
						]
					);

					


				$widget->end_controls_tab();


				/* Hover tab */

				$widget->start_controls_tab(
					'ts_sfc_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);


					$widget->add_control(
						'ts_popup_controls_h',
						[
							'label' => __( 'Control buttons', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$widget->add_control(
						'ts_popup_button_1_h',
						[
							'label' => __( 'Clear button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-1:hover' => 'background: {{VALUE}}',
							],
						]
					);

					$widget->add_control(
						'ts_popup_button_1_c_h',
						[
							'label' => __( 'Clear button color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-1:hover' => 'color: {{VALUE}}',
							],
						]
					);

					$widget->add_control(
						'ts_popup_button_2_h',
						[
							'label' => __( 'Submit button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-2:hover' => 'background: {{VALUE}}',
							],
						]
					);

					$widget->add_control(
						'ts_popup_button_2_c_h',
						[
							'label' => __( 'Submit button color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-popup-controller .ts-btn-2:hover' => 'color: {{VALUE}}',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_popup_button_1_shadow_h',
							'label' => __( 'Submit button shadow', 'plugin-domain' ),
							'selector' => '.ts-popup-controller .ts-btn-2:hover',
						]
					);


				$widget->end_controls_tab();

			$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

}
