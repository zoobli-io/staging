<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Create_Post extends Base_Widget {

	public function get_name() {
		return 'ts-create-post';
	}

	public function get_title() {
		return __( 'Create post (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'ts_sf_post_types',
			[
				'label' => __( 'Post type', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$post_types = [];
			foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
				$post_types[ $post_type->get_key() ] = $post_type->get_label();
			}

			$this->add_control( 'ts_post_type', [
				'label' => __( 'Post type', 'voxel' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $post_types,
			] );

			$this->add_responsive_control(
				'cpt_filter_width',
				[
					'label' => __( 'Min width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'condition' => [ 'cpt_filter_cols' => 'elementor-col-auto' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} #cpt_filter ' => 'min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);


		$this->end_controls_section();

		

		$this->start_controls_section(
			'ts_sf_styling_general',
			[
				'label' => __( 'Form: General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_responsive_control(
					'ts_sf_general_padding',
					[
						'label' => __( 'Form padding', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ts-create-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_sf_general_bg',
					[
						'label' => __( 'Background color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-create-post' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'ts_sf_general_border',
						'label' => __( 'Button border', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-create-post',
					]
				);

				$this->add_responsive_control(
					'ts_sf_general_radius',
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
							'{{WRAPPER}} .ts-create-post' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);


				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'ts_sf_general_shadow',
						'label' => __( 'Box Shadow', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-create-post',
					]
				);

				$this->add_responsive_control(
					'ts_cp_max_width',
					[
						'label' => __( 'Max width', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1200,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-create-post' => 'max-width: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_cp_min_height',
					[
						'label' => __( 'Min height', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%', 'vh' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1200,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-create-post' => 'min-height: {{SIZE}}{{UNIT}};',
						],
					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_form_head',
			[
				'label' => __( 'Form: Head', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
				
				$this->add_control(
					'ts_head_hide',
					[
						'label' => __( 'Hide', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => __( 'Hide', 'your-plugin' ),
						'label_off' => __( 'Show', 'your-plugin' ),
						'return_value' => 'none',
						'default' => 'Show',
						'selectors' => [
							'{{WRAPPER}} .ts-form-progres' => 'display: {{VALUE}}',
						],
						
					]
				);

				$this->add_responsive_control(
					'ts_head_spacing',
					[
						'label' => __( 'Bottom spacing', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-form-progres' => 'padding-bottom: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'ts_steps_bar',
					[
						'label' => __( 'Form steps bar', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_steps_bar_hide',
					[
						'label' => __( 'Hide', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label_on' => __( 'Hide', 'your-plugin' ),
						'label_off' => __( 'Show', 'your-plugin' ),
						'return_value' => 'none',
						'default' => 'Show',
						'selectors' => [
							'{{WRAPPER}} .step-percentage' => 'display: {{VALUE}}',
						],
						
					]
				);

				$this->add_responsive_control(
					'ts_steps_bar_height',
					[
						'label' => __( 'Height', 'plugin-domain' ),
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
							'{{WRAPPER}} ul.step-percentage' => 'height: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_steps_bar_radius',
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
							'{{WRAPPER}} ul.step-percentage' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_percentage_spacing',
					[
						'label' => __( 'Bottom spacing', 'plugin-domain' ),
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
							'{{WRAPPER}} .step-percentage' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'ts_steps_bar_bg',
					[
						'label' => __( 'Step background', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} ul.step-percentage li'
							=> 'background-color: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'ts_steps_bar_done',
					[
						'label' => __( 'Step background (Filled)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} ul.step-percentage li.step-done'
							=> 'background-color: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'ts_current_step',
					[
						'label' => __( 'Step heading', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_current_step_text',
						'label' => __( 'Typography' ),
						'selector' => '{{WRAPPER}} .active-step-details p',
					]
				);


				$this->add_responsive_control(
					'ts_current_step_col',
					[
						'label' => __( 'Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .active-step-details p' => 'color: {{VALUE}}',
						],

					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_form_nav',
			[
				'label' => __( 'Head: Next/Prev buttons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_fnav_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_fnav_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					$this->add_control(
						'ts_fnav_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .step-nav .ts-icon-btn'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_fnav_btn_icon_size',
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
								'{{WRAPPER}} .step-nav .ts-icon-btn' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_fnav_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .step-nav .ts-icon-btn'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_fnav_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .step-nav .ts-icon-btn',
						]
					);

					$this->add_responsive_control(
						'ts_fnav_btn_radius',
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
								'{{WRAPPER}} .step-nav  .ts-icon-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_fnav_btn_size',
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
								'{{WRAPPER}} .step-nav .ts-icon-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'submit_nav_icons',
						[
							'label' => __( 'Icons', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'next_icon',
						[
							'label' => __( 'Icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-angle-right',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'prev_icon',
						[
							'label' => __( 'Icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-angle-left',
								'library' => 'la-solid',
							],
						]
					);

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_fnav_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_fnav_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .step-nav .ts-icon-btn:hover'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_fnav_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .step-nav .ts-icon-btn:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_fnav_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .step-nav .ts-icon-btn:hover'
								=> 'border-color: {{VALUE}};',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_form_footer',
			[
				'label' => __( 'Form: Footer', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


			$this->add_responsive_control(
				'footer_top_spacing',
				[
					'label' => __( 'Top spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-form-footer' => 'padding-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_styling_buttons',
			[
				'label' => __( 'Primary button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_sf_buttons_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_sf_buttons_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_submit_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-2.create-btn',
						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-btn-2.create-btn' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_form_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-2.create-btn',
						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_radius',
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
								'{{WRAPPER}} .ts-btn-2.create-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'ts_sf_form_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_sf_form_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn' => 'background: {{VALUE}}',
							],

						]
					);



					$this->add_responsive_control(
						'ts_sf_form_btn_icon_size',
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
								'{{WRAPPER}} .ts-btn-2.create-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_icon_margin',
						[
							'label' => __( 'Icon right padding', 'plugin-domain' ),
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
								'size' => 10,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'publish_icon',
						[
							'label' => __( 'Publish icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-arrow-right',
								'library' => 'la-solid',
							],
						]
					);

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_sf_buttons_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_sf_form_btn_t_hover',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_bg_hover',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn:hover' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_bo_hover',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-2.create-btn:hover i' => 'color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_ffooter_btn',
			[
				'label' => __( 'Footer: Next/Previous step', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_ffooter_btn_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_ffooter_btn_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					
					$this->add_control(
						'ts_ffooter_icon',
						[
							'label' => __( 'Icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_responsive_control(
						'ffooter_icon_con_size',
						[
							'label' => __( 'Container size', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-nextprev a i' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ffooter_icon_size',
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
								'{{WRAPPER}} .ts-nextprev a i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ffooter_icon_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-nextprev a i',
						]
					);

					$this->add_responsive_control(
						'ffooter_icon_radius',
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
								'{{WRAPPER}} .ts-nextprev a i' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_ffooter_text',
						[
							'label' => __( 'Text', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_ffooter_text_typo',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-nextprev a span',
						]
					);

					$this->add_responsive_control(
						'ffooter_text_margin',
						[
							'label' => __( 'Side margin', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-nextprev a span' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);




					$this->add_control(
						'ts_ffooter_colors',
						[
							'label' => __( 'Colors (Default)', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_responsive_control(
						'ts_ffooter_text_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev a span' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_con_c',
						[
							'label' => __( 'Icon container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev a i' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_c',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev a i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_ffooter_fw_colors',
						[
							'label' => __( 'Colors (Next)', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_responsive_control(
						'ts_ffooter_text_fw_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev .ts-next span' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_con_fw_c',
						[
							'label' => __( 'Icon container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev .ts-next i' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_fw_c',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev .ts-next i' => 'color: {{VALUE}}',
							],

						]
					);


					

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_ffooter_btn_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_ffooter_colors_h',
						[
							'label' => __( 'Colors (Default)', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_responsive_control(
						'ts_ffooter_text_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev a:hover span' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_con_c_h',
						[
							'label' => __( 'Icon container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev a:hover i' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_c_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev a:hover i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_ffooter_fw_colors_h',
						[
							'label' => __( 'Colors (Next)', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_responsive_control(
						'ts_ffooter_text_fw_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev .ts-next:hover span' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_con_fw_c_h',
						[
							'label' => __( 'Icon container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev .ts-next:hover i' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_ffooter_icon_c_fw_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nextprev .ts-next:hover i' => 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf1_fields_general',
			[
				'label' => __( 'Form: Fields general', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


			$this->add_control(
				'ts_sf1_input',
				[
					'label' => __( 'Field', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'ts_sf1_form_group_padding',
				[
					'label' => __( 'Margin', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .create-form-step > .ts-form-group,{{WRAPPER}} .ts-product-field > *' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_sf1_input_lbl',
				[
					'label' => __( 'Field label', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);


			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_sf1_input_label_text',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-form-group label',
				]
			);


			$this->add_responsive_control(
				'ts_sf1_input_label_col',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-form-group label' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'ts_intxt_double',
				[
					'label' => __( 'Double field spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-double-input > *:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ts-double-input.has-controller > *' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'sf1_input_label_padding',
				[
					'label' => __( 'Label padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'selectors' => [
						'{{WRAPPER}}  .ts-form-group label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts1_field_desc_h',
				[
					'label' => __( 'Field description', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);


			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts1_field_desc_t',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-form-group small',
				]
			);


			$this->add_responsive_control(
				'ts1_field_desc_col',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-form-group  small' => 'color: {{VALUE}}',
					],

				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_intxt',
			[
				'label' => __( 'Form: Input & Textarea', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_intxt_tabs'
			);
				/* Normal tab */

				$this->start_controls_tab(
					'ts_intxt_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_intxt_placeholde_heading',
						[
							'label' => __( 'Placeholder', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_intxt_placeholder',
						[
							'label' => __( 'Placeholder color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input.ts-filter::placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .ts-form textarea.ts-filter::placeholder' => 'color: {{VALUE}}',

							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_intxt_input_input_typo',
							'label' => __( 'Typography' ),
							'selector' =>
								'{{WRAPPER}} .ts-form input.ts-filter::placeholder, .ts-form textarea.ts-filter::placeholder',
						]
					);

					$this->add_control(
						'ts_intxt_text',
						[
							'label' => __( 'Value', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					

					$this->add_responsive_control(
						'ts_intxt_value_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input.ts-filter' => 'color: {{VALUE}};',
								'{{WRAPPER}} .ts-form textarea.ts-filter' => 'color: {{VALUE}};',
							],

						]
					);



					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_intxt_value_typo',
							'label' => __( 'Typography' ),

							'selector' => '{{WRAPPER}} .ts-form input.ts-filter, {{WRAPPER}} .ts-form textarea.ts-filter',
							

						]
					);


					$this->add_control(
						'ts_intxt_general',
						[
							'label' => __( 'General', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_intxt_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form textarea.ts-filter' => 'background: {{VALUE}}',
								'{{WRAPPER}} .ts-form input.ts-filter' => 'background: {{VALUE}}',
							],

						]
					);

					


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_intxt_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form textarea.ts-filter, {{WRAPPER}} .ts-form input.ts-filter',
							
							
						]
					);

					$this->add_control(
						'ts_intxt_input_heading',
						[
							'label' => __( 'Input', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_intxt_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-form input.ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_intxt_input_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
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
								'{{WRAPPER}}  .ts-form input.ts-filter' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_intxt_input_radius',
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
								'{{WRAPPER}} .ts-form input.ts-filter' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					



					$this->add_control(
						'ts_intxt_textarea_heading',
						[
							'label' => __( 'Textarea', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_txt_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-form textarea.ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_intxt_textarea_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 1500,
									'step' => 1,
								],
								'%' => [
									'min' => 0,
									'max' => 100,
								],
							],
							'selectors' => [
								'{{WRAPPER}}  .ts-form textarea.ts-filter' => 'min-height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_intxt_textarea_radius',
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
								'{{WRAPPER}} .ts-form textarea.ts-filter' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_input2_icon_heading',
						[
							'label' => __( 'Input with icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_input2_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-input-icon input.ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);



					$this->add_responsive_control(
						'ts_input2_icon_col',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-input-icon i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_intxt_icon_size',
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
								'{{WRAPPER}} .ts-input-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_intxt_icon_margin',
						[
							'label' => __( 'Icon left padding', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-input-icon i' => 'left: {{SIZE}}{{UNIT}};',
							],
						]
					);

					

					



				$this->end_controls_tab();

				/* Hover */

				$this->start_controls_tab(
					'ts_intxt_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_intxt_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form textarea.ts-filter:hover' => 'background: {{VALUE}}',
								'{{WRAPPER}} .ts-form input.ts-filter:hover' => 'background: {{VALUE}}',
								'{{WRAPPER}} .ts-form .mce-content-body:hover' => 'background: {{VALUE}};',
							],

						]
					);

					$this->add_responsive_control(
						'ts_intxt_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form textarea.ts-filter:hover' => 'border-color: {{VALUE}}',
								'{{WRAPPER}} .ts-form input.ts-filter:hover' => 'border-color: {{VALUE}}',
								'{{WRAPPER}} .ts-form .mce-content-body:hover' => 'border-color: {{VALUE}};',
							],

						]
					);

					$this->add_responsive_control(
						'ts_intxt_placeholder_h',
						[
							'label' => __( 'Placeholder color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input.ts-filter:hover::placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .ts-form textarea.ts-filter:hover::placeholder' => 'color: {{VALUE}}',

							],

						]
					
					);
						
					$this->add_responsive_control(
						'ts_intxt_value_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input.ts-filter:hover' => 'color: {{VALUE}};',
								'{{WRAPPER}} .ts-form textarea.ts-filter:hover' => 'color: {{VALUE}};',
							],

						]
					);

					$this->add_responsive_control(
						'ts_input2_icon_col_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-input-icon:hover i' => 'color: {{VALUE}}',
							],

						]
					);



				$this->end_controls_tab();

				/* Filled */

				$this->start_controls_tab(
					'ts_intxt_filled',
					[
						'label' => __( 'Active', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_intxt_bg_a',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form textarea.ts-filter:focus' => 'background: {{VALUE}}',
								'{{WRAPPER}} .ts-form input.ts-filter:focus' => 'background: {{VALUE}}',
								'{{WRAPPER}} .ts-form .mce-content-body:focus' => 'background: {{VALUE}};',
							],

						]
					);

					$this->add_responsive_control(
						'ts_intxt_border_a',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form textarea.ts-filter:focus' => 'border-color: {{VALUE}}',
								'{{WRAPPER}} .ts-form input.ts-filter:focus' => 'border-color: {{VALUE}}',
								'{{WRAPPER}} .ts-form .mce-content-body:focus' => 'border-color: {{VALUE}};',
							],

						]
					);

					$this->add_responsive_control(
						'ts_intxt_placeholder_a',
						[
							'label' => __( 'Placeholder color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input.ts-filter:active::placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .ts-form textarea.ts-filter:active::placeholder' => 'color: {{VALUE}}',

							],

						]
					
					);

					$this->add_responsive_control(
						'ts_intxt_value_color_a',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input.ts-filter:focus' => 'color: {{VALUE}};',
								'{{WRAPPER}} .ts-form textarea.ts-filter:focus' => 'color: {{VALUE}};',
							],

						]
					);

				

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_input_suffix',
			[
				'label' => __( 'Form: Input suffix', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_suffix_typo',
					'label' => __( 'Button typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .input-container .input-suffix',
				]
			);

			$this->add_responsive_control(
				'ts_suffix_text',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .input-container .input-suffix' => 'color: {{VALUE}}',
					],

				]
			);


			$this->add_responsive_control(
				'ts_suffix_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .input-container .input-suffix' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'ts_suffix_radius',
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
						'{{WRAPPER}} .input-container .input-suffix' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'ts_suffix_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .input-container .input-suffix',
				]
			);

			$this->add_responsive_control(
				'ts_suffix_margin',
				[
					'label' => __( 'Right margin', 'plugin-domain' ),
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
						'{{WRAPPER}} .input-container .input-suffix' => 'right: {{SIZE}}{{UNIT}};',
					],
				]
			);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sfeditor',
			[
				'label' => __( 'Form: Text Editor', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_control(
				'ts_sfeditor_btns',
				[
					'label' => __( 'Editor buttons', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'ts_sfeditor_btn_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mce-btn i' => 'color: {{VALUE}} !important;',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_btn_bg',
				[
					'label' => __( 'Icon background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mce-btn' => 'background-color: {{VALUE}}!important;',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_btn_border',
				[
					'label' => __( 'Icon border (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mce-btn' => 'border-color: {{VALUE}}!important;',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_btn_bg_hover',
				[
					'label' => __( 'Icon background (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mce-btn:hover' => 'background-color: {{VALUE}}!important;',
					],

				]
			);


			$this->add_control(
				'ts_sfeditor_btn_bg_active',
				[
					'label' => __( 'Icon background (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mce-btn.mce-active' => 'background-color: {{VALUE}}!important;',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_btn_bg_active_border',
				[
					'label' => __( 'Icon border (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .mce-btn.mce-active' => 'border-color: {{VALUE}}!important;',
					],

				]
			);



			$this->add_control(
				'ts_sfeditor_btn_radius',
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
						'{{WRAPPER}} .mce-btn' => 'border-radius: {{SIZE}}{{UNIT}}!important;',
					],
				]
			);

			$this->add_control(
				'ts_sfeditor_textarea',
				[
					'label' => __( 'Textarea', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);



			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_sfeditor_text',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-form-group .mce-content-body',
				]
			);

			$this->add_control(
				'ts_sfeditor_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-form-group .mce-content-body' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_text_link',
				[
					'label' => __( 'Link color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-form-group .editor-container a' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_text_highlight',
				[
					'label' => __( 'Highlight background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-form-group .mce-content-body *[data-mce-selected="inline-boundary"]' => 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-form-group .mce-content-body' => 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ts-form-group .mce-content-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_sfeditor_border',
					'label' => __( 'Filter border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-form-group .mce-content-body',
				]
			);

			$this->add_control(
				'ts_sfeditor_radius',
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
						'{{WRAPPER}} .ts-form-group .mce-content-body' => 'border-radius: {{SIZE}}{{UNIT}}!important;',
					],
				]
			);

			$this->add_control(
				'ts_sfeditor_dialog',
				[
					'label' => __( 'Dialog box', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'ts_sfeditor_dialog_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'div.mce-inline-toolbar-grp' => 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_dialog_text',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'div.mce-inline-toolbar-grp input::placeholder' => 'color: {{VALUE}}',
						'div.mce-inline-toolbar-grp input' => 'color: {{VALUE}}',
						'div.mce-inline-toolbar-grp .wp-link-preview a' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_sfeditor_dialog_border',
				[
					'label' => __( 'Border color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'div.mce-inline-toolbar-grp' => 'border-color: {{VALUE}} !important;',
					],

				]
			);



		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_styling_filters',
			[
				'label' => __( 'Form: Popup trigger', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_sf_filters_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_sf_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					$this->add_responsive_control(
						'ts_sf_input_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-form div.ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_height',
						[
							'label' => __( 'height', 'plugin-domain' ),
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
								'size' => 50,
							],
							'selectors' => [
								'{{WRAPPER}} div.ts-filter' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} div.ts-filter',
						]
					);




					$this->add_responsive_control(
						'ts_sf_input_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form div.ts-filter' => 'background: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_sf_input_value_col',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form div.ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_input_input_typo',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-form div.ts-filter',
						]
					);



					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_input_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} div.ts-filter',
						]
					);




					$this->add_responsive_control(
						'ts_sf_input_radius',
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
								'{{WRAPPER}} .ts-form div.ts-filter' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_col',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} div.ts-filter i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_size',
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
							'default' => [
								'unit' => 'px',
								'size' => 24,
							],
							'selectors' => [
								'{{WRAPPER}} div.ts-filter i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_margin',
						[
							'label' => __( 'Icon right padding', 'plugin-domain' ),
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
								'size' => 10,
							],
							'selectors' => [
								'{{WRAPPER}} div.ts-filter i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);


				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_sf_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);


					$this->add_control(
						'ts_sf_input_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form div.ts-filter:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form div.ts-filter:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow_hover',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} div.ts-filter:hover',
						]
					);

				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'ts_sf_filled',
					[
						'label' => __( 'Filled', 'plugin-name' ),
					]
				);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_input_typo_filled',
							'label' => __( 'Typography', 'plugin-domain' ),
							'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
							'selector' => '{{WRAPPER}} div.ts-filter.ts-filled',
						]
					);

					$this->add_control(
						'ts_sf_input_background_filled',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form div.ts-filter.ts-filled' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_value_col_filled',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} div.ts-filter.ts-filled .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_col_filled',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} div.ts-filter.ts-filled i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_border_filled',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form div.ts-filter.ts-filled' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_border_filled_width',
						[
							'label' => __( 'Border width', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-form div.ts-filter.ts-filled' => 'border-width: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_field_switch',
			[
				'label' => __( 'Form: Switcher', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

				$this->add_control(
					'ts_field_switch',
					[
						'label' => __( 'Switch slider', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_field_switch_bg',
					[
						'label' => __( 'Background (Inactive)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .onoffswitch .onoffswitch-label'
							=> 'background-color: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'ts_field_switch_bg_active',
					[
						'label' => __( 'Background (Active)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .onoffswitch .onoffswitch-checkbox:checked + .onoffswitch-label'
							=> 'background-color: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'ts_field_switch_bg_handle',
					[
						'label' => __( 'Handle background', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .onoffswitch .onoffswitch-label:before'
							=> 'background-color: {{VALUE}}',
						],

					]
				);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_stepper',
			[
				'label' => __( 'Form: Number stepper', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_stepper_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_stepper_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);
					$this->add_control(
						'popup_number_input_size',
						[
							'label' => __( 'Input value size', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 13,
									'max' => 30,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 20,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input input' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_stepper_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input button'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_stepper_btn_icon_size',
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
								'{{WRAPPER}} .ts-stepper-input button' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_stepper_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input button'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_stepper_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-stepper-input button',
						]
					);

					$this->add_responsive_control(
						'ts_stepper_btn_radius',
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
								'{{WRAPPER}} .ts-stepper-input button' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_stepper_btn_size',
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
								'{{WRAPPER}} .ts-stepper-input button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_stepper_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_stepper_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input button:hover'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_stepper_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input button:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_stepper_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input button:hover'
								=> 'border-color: {{VALUE}};',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();
				


		$this->end_controls_section();


		$this->start_controls_section(
			'ts_repeater',
			[
				'label' => __( 'Form: Repeater', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'ts_repeater_spacing',
				[
					'label' => __( 'Content spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-field-repeater .ts-form-group' => 'padding: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_fh_btn_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-field-repeater'
						=> 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_repeater_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-field-repeater',
				]
			);

			$this->add_responsive_control(
				'ts_repeater_radius',
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
						'{{WRAPPER}} .ts-field-repeater' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_repeater_head',
			[
				'label' => __( 'Form: Repeater head', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'rhead_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ts-repeater-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'rhead_typo',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-repeater-head label',
				]
			);

			$this->add_responsive_control(
				'rhead_text_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-repeater-head label' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'rhead_icon_size',
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
						'{{WRAPPER}} .ts-repeater-head label i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'rhead_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-repeater-head label i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'rhead_icon_margin',
				[
					'label' => __( 'Icon right margin', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-repeater-head label i' => 'padding-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'rhead_border_color',
				[
					'label' => __( 'Border color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-repeater-head' => 'border-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'rhead_border_width',
				[
					'label' => __( 'Border width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 40,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ts-repeater-head' => 'border-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_repeater_btn',
			[
				'label' => __( 'Form: Repeater "Add" button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'repeater_btn_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'repeater_btn_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'repeater_btn_icon_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-3 i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'repeater_btn_icon_size',
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
								'{{WRAPPER}} .ts-btn-3 i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'repeater_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-3'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'repeater_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-3',
						]
					);

					$this->add_responsive_control(
						'repeater_btn_radius',
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
								'{{WRAPPER}} .ts-btn-3' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'repeater_btn_text',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-btn-3',
						]
					);

					$this->add_control(
						'repeater_btn_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-3'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'repeater_btn_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'repeater_btn_icon_color_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-3:hover i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'repeater_btn_bg_h',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-3:hover'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'repeater_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-3:hover'
								=> 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'repeater_btn_text_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-3:hover'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_trash_btn',
			[
				'label' => __( 'Form: Repeater "Delete" button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_trash_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_trash_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_trash_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-repeater-remove'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_trash_btn_icon_size',
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
								'{{WRAPPER}} .ts-repeater-remove' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_trash_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-repeater-remove'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_trash_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-repeater-remove',
						]
					);

					$this->add_responsive_control(
						'ts_trash_btn_radius',
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
								'{{WRAPPER}} .ts-repeater-remove' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_trash_btn_size',
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
								'{{WRAPPER}} .ts-repeater-remove' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_trash_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_trash_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-repeater-remove:hover'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_trash_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-repeater-remove:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_trash_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-repeater-remove:hover'
								=> 'border-color: {{VALUE}};',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'ts_form_heading',
			[
				'label' => __( 'Form: Heading', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_form_heading_text',
						'label' => __( 'Typography' ),
						'selector' => '{{WRAPPER}} .create-form-step > .ts-form-group.ui-heading-field label',
					]
				);


				$this->add_responsive_control(
					'ts_form_heading_col',
					[
						'label' => __( 'Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .create-form-step > .ts-form-group.ui-heading-field label' => 'color: {{VALUE}}',
						],

					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_form_image',
			[
				'label' => __( 'Form: Image', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_responsive_control(
				'ts_form_image_width',
				[
					'label' => __( 'Width', 'plugin-domain' ),
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
						'{{WRAPPER}} .ui-image-field img' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_form_image_radius',
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
						'{{WRAPPER}} .ui-image-field img' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		

		$this->start_controls_section(
			'ts_avail_calendar',
			[
				'label' => __( 'Form: Availability calendar', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'ts_avail_spacing',
				[
					'label' => __( 'Content spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-availability-calendar' => 'padding: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_avail_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar'
						=> 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_avail_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-availability-calendar',
				]
			);

			$this->add_responsive_control(
				'ts_avail_radius',
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
						'{{WRAPPER}} .ts-availability-calendar' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'availability_field_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-availability-calendar',
				]
			);

			$this->add_control(
				'avail_calendar_months',
				[
					'label' => __( 'Months', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'avail_months_typo',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-availability-calendar .pika-label',
				]
			);

			$this->add_control(
				'avail_months_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar .pika-label'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'avail+days_of_week',
				[
					'label' => __( 'Days of the week', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'avail_days_typo',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-availability-calendar .pika-table abbr[title]',
				]
			);

			$this->add_control(
				'avail_days_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar .pika-table abbr[title]'
						=> 'color: {{VALUE}}',
					],

				]
			);


			$this->add_control(
				'avail_available_date',
				[
					'label' => __( 'Dates (available)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'avail_number_typo',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-availability-calendar td:not(.is-disabled)[aria-selected="false"] .pika-button',
				]
			);

			$this->add_control(
				'avail_number_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar td:not(.is-disabled)[aria-selected="false"] .pika-button'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'avail_number_color_h',
				[
					'label' => __( 'Color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar td:not(.is-disabled)[aria-selected="false"] .pika-button:hover'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'avail_number_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar td:not(.is-disabled)[aria-selected="false"] .pika-button'
						=> 'background-color: {{VALUE}}',
					],

				]
			);


			$this->add_control(
				'avail_number_bg_h',
				[
					'label' => __( 'Background (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar td:not(.is-disabled)[aria-selected="false"] .pika-button:hover'
						=> 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'avail_disabled_date',
				[
					'label' => __( 'Dates (Disabled)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'avail_dis_number_typo',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-availability-calendar .is-selected .pika-button',
				]
			);

			$this->add_control(
				'avail_disabled_number_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar .is-selected .pika-button'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'avail_dis_number_color_h',
				[
					'label' => __( 'Color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar .is-selected .pika-button:hover'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'avail_dis_number_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar .is-selected .pika-button'
						=> 'background-color: {{VALUE}}',
					],

				]
			);


			$this->add_control(
				'avail_dis_number_bg_h',
				[
					'label' => __( 'Background (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar .is-selected .pika-button:hover'
						=> 'background-color: {{VALUE}}',
					],

				]
			);


			

			$this->add_control(
				'avail_unavailable_date',
				[
					'label' => __( 'Dates (unavailable)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'avail_unvailable_date_t',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}}  .ts-availability-calendar .is-disabled .pika-button',
				]
			);

			$this->add_control(
				'avail_unvailable_date_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-availability-calendar .is-disabled .pika-button'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'avail_days_settings',
				[
					'label' => __( 'Other settings', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'avail_days_border_radius',
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
						'{{WRAPPER}} .ts-availability-calendar .pika-button' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);


		$this->end_controls_section();

		$this->start_controls_section(
			'avail_icon_button',
			[
				'label' => __( 'Form: Calendar buttons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'avail_icon_button_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'avail_icon_button_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					
					$this->add_control(
						'avail_ib_styling',
						[
							'label' => __( 'Button styling', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'avail_number_btn_size',
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
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'avail_number_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'avail_number_btn_icon_size',
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
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'avail_number_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'avail_number_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-availability-calendar .ts-icon-btn',
						]
					);

					$this->add_responsive_control(
						'avail_number_btn_radius',
						[
							'label' => __( 'Button border radius', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 100,

								],
								'%' => [
									'min' => 0,
									'max' => 100,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'avail_icon_button_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'avail_popup_number_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn:hover i'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'avail_number_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'avail_button_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-availability-calendar .ts-icon-btn:hover'
								=> 'border-color: {{VALUE}};',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'book_slots',
			[
				'label' => __( 'Form: Timeslots', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'prf_timeslot_cols',
				[
					'label' => __( 'Number of columns', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 6,
					'step' => 1,
					'default' => 1,
					'selectors' => [
						'{{WRAPPER}} .timeslot-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
					],
				]
			);

			
			$this->add_responsive_control(
				'slots_item_gap',
				[
					'label' => __( 'Gap between slots', 'plugin-domain' ),
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
						'{{WRAPPER}} .timeslot-list' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'slots_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .timeslot-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			

			$this->add_control(
				'slots_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .timeslot-list li'
						=> 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'slots_border',
					'label' => __( 'Button border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .timeslot-list li',
				]
			);


			$this->add_responsive_control(
				'slots_radius',
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
						'{{WRAPPER}} .timeslot-list li' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'slots_text',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .timeslot-list li > span'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'slots_typo_text',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .timeslot-list li > span',
				]
			);

			$this->add_control(
				'slots_remove_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .timeslot-list li i'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'slots_remove_size',
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
						'{{WRAPPER}} .timeslot-list li i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_scndry_btn',
			[
				'label' => __( 'Form: Secondary button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'scndry_btn_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'scndry_btn_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'scndry_btn_icon_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1.create-btn i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scndry_btn_icon_size',
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
								'{{WRAPPER}} .ts-btn-1.create-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scndry_btn_icon_margin',
						[
							'label' => __( 'Icon right padding', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-btn-1.create-btn i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'scndry_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1.create-btn'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'scndry_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-1.create-btn',
						]
					);

					$this->add_responsive_control(
						'scndry_btn_radius',
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
								'{{WRAPPER}} .ts-btn-1.create-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scndry_btn_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-btn-1.create-btn' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'scndry_btn_text',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-btn-1.create-btn',
						]
					);

					$this->add_control(
						'scndry_btn_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1.create-btn'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'scndry_btn_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'scndry_btn_icon_color_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1.create-btn:hover i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'scndry_btn_bg_h',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1.create-btn:hover'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'scndry_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1.create-btn:hover'
								=> 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'scndry_btn_text_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1.create-btn:hover'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_tertiary_btn',
			[
				'label' => __( 'Form: Tertiary button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'tertiary_btn_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'tertiary_btn_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'tertiary_btn_icon_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4.create-btn i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'tertiary_btn_icon_size',
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
								'{{WRAPPER}} .ts-btn-4.create-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'tertiary_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4.create-btn'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'tertiary_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-4.create-btn',
						]
					);

					$this->add_responsive_control(
						'tertiary_btn_radius',
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
								'{{WRAPPER}} .ts-btn-4.create-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'tertiary_btn_text',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-btn-4.create-btn',
						]
					);

					$this->add_control(
						'tertiary_btn_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4.create-btn'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'tertiary_btn_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'tertiary_btn_icon_color_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4.create-btn:hover i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'tertiary_btn_bg_h',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4.create-btn:hover'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'tertiary_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4.create-btn:hover'
								=> 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'tertiary_btn_text_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4.create-btn:hover'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'sf_success',
			[
				'label' => __( 'Form: Post submitted/Updated', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'sf_welc_align',
				[
					'label' => __( 'Align icon', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'center',
					'options' => [
						'flex-start'  => __( 'Left', 'plugin-domain' ),
						'center' => __( 'Center', 'plugin-domain' ),
						'flex-end' => __( 'Right', 'plugin-domain' ),
					],
					'selectors' => [
						'{{WRAPPER}} .ts-edit-success' => 'align-items: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'sf_welc_align_text',
				[
					'label' => __( 'Text align', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'center',
					'options' => [
						'left'  => __( 'Left', 'plugin-domain' ),
						'center' => __( 'Center', 'plugin-domain' ),
						'right' => __( 'Right', 'plugin-domain' ),
					],
					'selectors' => [
						'{{WRAPPER}} .ts-edit-success' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'sf_success_icon_heading',
				[
					'label' => __( 'Icon', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'sf_welc_ico_size',
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
						'{{WRAPPER}} .ts-edit-success > i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'sf_welc_ico_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-edit-success > i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'sf_welc_heading',
				[
					'label' => __( 'Heading', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'sf_welc_heading_t',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-edit-success h4',
				]
			);

			$this->add_responsive_control(
				'sf_welc_heading_col',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-edit-success h4' => 'color: {{VALUE}}',
					],

				]
			);

		$this->end_controls_section();





		


	}

	protected function render( $instance = [] ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$post_type = \Voxel\Post_Type::get( $this->get_settings_for_display('ts_post_type') );
		if ( ! $post_type ) {
			return;
		}

		$config = [];
		$config['post_type'] = [
			'key' => $post_type->get_key(),
		];

		$post = null;
		if ( $post_type->get_key() === 'profile' ) {
			$post = \Voxel\current_user()->get_or_create_profile();
		}

		if ( \Voxel\Post::current_user_can_edit( $_GET['post_id'] ?? null ) ) {
			$post = \Voxel\Post::get( $_GET['post_id'] );
		}

		if ( $post && $post->post_type->get_key() !== $post_type->get_key() ) {
			return;
		}

		if ( $post ) {
			$config['post'] = [
				'id' => $post->get_id(),
			];
		}

		$config['fields'] = [];
		$config['steps'] = [];
		foreach ( $post_type->get_fields() as $field ) {
			if ( $post ) {
				$field->set_post( $post );
			}

			$config['fields'][ $field->get_key() ] = $field->get_frontend_config();

			if ( $field->get_type() === 'ui-step' ) {
				$config['steps'][] = $field->get_key();
			}
		}

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/create-post.php' );

		if ( \Voxel\is_edit_mode() ) {
           printf( '<script type="text/javascript">%s</script>', 'window.render_create_post();' );
        }
	}

	public function get_script_depends() {
		return [
			'vx:create-post.js',
		];
	}

	public function get_style_depends() {
		return [
			'vx:create-post.css',
		];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
