<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Current_Plan extends Base_Widget {

	public function get_name() {
		return 'ts-current-plan';
	}

	public function get_title() {
		return __( 'Current plan (27)', 'voxel' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'panel_options',
			[
				'label' => __( 'Panel', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'panel_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-panel',
				]
			);


			$this->add_responsive_control(
				'panel_radius',
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
						'{{WRAPPER}} .ts-panel' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'panel_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-panel' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'panel_pricing',
				[
					'label' => __( 'Panel Pricing', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'price_align',
				[
					'label' => __( 'Align content', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'left'  => __( 'Left', 'plugin-domain' ),
						'center' => __( 'Center', 'plugin-domain' ),
						'flex-end' => __( 'Right', 'plugin-domain' ),
					],

					'selectors' => [
						'{{WRAPPER}} .active-plan .ac-plan-pricing' => 'justify-content: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'price_typo',
					'label' => __( 'Price typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ac-plan-price',
				]
			);

			$this->add_responsive_control(
				'price_col',
				[
					'label' => __( 'Price text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ac-plan-price' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'period_typo',
					'label' => __( 'Period typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ac-price-period',
				]
			);

			$this->add_responsive_control(
				'period_col',
				[
					'label' => __( 'Period text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ac-price-period' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'panel_body',
				[
					'label' => __( 'Panel body', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);



			$this->add_responsive_control(
				'panel_spacing',
				[
					'label' => __( 'Body spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ac-body' => 'padding: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'panel_gap',
				[
					'label' => __( 'Body content gap', 'plugin-domain' ),
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
						'{{WRAPPER}} .ac-body' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				]
			);


			$this->add_control(
				'text_align',
				[
					'label' => __( 'Align text', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'left'  => __( 'Left', 'plugin-domain' ),
						'center' => __( 'Center', 'plugin-domain' ),
						'right' => __( 'Right', 'plugin-domain' ),
					],

					'selectors' => [
						'{{WRAPPER}} .ac-body' => 'text-align: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'body_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-panel .ac-body p',
				]
			);

			$this->add_responsive_control(
				'body_typo_col',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-panel .ac-body p' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'body_typo_link',
					'label' => __( 'Link typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-panel .ac-body p a',
				]
			);

			$this->add_responsive_control(
				'body_col_link',
				[
					'label' => __( 'Link color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-panel .ac-body p a' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'panel_head',
				[
					'label' => __( 'Panel head', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'head_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ac-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'head_ico_size',
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
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ts-panel .ac-head i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'head_ico_margin',
				[
					'label' => __( 'Icon right margin', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-panel .ac-head i' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'head_ico_col',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-panel .ac-head i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'head_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-panel .ac-head p',
				]
			);

			$this->add_responsive_control(
				'head_typo_col',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-panel .ac-head p' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'panel_buttons',
				[
					'label' => __( 'Panel buttons', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);


			$this->add_responsive_control(
				'panel_buttons_grid',
				[
					'label' => __( 'Number of columns', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 6,
					'step' => 1,
					'default' => 3,
					'selectors' => [
						'{{WRAPPER}} .current-plan-btn' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
					],
				]
			);

			$this->add_responsive_control(
				'panel_buttons_gap',
				[
					'label' => __( 'Item gap', 'plugin-domain' ),
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
						'{{WRAPPER}} .current-plan-btn' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				
				]
			);

		$this->end_controls_section();


		$this->start_controls_section(
			'scnd_btn',
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
							'selector' => '{{WRAPPER}} .ts-btn-1',
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
								'{{WRAPPER}} .ts-btn-1' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scnd_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1' => 'color: {{VALUE}}',
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
								'{{WRAPPER}} .ts-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scnd_btn_height',
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
								'{{WRAPPER}}  .ts-btn-1' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'scnd_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'scnd_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-1',
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
								'{{WRAPPER}} .ts-btn-1 i' => 'font-size: {{SIZE}}{{UNIT}};',
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
								'{{WRAPPER}} .ts-btn-1 i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'scnd_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1 i' => 'color: {{VALUE}}',
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
								'{{WRAPPER}} .ts-btn-1:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scnd_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scnd_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scnd_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-1:hover i' => 'color: {{VALUE}}',
							],

						]
					);
					


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render( $instance = [] ) {
		if ( ! is_user_logged_in() ) {
			printf( '<p class="ts-restricted">%s</p>', _x( 'You must be logged in to view this content.', 'current plan widget', 'voxel' ) );
			return;
		}

		$current_user = \Voxel\current_user();
		$membership = $current_user->get_membership();
		$switch_url = get_permalink( \Voxel\get( 'templates.pricing' ) );
		$portal_url = home_url( '/?vx=1&action=stripe.customer.portal' );
		$retry_payment_url = null;
		$reactivate_url = null;
		$cancel_url = null;

		$current_price_key = null;
		if ( $membership->get_type() === 'subscription' ) {
			$current_price_key = sprintf(
				'%s@%s%s',
				$membership->plan->get_key(),
				\Voxel\Stripe::is_test_mode() ? 'test:' : '',
				$membership->get_price_id()
			);

			$retry_payment_url = wp_nonce_url( home_url( '/?vx=1&action=plans.retry_payment' ), 'vx_retry_payment' );
			$reactivate_url = wp_nonce_url( home_url( '/?vx=1&action=plans.reactivate_plan' ), 'vx_reactivate_plan' );
			$cancel_url = wp_nonce_url( home_url( '/?vx=1&action=plans.cancel_plan' ), 'vx_cancel_plan' );
		}

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/current-plan.php' );
	}

	public function get_style_depends() {
		return [ 'vx:pricing-plan.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
