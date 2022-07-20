<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class File_Field {

	public static function controls( $widget ) {
		$widget->start_controls_section(
			'ts_form_file',
			[
				'label' => __( 'Form: File/Gallery', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			

			$widget->start_controls_tabs(
				'file_field_tabs'
			);

				/* Normal tab */

				$widget->start_controls_tab(
					'file_field_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$widget->add_responsive_control(
						'ts_file_col_no',
						[
							'label' => __( 'Number of columns', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::NUMBER,
							'min' => 1,
							'max' => 6,
							'step' => 1,
							'default' => 3,
							'selectors' => [
								'.ts-file-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_file_col_gap',
						[
							'label' => __( 'Item gap', 'plugin-domain' ),
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
								'.ts-file-list' => 'grid-gap: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_file_col_height',
						[
							'label' => __( 'Item height', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 50,
									'max' => 500,
									'step' => 1,
								],
							],
							'selectors' => [
								'.ts-file, .pick-file-input' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);



					$widget->add_control(
						'ts_file_add',
						[
							'label' => __( 'Select files', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'ts_file_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.pick-file-input a i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_responsive_control(
						'ts_file_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
								'.pick-file-input a i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_file_bg',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.pick-file-input'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_file_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '.pick-file-input',
						]
					);

					$widget->add_responsive_control(
						'ts_file_radius',
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
								'.pick-file-input' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_file_text',
							'label' => __( 'Typography' ),
							'selector' => '.pick-file-input a',
						]
					);

					$widget->add_control(
						'ts_file_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.pick-file-input a'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_file_added',
						[
							'label' => __( 'Added file/image', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_responsive_control(
						'ts_added_radius',
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
								'.ts-file' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_added_bg',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-file'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_added_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-file-info i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_responsive_control(
						'ts_added_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
								'.ts-file-info i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_added_text',
							'label' => __( 'Typography' ),
							'selector' => '.ts-file-info code',
						]
					);

					$widget->add_control(
						'ts_added_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-file-info code'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_remove_file',
						[
							'label' => __( 'Remove/Check button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'ts_rmf_bg',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-remove-file'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_rmf_bg_h',
						[
							'label' => __( 'Background (Hover)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-remove-file:hover'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_rmf_color',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-remove-file'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_rmf_color_h',
						[
							'label' => __( 'Color (Hover)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-remove-file:hover'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_responsive_control(
						'ts_rmf_radius',
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
								'.ts-remove-file' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_rmf_size',
						[
							'label' => __( 'Size', 'plugin-domain' ),
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
								'.ts-remove-file' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_responsive_control(
						'ts_rmf_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
								'.ts-remove-file' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					



				$widget->end_controls_tab();


				/* Hover tab */

				$widget->start_controls_tab(
					'ts_file_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$widget->add_control(
						'ts_file_add_h',
						[
							'label' => __( 'Select files', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'ts_file_icon_color_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.pick-file-input a:hover i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_file_bg_h',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.pick-file-input:hover'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_file_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.pick-file-input:hover'
								=> 'border-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_file_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.pick-file-input a:hover'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$widget->end_controls_tab();

			$widget->end_controls_tabs();



		$widget->end_controls_section();
		
	}
}
