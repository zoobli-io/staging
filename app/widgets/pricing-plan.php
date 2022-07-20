<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pricing_Plan extends Base_Widget {

	public function get_name() {
		return 'ts-pricing-plan';
	}

	public function get_title() {
		return __( 'Pricing plan (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		$plans = \Voxel\Membership\Plan::all();
		$options = [];

		foreach ( $plans as $plan ) {
			$pricing = $plan->get_pricing();
			foreach ( [ 'live', 'test' ] as $mode ) {
				if ( is_null( $pricing[ $mode ] ) || empty( $pricing[ $mode ]['prices'] ) ) {
					continue;
				}

				foreach ( $pricing[ $mode ]['prices'] as $price_id => $price ) {
					if ( ! $price['active'] ) {
						continue;
					}

					$option_key = sprintf(
						'%s@%s%s',
						$plan->get_key(),
						$mode === 'test' ? 'test:' : '',
						$price_id
					);

					$option_label = \Voxel\currency_format( $price['amount'], $price['currency'] );
					if ( $period = \Voxel\Membership\Plan::get_price_period( $price ) ) {
						$option_label .= sprintf( ' / %s', $period );
					}

					$options[ $option_key ] = sprintf(
						'%s%s &mdash; %s',
						$mode === 'test' ? '[TEST] ' : '',
						$plan->get_label(),
						$option_label
					);
				}
			}
		}

		$this->start_controls_section( 'ts_prices_section', [
			'label' => __( 'Price groups', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$repeater = new \Elementor\Repeater;
		$repeater->add_control( 'group_label', [
			'label' => __( 'Group label', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Monthly',
		] );

		$repeater->add_control( 'prices', [
			'label' => __( 'Choose prices', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT2,
			'multiple' => true,
			'options' => $options,
			'label_block' => true,
		] );

			$this->add_control( 'ts_price_groups', [
				'label' => __( 'Items', 'elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			] );

			$this->end_controls_section();

			$this->start_controls_section(
				'plans_general',
				[
					'label' => __( 'General', 'plugin-name' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

				$this->add_responsive_control(
					'plans_columns',
					[
						'label' => __( 'Number of columns', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 6,
						'step' => 1,
						'default' => 3,
						'selectors' => [
							'{{WRAPPER}} .ts-plans-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
						],
					]
				);

				$this->add_responsive_control(
					'pplans_gap',
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
							'{{WRAPPER}} .ts-plans-list' => 'grid-gap: {{SIZE}}{{UNIT}};',
						],
					
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'pplans_border',
						'label' => __( 'Border', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-plan-container',
					]
				);


				$this->add_responsive_control(
					'pplans_radius',
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
							'{{WRAPPER}} .ts-plan-container' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'pplans_bg',
					[
						'label' => __( 'Background', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-container' => 'background: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'plan_body',
					[
						'label' => __( 'Plan body', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'pplans_spacing',
					[
						'label' => __( 'Body padding', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-plan-body' => 'padding: {{SIZE}}{{UNIT}};',
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
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-body' => 'grid-gap: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'plan_image',
					[
						'label' => __( 'Plan image', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'plan_img_pad',
					[
						'label' => __( 'Image padding', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'plan_img_max',
					[
						'label' => __( 'height', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-plan-image img' => 'height: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'panel_pricing',
					[
						'label' => __( 'Plan pricing', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'pricing_align',
					[
						'label' => __( 'Align', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'left',
						'options' => [
							'left'  => __( 'Left', 'plugin-domain' ),
							'center' => __( 'Center', 'plugin-domain' ),
							'flex-end' => __( 'Right', 'plugin-domain' ),
						],

						'selectors' => [
							'{{WRAPPER}} .ts-plan-pricing' => 'justify-content: {{VALUE}}',
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'price_typo',
						'label' => __( 'Price typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-plan-price',
					]
				);

				$this->add_responsive_control(
					'price_col',
					[
						'label' => __( 'Price text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-price' => 'color: {{VALUE}}',
						],

					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'period_typo',
						'label' => __( 'Period typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-price-period',
					]
				);

				$this->add_responsive_control(
					'period_col',
					[
						'label' => __( 'Period text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-price-period' => 'color: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'plan_name_section',
					[
						'label' => __( 'Plan name', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'content_align',
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
							'{{WRAPPER}} .ts-plan-details' => 'justify-content: {{VALUE}}',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'name_typo',
						'label' => __( 'Name typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-plan-name',
					]
				);

				$this->add_responsive_control(
					'name_col',
					[
						'label' => __( 'Name text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-name' => 'color: {{VALUE}}',
						],

					]
				);

				

				$this->add_control(
					'plan_list_section',
					[
						'label' => __( 'Plan features', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'list_align',
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
							'{{WRAPPER}} .ts-plan-features ul' => 'align-items: {{VALUE}}',
						],
					]
				);

				$this->add_responsive_control(
					'list_gap',
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
							'{{WRAPPER}} .ts-plan-features ul' => 'grid-gap: {{SIZE}}{{UNIT}};',
						],
					
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'list_typo',
						'label' => __( 'Typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-plan-features ul li span',
					]
				);

				$this->add_responsive_control(
					'list_col',
					[
						'label' => __( 'Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul li span' => 'color: {{VALUE}}',
						],

					]
				);

				$this->add_responsive_control(
					'list_ico_col',
					[
						'label' => __( 'Icon color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-plan-features ul li i' => 'color: {{VALUE}}',
						],

					]
				);

				$this->add_responsive_control(
					'list_ico_size',
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
							'{{WRAPPER}} .ts-plan-features ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'list_ico_right_pad',
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
							'{{WRAPPER}} .ts-plan-features ul li i' => 'padding-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'plan_list_icon',
					[
						'label' => __( 'Feature icon', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'las la-check-circle',
							'library' => 'la-solid',
						],
					]
				);


			$this->end_controls_section();

			$this->start_controls_section(
				'pltabs_section',
				[
					'label' => __( 'Tabs', 'plugin-name' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

				$this->start_controls_tabs(
					'pltabs_tabs'
				);

					/* Normal tab */

					$this->start_controls_tab(
						'pltabs_normal',
						[
							'label' => __( 'Normal', 'plugin-name' ),
						]
					);
						

						$this->add_control(
							'pltabs_tabs_heading',
							[
								'label' => __( 'Tabs', 'plugin-name' ),
								'type' => \Elementor\Controls_Manager::HEADING,
								'separator' => 'before',
							]
						);

						$this->add_control(
							'pltabs_justify',
							[
								'label' => __( 'Justify', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::SELECT,
								'default' => 'left',
								'options' => [
									'left'  => __( 'Left', 'plugin-domain' ),
									'center' => __( 'Center', 'plugin-domain' ),
									'flex-end' => __( 'Right', 'plugin-domain' ),
									'space-between' => __( 'Space between', 'plugin-domain' ),
									'space-around' => __( 'Space around', 'plugin-domain' ),
								],

								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs' => 'justify-content: {{VALUE}}',
								],
							]
						);

						$this->add_control(
							'pltabs_padding',
							[
								'label' => __( 'Padding', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', '%', 'em' ],
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);

						$this->add_control(
							'pltabs_margin',
							[
								'label' => __( 'Margin', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', '%', 'em' ],
								'default' => [
									'unit' => 'px',
									'bottom' => 15,
									'right' => 15,
									'left' => 0,
									'top' => 0,
								],
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'name' => 'pltabs_text',
								'label' => __( 'Tab typography' ),
								'selector' => '{{WRAPPER}} .ts-generic-tabs li a',
							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'name' => 'pltabs_active',
								'label' => __( 'Active tab typography' ),
								'selector' => '{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a',
							]
						);


						$this->add_control(
							'pltabs_text_color',
							[
								'label' => __( 'Text color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_active_text_color',
							[
								'label' => __( 'Active text color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_bg_color',
							[
								'label' => __( 'Background', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a' => 'background-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_bg_active_color',
							[
								'label' => __( 'Active background', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'background-color: {{VALUE}}',
								],

							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'pltabs_border',
								'label' => __( 'Border', 'plugin-domain' ),
								'selector' => '{{WRAPPER}} .ts-generic-tabs li a',
							]
						);

						$this->add_control(
							'pltabs_border_active',
							[
								'label' => __( 'Active border color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_radius',
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
									'{{WRAPPER}} .ts-generic-tabs li a' => 'border-radius: {{SIZE}}{{UNIT}};',
								],
							]
						);


					$this->end_controls_tab();

					/* Hover tab */

					$this->start_controls_tab(
						'pltabs_hover',
						[
							'label' => __( 'Hover', 'plugin-name' ),
						]
					);

						$this->add_control(
							'pltabs_tabs_h',
							[
								'label' => __( 'Timeline tabs', 'plugin-name' ),
								'type' => \Elementor\Controls_Manager::HEADING,
								'separator' => 'before',
							]
						);

						$this->add_control(
							'pltabs_text_color_h',
							[
								'label' => __( 'Text color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'color: {{VALUE}}',
								],

							]
						);

						

						$this->add_control(
							'pltabs_active_text_color_h',
							[
								'label' => __( 'Active text color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_border_color_h',
							[
								'label' => __( 'Border color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_border_h_active',
							[
								'label' => __( 'Active border color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_bg_color_h',
							[
								'label' => __( 'Background', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'background-color: {{VALUE}}',
								],

							]
						);

						$this->add_control(
							'pltabs_active_color_h',
							[
								'label' => __( 'Active background', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'background-color: {{VALUE}}',
								],

							]
						);


					$this->end_controls_tab();

				$this->end_controls_tabs();

			$this->end_controls_section();

			$this->start_controls_section(
				'primary_btn',
				[
					'label' => __( 'Primary button', 'plugin-name' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);

				$this->start_controls_tabs(
					'primary_btn_tabs'
				);

					/* Normal tab */

					$this->start_controls_tab(
						'primary_btn_normal',
						[
							'label' => __( 'Normal', 'plugin-name' ),
						]
					);



						$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'name' => 'primary_btn_typo',
								'label' => __( 'Button typography', 'plugin-domain' ),
								'selector' => '{{WRAPPER}} .ts-btn-2',
							]
						);


						$this->add_responsive_control(
							'primary_btn_radius',
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
									'{{WRAPPER}} .ts-btn-2' => 'border-radius: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_c',
							[
								'label' => __( 'Text color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_padding',
							[
								'label' => __( 'Padding', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::DIMENSIONS,
								'size_units' => [ 'px', '%', 'em' ],
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_height',
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
									'{{WRAPPER}}  .ts-btn-2' => 'height: {{SIZE}}{{UNIT}};',
								],
							]
						);


						$this->add_responsive_control(
							'primary_btn_bg',
							[
								'label' => __( 'Background color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2' => 'background: {{VALUE}}',
								],

							]
						);

						$this->add_group_control(
							\Elementor\Group_Control_Border::get_type(),
							[
								'name' => 'primary_btn_border',
								'label' => __( 'Border', 'plugin-domain' ),
								'selector' => '{{WRAPPER}} .ts-btn-2',
							]
						);


						$this->add_responsive_control(
							'primary_btn_icon_size',
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
									'{{WRAPPER}} .ts-btn-2 i' => 'font-size: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_icon_pad',
							[
								'label' => __( 'Icon left padding', 'plugin-domain' ),
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
									'{{WRAPPER}} .ts-btn-2 i' => 'padding-left: {{SIZE}}{{UNIT}};',
								],
							]
						);

						$this->add_responsive_control(
							'primary_btn_icon_color',
							[
								'label' => __( 'Icon color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2 i' => 'color: {{VALUE}}',
								],

							]
						);
					$this->end_controls_tab();
					/* Hover tab */

					$this->start_controls_tab(
						'primary_btn_hover',
						[
							'label' => __( 'Hover', 'plugin-name' ),
						]
					);

						$this->add_responsive_control(
							'primary_btn_c_h',
							[
								'label' => __( 'Text color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover' => 'color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_bg_h',
							[
								'label' => __( 'Background color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover' => 'background: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_border_h',
							[
								'label' => __( 'Border color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover' => 'border-color: {{VALUE}}',
								],

							]
						);

						$this->add_responsive_control(
							'primary_btn_icon_color_h',
							[
								'label' => __( 'Icon color', 'plugin-domain' ),
								'type' => \Elementor\Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .ts-btn-2:hover i' => 'color: {{VALUE}}',
								],

							]
						);
						


					$this->end_controls_tab();

				$this->end_controls_tabs();

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
								'label' => __( 'Icon left padding', 'plugin-domain' ),
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
									'{{WRAPPER}} .ts-btn-1 i' => 'padding-left: {{SIZE}}{{UNIT}};',
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
		foreach ( $plans as $plan ) {
			$key = sprintf( 'ts_plan:%s', $plan->get_key() );

			$this->start_controls_section( $key.':section', [
				'label' => sprintf( __( 'Plan: %s', 'plugin-name' ), $plan->get_label() ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			] );

			$this->add_control( $key.':image', [
				'label' => __( 'Choose image', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			] );

			$repeater = new \Elementor\Repeater;
			$repeater->add_control( 'text', [
				'label' => __( 'Text', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			] );


			$this->add_control( $key.':features', [
				'label' => __( 'Features', 'elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			] );

			$this->end_controls_section();
		}
	}

	protected function render( $instance = [] ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$groups = $this->get_settings_for_display( 'ts_price_groups' );
		$current_user = \Voxel\current_user();

		// update plan information in case webhook hasn't been triggered yet
		if ( ! empty( $_GET['success'] ) && ! empty( $_GET['session_id'] ) && wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'vx_pricing_checkout' ) ) {
			try {
				$membership = $current_user->get_membership();
				$stripe = \Voxel\Stripe::getClient();
				$session = $stripe->checkout->sessions->retrieve( $_GET['session_id'], [ 'expand' => [ 'subscription' ] ] );
				$subscription = $session->subscription;
				do_action( 'voxel/membership/subscription-updated', $subscription );
				wp_add_inline_script( 'voxel-frontend', 'Voxel.deleteSearchParam("session_id")' );
			} catch ( \Exception $e ) {
				//
			}
		}

		$prices = [];
		foreach ( $groups as $group ) {
			if ( ! is_array( $group['prices'] ) || empty( $group['prices'] ) ) {
				continue;
			}

			foreach ( $group['prices'] as $price_key ) {
				$price_id = substr( strrchr( $price_key, '@' ), 1 );
				$plan_key = str_replace( '@'.$price_id, '', $price_key );
				$mode = substr( $price_id, 0, 5 ) === 'test:' ? 'test' : 'live';
				$price_id = str_replace( 'test:', '', $price_id );

				$plan = \Voxel\Membership\Plan::get( $plan_key );
				if ( ! $plan ) {
					continue;
				}

				$pricing = $plan->get_pricing();
				if ( empty( $pricing[ $mode ] ) || empty( $pricing[ $mode ]['prices'][ $price_id ] ) ) {
					continue;
				}

				$price = $pricing[ $mode ]['prices'][ $price_id ];
				if ( ! $price['active'] ) {
					continue;
				}

				$image = \Elementor\Group_Control_Image_Size::get_attachment_image_html(
					$this->get_settings_for_display(),
					'thumbnail',
					sprintf( 'ts_plan:%s:image', $plan->get_key() )
				);

				$prices[] = [
					'key' => $price_key,
					'group' => $group['_id'],
					'label' => $plan->get_label(),
					'amount' => \Voxel\currency_format( $price['amount'], $price['currency'] ),
					'period' => \Voxel\Membership\Plan::get_price_period( $price ),
					'image' => $image,
					'features' => $this->get_settings_for_display( sprintf( 'ts_plan:%s:features', $plan->get_key() ) ),
					'link' => add_query_arg( [
						'action' => 'plans.choose_plan',
						'plan' => $price_key,
						'redirect_to' => $_GET['redirect_to'] ?? null,
						'_wpnonce' => wp_create_nonce( 'vx_choose_plan' ),
					], home_url('/?vx=1') ),
				];
			}
		}

		if ( empty( $prices ) ) {
			return;
		}

		$default_group = $groups[0]['_id'];
		$membership = $current_user->get_membership();
		$current_price_key = null;
		if ( $membership->get_type() === 'subscription' && ! in_array( $membership->get_status(), [ 'canceled', 'incomplete_expired' ], true ) ) {
			$current_price_key = sprintf(
				'%s@%s%s',
				$membership->plan->get_key(),
				\Voxel\Stripe::is_test_mode() ? 'test:' : '',
				$membership->get_price_id()
			);
		}

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/pricing-plan.php' );
	}

	public function get_style_depends() {
		return [ 'vx:pricing-plan.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
