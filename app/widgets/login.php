<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Login extends Base_Widget {

	public function get_name() {
		return 'ts-login';
	}

	public function get_title() {
		return __( 'Login (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {

		$this->start_controls_section( 'auth_content', [
			'label' => __( 'General', 'plugin-name' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

			$this->add_control( 'ts_view_screen', [
				'label' => __( 'View screen', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'login',
				'options' => [
					'login'  => __( 'Login', 'plugin-name' ),
					'register' => __( 'Register', 'plugin-name' ),
					'confirm_account' => __( 'Confirm account', 'plugin-name' ),
					'recover' => __( 'Recover', 'plugin-name' ),
					'recover_confirm' => __( 'Recover confirm code', 'plugin-name' ),
					'recover_set_password' => __( 'Recover set password', 'plugin-name' ),
					'welcome' => __( 'Welcome', 'plugin-name' ),
					'security' => __( 'Security', 'plugin-name' ),
					'security_update_password' => __( 'Update password', 'plugin-name' ),
					'security_update_email' => __( 'Update email', 'plugin-name' ),
				],
			] );

			

			$this->add_control(
				'auth_title',
				[
					'label' => esc_html__( 'Title', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Hello visitor!', 'plugin-name' ),
					'placeholder' => esc_html__( 'Type your title here', 'plugin-name' ),
				]
			);



		$this->end_controls_section();

		$this->start_controls_section( 'auth_icons', [
			'label' => __( 'Icons', 'plugin-name' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

			$this->add_control( 'auth_google_ico', [
				'label' => __( 'Google icon', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fab fa-google',
					'library' => 'fa-brands',
				],
			] );

			$this->add_control( 'auth_user_ico', [
				'label' => __( 'Username icon', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'las la-user',
					'library' => 'la-solid',
				],
			] );

			$this->add_control( 'auth_pass_ico', [
				'label' => __( 'Lock icon', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'las la-lock',
					'library' => 'la-solid',
				],
			] );

			$this->add_control( 'auth_email_ico', [
				'label' => __( 'Email icon', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'lar la-envelope',
					'library' => 'la-regular',
				],
			] );

			$this->add_control( 'auth_welcome_ico', [
				'label' => __( 'Welcome icon', 'text-domain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'lab la-fly',
					'library' => 'la-brands',
				],
			] );



		$this->end_controls_section();

		$this->start_controls_section( 'auth_style', [
			'label' => __( 'General', 'plugin-name' ),
			'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		] );

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'auth_heading_t',
					'label' => __( 'Title typography' ),
					'selector' => '{{WRAPPER}} .ts-login-head h1',
				]
			);

			$this->add_responsive_control(
				'ts_sf_input_label_col',
				[
					'label' => __( 'Title color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-login-head h1' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'ts_section_spacing',
				[
					'label' => __( 'Section spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .login-section' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_fg_spacing',
				[
					'label' => __( 'Field spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-login .ts-form-group' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'auth_primary_btn',
			[
				'label' => __( 'Primary button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'one_btn_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'one_btn_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);



					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'one_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-login .ts-btn-2',
						]
					);


					$this->add_responsive_control(
						'one_btn_radius',
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
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'one_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'one_btn_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'one_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'one_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-login .ts-btn-2',
						]
					);


					$this->add_responsive_control(
						'one_btn_icon_size',
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
								'{{WRAPPER}} .ts-login .ts-btn-2 i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'one_btn_icon_pad',
						[
							'label' => __( 'Icon right padding', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-login .ts-btn-2 i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'one_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2 i' => 'color: {{VALUE}}',
							],

						]
					);



				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'one_btn_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'one_btn_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'one_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'one_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'one_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-2:hover i' => 'color: {{VALUE}}',
							],

						]
					);
					


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'auth_scnd_btn',
			[
				'label' => __( 'Secondary button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'scnd_btn_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'scnd_btn_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);



					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'scnd_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-login .ts-btn-1',
						]
					);


					$this->add_responsive_control(
						'scnd_btn_radius',
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
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scnd_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scnd_btn_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'scnd_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'scnd_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-login .ts-btn-1',
						]
					);


					$this->add_responsive_control(
						'scnd_btn_icon_size',
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
								'{{WRAPPER}} .ts-login .ts-btn-1 i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scnd_btn_icon_pad',
						[
							'label' => __( 'Icon right padding', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-login .ts-btn-1 i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scnd_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1 i' => 'color: {{VALUE}}',
							],

						]
					);



				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'scnd_btn_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'scnd_btn_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scnd_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scnd_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scnd_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-btn-1:hover i' => 'color: {{VALUE}}',
							],

						]
					);
					


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'auth_google_btn',
			[
				'label' => __( 'Google button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'google_btn_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'google_btn_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);



					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'google_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-login .ts-google-btn',
						]
					);


					$this->add_responsive_control(
						'google_btn_radius',
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
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'google_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'google_btn_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'google_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'google_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-login .ts-google-btn',
						]
					);


					$this->add_responsive_control(
						'google_btn_icon_size',
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
								'{{WRAPPER}} .ts-login .ts-google-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'google_btn_icon_pad',
						[
							'label' => __( 'Icon right padding', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-login .ts-google-btn i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'google_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn i' => 'color: {{VALUE}}',
							],

						]
					);



				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'google_btn_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'google_btn_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'google_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'google_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'google_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-login .ts-google-btn:hover i' => 'color: {{VALUE}}',
							],

						]
					);
					


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'auth_input',
			[
				'label' => __( 'Input', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'auth_input_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'auth_input_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					$this->add_control(
						'auth_input_height',
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
								'{{WRAPPER}} .ts-form input' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'auth_input_radius',
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
								'{{WRAPPER}} .ts-form input' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'auth_input_font',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-form input',
						]
					);

					$this->add_control(
						'auth_input_padding',
						[
							'label' => __( 'Input padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-form input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'auth_input_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form input',
						]
					);

					$this->add_control(
						'auth_input_bg',
						[
							'label' => __( 'Input background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'auth_input_background_filled',
						[
							'label' => __( 'Input background color (Focus)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input:focus' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'auth_input_value_col',
						[
							'label' => __( 'Input value color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'auth_input_placeholder_color',
						[
							'label' => __( 'Input placeholder color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input::-webkit-input-placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .ts-form input:-moz-placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .ts-form input::-moz-placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} .ts-form input:-ms-input-placeholder' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'auth_input_icon_c',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-input-icon > i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'auth_input_icon_size',
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
								'{{WRAPPER}} .ts-input-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'auth_input_icon_margin',
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
								'{{WRAPPER}} .ts-input-icon > i' => 'left: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'auth_input_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);


					$this->add_control(
						'auth_input_h',
						[
							'label' => __( 'Input', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'auth_input_bg_h',
						[
							'label' => __( 'Input background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'auth_input_h_border',
						[
							'label' => __( 'Input border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form input:hover' => 'border-color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'auth_label_section',
			[
				'label' => __( 'Label and description', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'auth_label',
				[
					'label' => __( 'Label', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);


			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'auth_label_typo',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-form-group label, {{WRAPPER}} .container-checkbox p',
				]
			);


			$this->add_responsive_control(
				'auth_label_col',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-form-group label,{{WRAPPER}} .container-checkbox p' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'auth_desc',
				[
					'label' => __( 'Description', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);


			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'auth_desc_t',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-form-group small',
				]
			);


			$this->add_responsive_control(
				'auth_desc_col',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}  .ts-form-group small' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'auth_link',
				[
					'label' => __( 'Link', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'auth_link_t',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-form-group label a',
				]
			);


			$this->add_responsive_control(
				'auth_link_col',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}  .ts-form-group label a' => 'color: {{VALUE}}',
					],

				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'auth_welcome_section',
			[
				'label' => __( 'Welcome', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'welc_align',
				[
					'label' => __( 'Align content', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'center',
					'options' => [
						'flex-start'  => __( 'Left', 'plugin-domain' ),
						'center' => __( 'Center', 'plugin-domain' ),
						'flex-end' => __( 'Right', 'plugin-domain' ),
					],
					'selectors' => [
						'{{WRAPPER}} .ts-welcome-message' => 'align-items: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'welc_align_text',
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
						'{{WRAPPER}} .ts-welcome-message' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'welc_ico',
				[
					'label' => __( 'Welcome icon', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'welc_ico_size',
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
						'{{WRAPPER}} .ts-welcome-message i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'welc_ico_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-welcome-message i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'welc_heading',
				[
					'label' => __( 'Welcome heading', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'welc_heading_t',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-welcome-message h2',
				]
			);

			$this->add_responsive_control(
				'welc_heading_col',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-welcome-message h2' => 'color: {{VALUE}}',
					],

				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'auth_checkbox_section',
			[
				'label' => __( 'Checkbox', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
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
						'{{WRAPPER}} .container-checkbox .checkmark' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
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
						'{{WRAPPER}} .container-checkbox .checkmark' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'check_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .container-checkbox .checkmark',
				]
			);

			$this->add_responsive_control(
				'unchecked_bg',
				[
					'label' => __( 'Background color (unchecked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .container-checkbox .checkmark' => 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'checked_bg',
				[
					'label' => __( 'Background color (checked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .container-checkbox input:checked ~ .checkmark' => 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'checked_border',
				[
					'label' => __( 'Border-color (checked)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .container-checkbox input:checked ~ .checkmark' => 'border-color: {{VALUE}}',
					],

				]
			);



			$this->add_responsive_control(
				'check_text_margin',
				[
					'label' => __( 'Margin against text', 'plugin-domain' ),
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
						'{{WRAPPER}} .container-checkbox p' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}

	protected function render( $instance = [] ) {
		$config = [
			'screen' => 'login',
			'nonce' => wp_create_nonce( 'vx_auth' ),
			'redirectUrl' => \Voxel\get_redirect_url(),
			'register_enabled' => \Voxel\get( 'settings.membership.enabled', true ),
			'recaptcha' => [
				'enabled' => \Voxel\get('settings.recaptcha.enabled'),
				'key' => \Voxel\get('settings.recaptcha.key'),
			],
		];

		// set default screen
		if ( \Voxel\is_edit_mode() && ( $screen = $this->get_settings_for_display( 'ts_view_screen' ) ) ) {
			$config['screen'] = $this->get_settings_for_display( 'ts_view_screen' );
		} elseif ( is_user_logged_in() ) {
			if ( isset( $_GET['welcome'] ) ) {
				$user = \Voxel\current_user();
				$profile = $user->get_or_create_profile();
				$config['screen'] = 'welcome';
				$config['editProfileUrl'] = $profile ? $profile->get_edit_link() : null;
				$config['userDisplayName'] = $user->get_display_name();
			} else {
				$config['screen'] = 'security';
			}
		} elseif ( isset( $_GET['register'] ) && \Voxel\get( 'settings.membership.enabled', true ) ) {
			$config['screen'] = 'register';
		} else {
			$config['screen'] = 'login';
		}

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/login.php' );

		if ( \Voxel\is_edit_mode() ) {
			printf( '<script type="text/javascript">%s</script>', 'window.render_auth();' );
		}
	}

	public function get_script_depends() {
		return [
			'google-recaptcha',
			'vx:auth.js',
		];
	}

	public function get_style_depends() {
		return [ 'vx:login.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
