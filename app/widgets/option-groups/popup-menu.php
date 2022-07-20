<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Menu {

	public static function controls( $widget ) {
		
		$widget->start_controls_section(
			'ts_sf_popup_list',
			[
				'label' => __( 'Popup: Menu styling', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->start_controls_tabs(
				'ts_popup_list_tabs'
			);

				/* Normal tab */

				$widget->start_controls_tab(
					'ts_sfl_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					$widget->add_control(
						'ts_popup_term_list_item',
						[
							'label' => __( 'List item', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$widget->add_control(
						'ts_popup_term_padding',
						[
							'label' => __( 'Item padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'.ts-term-dropdown li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_popup_term_margin',
						[
							'label' => __( 'Item margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'.ts-term-dropdown li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_term_max_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
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
								'.ts-term-dropdown li > a' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_popup_single_term_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '.ts-term-dropdown li > a',
						]
					);

					$widget->add_responsive_control(
						'ts_popup_single_term_radius',
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
								'.ts-term-dropdown li > a' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$widget->add_control(
						'ts_popup_term_icon',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li > a i'
								=> 'color: {{VALUE}};',
								'.ts-term-dropdown li > a svg'
								=> 'fill: {{VALUE}};',
							],

						]
					);

					$widget->add_responsive_control(
						'ts_popup_term_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
							'selectors' => [
								'.ts-term-dropdown li > a i' => 'font-size: {{SIZE}}{{UNIT}};',
								'.ts-term-dropdown li > a svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_icon_right_margin',
						[
							'label' => __( 'Right margin', 'plugin-domain' ),
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
								'.ts-term-dropdown li > a  i,.ts-term-dropdown li > a  svg,.term-dropdown-back span ' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_popup_term_title',
						[
							'label' => __( 'Title color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li > a p'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_popup_term_title_typo',
							'label' => __( 'Title typography', 'plugin-domain' ),
							'selector' => '.ts-term-dropdown li > a p',
						]
					);



					

					$widget->add_control(
						'ts_popup_chevron',
						[
							'label' => __( 'Chevron', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$widget->add_control(
						'ts_popup_term_arrow',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li > a span'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_responsive_control(
						'ts_popup_arrow_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
							'selectors' => [
								'.ts-term-dropdown li > a span' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);



					$widget->add_control(
						'ts_go_back',
						[
							'label' => __( 'Go back', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$widget->add_control(
						'ts_back_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.term-dropdown-back span'
								=> 'color: {{VALUE}}',
							],

						]
					);


					$widget->add_responsive_control(
						'ts_back_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
							'selectors' => [
								'.ts-term-dropdown li.term-dropdown-back span' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_back_icon_text_typo',
							'label' => __( 'Title typography', 'plugin-domain' ),
							'selector' => '.term-dropdown-back > a > p',
						]
					);

					$widget->add_control(
						'ts_back_icon_text',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.term-dropdown-back > a > p'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_popup_term_back',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.term-dropdown-back a'
								=> 'background-color: {{VALUE}}',
							],

						]
					);


				$widget->end_controls_tab();


				/* Hover tab */

				$widget->start_controls_tab(
					'ts_sfl_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$widget->add_control(
						'ts_term_item_hover',
						[
							'label' => __( 'Term item', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);
					$widget->add_control(
						'ts_popup_term_bg_h',
						[
							'label' => __( 'List item background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li > a:hover'
								=> 'background: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_popup_term_title_hover',
						[
							'label' => __( 'Title color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li > a:hover p'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_popup_term_icon_hover',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li > a:hover > i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_popup_term_arrow_hover',
						[
							'label' => __( 'Arrow icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li > a:hover span'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_go_back_hover',
						[
							'label' => __( 'Go back', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'ts_back_icon_color_hover',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.term-dropdown-back a:hover > span'
								=> 'color: {{VALUE}}',
							],

						]
					);


					$widget->add_control(
						'ts_back_icon_text_hover',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li.term-dropdown-back > a:hover > p'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_popup_term_back_hover',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-term-dropdown li.term-dropdown-back a:hover'
								=> 'background-color: {{VALUE}}',
							],

						]
					);


				$widget->end_controls_tab();

			$widget->end_controls_tabs();

		$widget->end_controls_section();


	}

}
