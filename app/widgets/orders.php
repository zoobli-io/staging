<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Orders extends Base_Widget {

	public function get_name() {
		return 'ts-orders';
	}

	public function get_title() {
		return __( 'Requests and orders (27)', 'voxel' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		

		$this->start_controls_section(
			'ts_sf_styling_filters',
			[
				'label' => __( 'Requests list: Filters', 'plugin-name' ),
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
						'ts_filter_margin',
						[
							'label' => __( 'Form padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);


					$this->add_control(
						'ts_sf_input',
						[
							'label' => __( 'Filter style', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					

					$this->add_responsive_control(
						'ts_filter_gap',
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
								'{{WRAPPER}} .ts-order-filters > .ts-form-group' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_padding',
						[
							'label' => __( 'Filter padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_height',
						[
							'label' => __( 'Filter height', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-order-filters .ts-filter' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-order-filters .ts-filter',
						]
					);




					$this->add_responsive_control(
						'ts_sf_input_bg',
						[
							'label' => __( 'Filter background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter' => 'background: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_sf_input_value_col',
						[
							'label' => __( 'Filter text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_input_input_typo',
							'label' => __( 'Filter typography' ),
							'selector' => '{{WRAPPER}} .ts-order-filters .ts-filter',
						]
					);




					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_input_border',
							'label' => __( 'Filter border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-order-filters .ts-filter',
						]
					);




					$this->add_responsive_control(
						'ts_sf_input_radius',
						[
							'label' => __( 'Filter border radius', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-order-filters .ts-filter' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_col',
						[
							'label' => __( 'Filter icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_size',
						[
							'label' => __( 'Filter icon size', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-order-filters .ts-filter i' => 'font-size: {{SIZE}}{{UNIT}};',
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
								'{{WRAPPER}} .ts-order-filters .ts-filter i' => 'padding-right: {{SIZE}}{{UNIT}};',
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
						'ts_sf_input_h',
						[
							'label' => __( 'Filter style', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_value_col_h',
						[
							'label' => __( 'Filter text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-filter:hover .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter:hover' => 'background: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_sf_input_icon_col_h',
						[
							'label' => __( 'Filter icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter:hover i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow_hover',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-order-filters .ts-filter:hover',
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

					$this->add_control(
						'ts_sf_input_filled',
						[
							'label' => __( 'Filter style (Filled)', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_input_typo_filled',
							'label' => __( 'Typography', 'plugin-domain' ),
							'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
							'selector' => '{{WRAPPER}} .ts-order-filters .ts-filter.ts-filled',
						]
					);

					$this->add_control(
						'ts_sf_input_background_filled',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter.ts-filled' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_value_col_filled',
						[
							'label' => __( 'Filter text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter.ts-filled .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_col_filled',
						[
							'label' => __( 'Filter icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter.ts-filled i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_border_filled',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-filters .ts-filter.ts-filled' => 'border-color: {{VALUE}}',
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
								'{{WRAPPER}} .ts-order-filters .ts-filter.ts-filled' => 'border-width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow_filled',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-order-filters .ts-filter.ts-filled',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_order_filter_icons',
			[
				'label' => __( 'Requests: Icons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);



			$this->add_control(
				'ts_all_requests',
				[
					'label' => __( 'All requests', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-exchange-alt',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_incoming',
				[
					'label' => __( 'Incoming', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-arrow-left',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_outgoing',
				[
					'label' => __( 'Outgoing', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-arrow-right',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_status',
				[
					'label' => __( 'Status', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'lar la-bookmark',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_order_keyword',
				[
					'label' => __( 'Text search', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-search',
						'library' => 'la-solid',
					],
				]
			);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_table_general',
			[
				'label' => __( 'Request list: Item', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'order_row_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'order_row_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_row_col_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-order-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_table_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-item' => 'background: {{VALUE}}',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_freset_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-order-item',
						]
					);

					$this->add_responsive_control(
						'ts_table_radius',
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
								'{{WRAPPER}} .ts-order-item' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_table_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-order-item',
						]
					);

				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'order_row_hover',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_table_bg_hover',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-item:hover' => 'background: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_table_border-color_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}}  .ts-order-item:hover' => 'border-color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_table_border_scale',
						[
							'label' => __( 'Scale', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0.9,
									'max' => 1.1,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}}  .ts-order-item:hover' => 'transform: scale({{SIZE}})',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_req_list',
			[
				'label' => __( 'Requests list', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'ts_req_list_padding',
				[
					'label' => __( 'Container padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .orders-flex,{{WRAPPER}} .ts-single-order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_table_row_column',
			[
				'label' => __( 'Requests list: Item content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'order_row_column_tabs'
			);
				/* Normal tab */

				$this->start_controls_tab(
					'order_row_column_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);
					$this->add_responsive_control(
						'ts_row_gap',
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
								'{{WRAPPER}} .ts-order-item' => 'grid-gap: {{SIZE}}{{UNIT}};',
							],
						
						]
					);

					$this->add_control(
						'ts_row_user',
						[
							'label' => __( 'User', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_row_avatar_size',
						[
							'label' => __( 'Avatar size', 'plugin-domain' ),
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
								'{{WRAPPER}} .data-con img.ts-status-avatar' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_row_avatar_radius',
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
								'{{WRAPPER}} .data-con img.ts-status-avatar' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_row_text',
						[
							'label' => __( 'Text', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_row_text_gap',
						[
							'label' => __( 'Text gap', 'plugin-domain' ),
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
								'{{WRAPPER}} .data-con p, {{WRAPPER}} .data-con span' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_row_primary',
						[
							'label' => __( 'Primary text', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_primary_typo',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .data-con p',
						]
					);

					$this->add_control(
						'ts_primary_color',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .data-con p' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_row_secondary',
						[
							'label' => __( 'Secondary', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_secondary_typo',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .data-con span',
						]
					);

					$this->add_control(
						'ts_secondary_color',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .data-con span' => 'color: {{VALUE}}',
							],
						]
					);

					

					


				
				$this->end_controls_tab();

				$this->start_controls_tab(
					'order_row_column_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);
					


					$this->add_control(
						'ts_row_user_color_h',
						[
							'label' => __( 'User', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} #ts-orders-table tr:hover .ts-table-con p' => 'color: {{VALUE}}',
							],
						]
					);

				


					$this->add_control(
						'ts_row_price_color_hover',
						[
							'label' => __( 'Price', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} #ts-orders-table tr:hover .ts-table-price' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_row_other_color_hover',
						[
							'label' => __( 'Other details', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} #ts-orders-table tr:hover .ts-table-other' => 'color: {{VALUE}}',
							],
						]
					);

					

					$this->add_control(
						'ts_row_stripe_color_hover',
						[
							'label' => __( 'Stripe icon', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} #ts-orders-table tr:hover .ts-table-other i' => 'color: {{VALUE}}',
							],
						]
					);

				
				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_outer_status',
			[
				'label' => __( 'Requests list: Order status', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);



				$this->add_control(
					'ts_ostatus_padding',
					[
						'label' => __( 'Padding', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ts-order-status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_ostatus_height',
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
							'{{WRAPPER}} .ts-order-status' => 'height: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_ostatus_icon_size',
					[
						'label' => __( 'Icon size', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 16,
								'max' => 80,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-order-status i' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				

				$this->add_control(
					'ts_ostatus_icon_spacing',
					[
						'label' => __( 'Icon right spacing', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-order-status i' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_ostatus_radius',
					[
						'label' => __( 'Border radius', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 16,
								'max' => 80,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-order-status' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_ostatus_typo',
						'label' => __( 'Typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-order-status p',
					]
				);


		$this->end_controls_section();

		/*
		==========
		No posts
		==========
		*/

		$this->start_controls_section(
			'ts_no_posts',
			[
				'label' => __( 'Request list: No results', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'ts_nopost_padding',
				[
					'label' => __( 'Container padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ts-no-posts' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_nopost_content_Gap',
				[
					'label' => __( 'Content gap', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-no-posts' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_nopost_ico_size',
				[
					'label' => __( 'Icon size', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-no-posts i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_nopost_ico_col',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-no-posts i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_nopost_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-no-posts p',
				]
			);

			$this->add_responsive_control(
				'ts_nopost_typo_col',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-no-posts p' => 'color: {{VALUE}}',
					],

				]
			);




		$this->end_controls_section();

		$this->start_controls_section(
			'ts_status_colors',
			[
				'label' => __( 'Order status colors', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'tss_complete',
				[
					'label' => __( 'Completed/Active', 'plugin-name' ),
					'description' => __( 'Completed and active status', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'tss_comp_c',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .completed > *, {{WRAPPER}} .sub_active  > *' => 'color: {{VALUE}}',
					],

				]
			);


			$this->add_responsive_control(
				'tss_comp_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .completed, {{WRAPPER}} .sub_active' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'tss_pending',
				[
					'label' => __( 'Pending/Incomplete/Past due', 'plugin-name' ),
					'description' => __( 'Pending payment, pending approval, session completed, subscription unpaid, incomplete or past due', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'tss_pend_c',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .pending_payment > *, {{WRAPPER}} .pending_approval > *, {{WRAPPER}} .session_completed > *, {{WRAPPER}} .sub_past_due > *, {{WRAPPER}} .sub_unpaid > *, {{WRAPPER}} .sub_incomplete > *' => 'color: {{VALUE}}',
					],

				]
			);


			$this->add_responsive_control(
				'tss_pend_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .pending_payment, {{WRAPPER}} .pending_approval, {{WRAPPER}} .session_completed, {{WRAPPER}} .sub_past_due, {{WRAPPER}} .sub_unpaid, {{WRAPPER}} .sub_incomplete' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'tss_canceled',
				[
					'label' => __( 'Canceled/Declined/Refunded', 'plugin-name' ),
					'description' => __( 'Canceled' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'tss_canc_c',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .canceled > *, {{WRAPPER}} .declined > *, {{WRAPPER}} .failed > *, {{WRAPPER}} .refunded > *, {{WRAPPER}} .sub_incomplete_expired > *, {{WRAPPER}} .sub_cancelled > *' => 'color: {{VALUE}}',
					],

				]
			);


			$this->add_responsive_control(
				'tss_canc_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .canceled, {{WRAPPER}} .declined, {{WRAPPER}} .failed, {{WRAPPER}} .refunded, {{WRAPPER}} .sub_incomplete_expired, {{WRAPPER}} .sub_cancelled' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'tss_neutral',
				[
					'label' => __( 'Neutral/Trial', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'tss_neutral_c',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .neutral > *, {{WRAPPER}} .sub_trialing  > *' => 'color: {{VALUE}}',
					],

				]
			);


			$this->add_responsive_control(
				'tss_neutral_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .neutral, {{WRAPPER}} .sub_trialing' => 'background: {{VALUE}}',
					],

				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_req_pag',
			[
				'label' => __( 'Request list: Pagination', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_rpag_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_rpag_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_rpag_top',
						[
							'label' => __( 'Top margin', 'plugin-domain' ),
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
								'{{WRAPPER}} .orders-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_rpag_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .orders-pagination .ts-btn-1',
						]
					);

					$this->add_control(
						'ts_rpag_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px'],
							'selectors' => [
								'{{WRAPPER}}  .orders-pagination .ts-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_rpag_btn_height',
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
								'{{WRAPPER}} .orders-pagination .ts-btn-1' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_rpag_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .orders-pagination .ts-btn-1',
						]
					);

					$this->add_responsive_control(
						'ts_rpag_btn_radius',
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
								'{{WRAPPER}} .orders-pagination .ts-btn-1' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'ts_rpag_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .orders-pagination .ts-btn-1' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_rpag_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .orders-pagination .ts-btn-1' => 'background: {{VALUE}}',
							],

						]
					);



					$this->add_responsive_control(
						'ts_rpag_btn_icon_size',
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
								'{{WRAPPER}} .orders-pagination .ts-btn-1 i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_rpag_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .orders-pagination .ts-btn-1 i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_rpag_icon_spacing',
						[
							'label' => __( 'Icon spacing', 'plugin-domain' ),
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
								'{{WRAPPER}} .orders-pagination .ts-btn-1:nth-child(1) i' => 'padding-right: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .orders-pagination .ts-btn-1:nth-child(2) i' => 'padding-left: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'rpag_next_icon',
						[
							'label' => __( 'Next icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-angle-right',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'rpag_prev_icon',
						[
							'label' => __( 'Prev icon', 'text-domain' ),
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
					'ts_rpag_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_rpag_btn_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .orders-pagination .ts-btn-1:hover' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_rpag_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .orders-pagination .ts-btn-1:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_rpag_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .orders-pagination .ts-btn-1:hover i' => 'color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'pg_icon_button',
			[
				'label' => __( 'Single request: Navigation buttons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'pg_icon_button_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'pg_icon_button_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					
					$this->add_control(
						'ib_styling',
						[
							'label' => __( 'Button styling', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
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
								'{{WRAPPER}} .ts-order-head .ts-icon-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_number_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-head .ts-icon-btn i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
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
								'{{WRAPPER}} .ts-order-head .ts-icon-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_number_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-head .ts-icon-btn' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_number_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-order-head .ts-icon-btn',
						]
					);

					$this->add_responsive_control(
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
								'{{WRAPPER}} .ts-order-head .ts-icon-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ib_icons',
						[
							'label' => __( 'Button icons', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_sr_back',
						[
							'label' => __( 'Back icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-angle-left',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'ts_sr_more',
						[
							'label' => __( 'More icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-ellipsis-h',
								'library' => 'la-solid',
							],
						]
					);

					

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'pg_icon_button_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_popup_number_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-head .ts-icon-btn:hover i'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_number_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-head .ts-icon-btn:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'ts_button_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-order-head .ts-icon-btn:hover'
								=> 'border-color: {{VALUE}};',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_timeline_post_head',
			[
				'label' => __( 'Single request: Post head', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_responsive_control(
					'ts_top_post_avatar_size',
					[
						'label' => __( 'Avatar size', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 16,
								'max' => 60,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-social-feed .ts-status-avatar, {{WRAPPER}}  .ts-status-head .ts-system-ico i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_control(
					'ts_top_post_avatar_radius',
					[
						'label' => __( 'Avatar radius', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-social-feed .ts-status-avatar, {{WRAPPER}}  .ts-status-head .ts-system-ico i' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_top_post_margin',
					[
						'label' => __( 'Avatar right margin', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 20,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}}  .ts-status-head > a, {{WRAPPER}}  .ts-status-head .ts-system-ico' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_top_post_name',
						'label' => __( 'Title typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-status-head a, {{WRAPPER}} .ts-status-head p',
					]
				);

				$this->add_control(
					'ts_top_post_name_color',
					[
						'label' => __( 'Title color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-head a, {{WRAPPER}} .ts-status-head p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_top_post_name_color_h',
					[
						'label' => __( 'Link color (Hover)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-head a:hover' => 'color: {{VALUE}}',
						],
					]
				);





				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_top_post_details',
						'label' => __( 'Details typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-status-head span',
					]
				);

				


				$this->add_control(
					'ts_top_post_name_details_color',
					[
						'label' => __( 'Details color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-head span' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
				'ts_robot',
				[
					'label' => __( 'System icon', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
				);

				$this->add_control(
					'ts_robot_color',
					[
						'label' => __( 'Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-system-ico i' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_robot_background',
					[
						'label' => __( 'Background', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-system-ico i' => 'background-color: {{VALUE}}',
						],
					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_req_approve',
			[
				'label' => __( 'Single request: Approve', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_approve_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_approve_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_approve_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-approve-btn',
						]
					);

					$this->add_control(
						'ts_approve_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px'],
							'selectors' => [
								'{{WRAPPER}} .ts-approve-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_approve_btn_height',
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
								'{{WRAPPER}} .ts-approve-btn' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_approve_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-approve-btn',
						]
					);

					$this->add_responsive_control(
						'ts_approve_btn_radius',
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
								'{{WRAPPER}} .ts-approve-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'ts_approve_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-approve-btn' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_approve_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-approve-btn' => 'background: {{VALUE}}',
							],

						]
					);



					$this->add_responsive_control(
						'ts_approve_btn_icon_size',
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
								'{{WRAPPER}} .ts-approve-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_approve_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-approve-btn i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_approve_icon_spacing',
						[
							'label' => __( 'Icon spacing', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-approve-btn i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'approve_icon',
						[
							'label' => __( 'Next icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-check-circle',
								'library' => 'la-solid',
							],
						]
					);

				

				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'ts_approve_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_approve_btn_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-approve-btn:hover' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_approve_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-approve-btn:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_approve_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-approve-btn:hover i' => 'color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		

		$this->start_controls_section(
			'ts_timeline_body',
			[
				'label' => __( 'Post: Body', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_post_body_typo',
						'label' => __( 'Typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-status-body > p,{{WRAPPER}} .ts-status-body > p > p',
					]
				);

				$this->add_control(
					'ts_post_body_color',
					[
						'label' => __( 'Text Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-body > p,{{WRAPPER}} .ts-status-body > p > p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_post_body_link_color',
					[
						'label' => __( 'Link Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-body > p a' => 'color: {{VALUE}}',
						],
					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_single_status',
			[
				'label' => __( 'Single request: Order status', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);



				$this->add_control(
					'ts_review_padding',
					[
						'label' => __( 'Padding', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ts-inner-status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_review_icon_size',
					[
						'label' => __( 'Icon size', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 16,
								'max' => 80,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-inner-status i' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				

				$this->add_control(
					'ts_review_icon_spacing',
					[
						'label' => __( 'Icon right spacing', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-inner-status i' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_review_radius',
					[
						'label' => __( 'Border radius', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 16,
								'max' => 80,
								'step' => 1,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-inner-status' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_review_typo',
						'label' => __( 'Typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-inner-status p',
					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_single_cards',
			[
				'label' => __( 'Single request: Order cards', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
			
			$this->add_responsive_control(
				'ts_link_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ts-order-card > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);


			$this->add_control(
				'ts_card_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-order-card' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_card_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-order-card',
				]
			);

			$this->add_responsive_control(
				'ts_card_border_radius',
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
						'{{WRAPPER}} .ts-order-card' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'ts_card_border_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-order-card',
				]
			);

			$this->add_control(
				'ts_sc_icon',
				[
					'label' => __( 'Icon', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'ts_sc_icon_size',
				[
					'label' => __( 'Icon size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ts-card-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_sc_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-card-icon' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'ts_sc_icon_bg',
				[
					'label' => __( 'Icon background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-card-icon' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_sc_icon_radius',
				[
					'label' => __( 'Border radius', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-card-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_sc_icon_margin',
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
						'{{WRAPPER}} .ts-order-card ul' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'ts_sc_icon_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-card-icon',
				]
			);

			$this->add_control(
				'ts_sc_primary',
				[
					'label' => __( 'Primary text', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_sc_primary_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-order-card ul p',
				]
			);

			$this->add_control(
				'ts_sc_primary_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-order-card ul p' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'ts_sc_secondary',
				[
					'label' => __( 'Secondary text', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_sc_secondary_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-order-card ul small',
				]
			);

			$this->add_control(
				'ts_sc_secondary_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-order-card ul small' => 'color: {{VALUE}};',
					],
				]
			);





		$this->end_controls_section();

		$this->start_controls_section(
			'ts_comment_divider',
			[
				'label' => __( 'Single request: Comment divider', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'ts_cdiv_margin',
				[
					'label' => __( 'Margin', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .post-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_divider_points',
				[
					'label' => __( 'Points', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'ts_div_point_size',
				[
					'label' => __( 'Size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .post-divider span' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_div_point_radius',
				[
					'label' => __( 'Radius', 'plugin-domain' ),
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
						'{{WRAPPER}} .post-divider span' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_div_point_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-divider span' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_div_point_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .post-divider span',
				]
			);

			$this->add_control(
				'ts_divider_line',
				[
					'label' => __( 'Line', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'ts_div_line_width',
				[
					'label' => __( 'Width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .post-divider b' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_div_line_height',
				[
					'label' => __( 'Height', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .post-divider b' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_div_line_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-divider b' => 'background-color: {{VALUE}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_single_files',
			[
				'label' => __( 'Single request: Attachments', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'ts_att_list_margin',
				[
					'label' => __( 'Attachemnt gap', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ts-status-attachments li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_att_icon_size',
				[
					'label' => __( 'Icon size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ts-status-attachments a i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_att_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-status-attachments a i' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'ts_att_icon_color_h',
				[
					'label' => __( 'Icon color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-status-attachments a:hover i' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_att_icon_margin',
				[
					'label' => __( 'Icon right margin', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ts-status-attachments a i' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_att_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-status-attachments a span',
				]
			);

			$this->add_control(
				'ts_att_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-status-attachments a span' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'ts_att_color_h',
				[
					'label' => __( 'Color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-status-attachments a:hover span' => 'color: {{VALUE}};',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_single_accordion',
			[
				'label' => __( 'Single request: Accordion', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'ts_acc_bg',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .order-info-container' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_acc_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .order-info-container',
				]
			);


			$this->add_responsive_control(
				'ts_acc_border_radius',
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
						'{{WRAPPER}} .order-info-container' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'ts_acc_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .order-info-container',
				]
			);


			$this->add_control(
				'ts_acc_head',
				[
					'label' => __( 'Head', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'ts_acc_head_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .order-info-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'acc_head_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .order-info-head p',
				]
			);

			$this->add_control(
				'acc_head_text',
				[
					'label' => __( 'Text Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .order-info-head p' => 'color: {{VALUE}}',
					],
				]
			);

			

			$this->add_control(
				'acc_head_icon',
				[
					'label' => __( 'Icon Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .order-info-head > i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
				'acc_head_icon_size',
				[
					'label' => __( 'Icon size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .order-info-head > i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_acc_body',
				[
					'label' => __( 'Body', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'acc_body_pad',
				[
					'label' => __( 'Row padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .order-info-container > ul > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'acc_body_row_small',
					'label' => __( 'Row title', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .order-info-container > ul > li small',
				]
			);

			$this->add_control(
				'acc_body_row_small_c',
				[
					'label' => __( 'Row title color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .order-info-container > ul > li small' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'acc_body_row_text',
					'label' => __( 'Row value', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .order-info-container > ul > li p',
				]
			);

			$this->add_control(
				'acc_body_row_text_c',
				[
					'label' => __( 'Row value color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .order-info-container > ul > li p' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'acc_row_border',
				[
					'label' => __( 'Border color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .order-info-container > ul > li' => 'border-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'acc_row_border_width',
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
						'{{WRAPPER}} .order-info-container > ul > li' => 'border-width: {{SIZE}}{{UNIT}};',
					],
				]
			);



		$this->end_controls_section();

		$this->start_controls_section(
			'acc_buttons',
			[
				'label' => __( 'Single request: Accordion buttons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'acc_buttons_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'acc_buttons_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					$this->add_responsive_control(
						'acc_btn_size',
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
								'{{WRAPPER}} .ts-icon-btn.ts-smaller' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'acc_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-icon-btn.ts-smaller i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'acc_btn_icon_size',
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
								'{{WRAPPER}} .ts-icon-btn.ts-smaller i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'acc_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-icon-btn.ts-smaller' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'acc_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-icon-btn.ts-smaller',
						]
					);

					$this->add_responsive_control(
						'ts_acc_btn_radius',
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
								'{{WRAPPER}} .ts-icon-btn.ts-smaller' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);


					

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'acc_buttons_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'acc_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-icon-btn.ts-smaller:hover i'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'acc_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-icon-btn.ts-smaller:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'acc_button_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-icon-btn.ts-smaller:hover'
								=> 'border-color: {{VALUE}};',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_req_comments',
			[
				'label' => __( 'Single request: Post a comment button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_reqc_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_reqc_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					

					$this->add_responsive_control(
						'ts_reqc_input_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_reqc_input_height',
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
							'default' => [
								'unit' => 'px',
								'size' => 50,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);





					$this->add_responsive_control(
						'ts_reqc_input_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter' => 'background: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_reqc_input_value_col',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_reqc_input_input_typo',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-add-status .ts-filter',
						]
					);



					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_reqc_input_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-add-status .ts-filter',
						]
					);




					$this->add_responsive_control(
						'ts_reqc_input_radius',
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
								'{{WRAPPER}} .ts-add-status .ts-filter' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_reqc_input_icon_col',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_reqc_input_icon_size',
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
								'{{WRAPPER}} .ts-add-status .ts-filter i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_reqc_input_icon_margin',
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
								'{{WRAPPER}} .ts-add-status .ts-filter i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_order_comment',
						[
							'label' => __( 'Post comment icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'lar la-newspaper',
								'library' => 'la-regular',
							],
						]
					);

					


				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_reqc_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_reqc_input_value_col_H',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_reqc_input_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_reqc_input_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-add-status .ts-filter:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();


			$this->end_controls_tabs();

		$this->end_controls_section();

		
	}

	protected function render( $instance = [] ) {
		if ( ! is_user_logged_in() ) {
			printf( '<p class="ts-restricted">%s</p>', _x( 'You must be logged in to view this content.', 'orders widget', 'voxel' ) );
			return;
		}

		$config = [
			'activeOrder' => absint( $_GET['order_id'] ?? null ),
			'actions' => $this->get_user_actions(),
		];

		$config['statuses'] = array_merge( [
			'all' => _x( 'All', 'order status', 'voxel' ),
		], \Voxel\Order::get_status_labels() );

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/orders.php' );

		if ( \Voxel\is_edit_mode() ) {
           printf( '<script type="text/javascript">%s</script>', 'window.render_orders();' );
        }
	}

	public function get_script_depends() {
		return [ 'vx:orders.js' ];
	}

	public function get_style_depends() {
		return [ 'vx:orders.css', 'vx:social-feed.css' ];
	}

	public function get_user_actions(): array {
		return [
			'author.approve' => [
				'label' => 'Approve',
				'icon' => 'las la-check-circle',
			],
			'author.decline' => [
				'label' => 'Decline',
				'icon' => 'las la-ban',
			],
			'author.approve_refund' => [
				'label' => 'Approve refund',
				'icon' => 'las la-check-circle',
			],
			'author.decline_refund' => [
				'label' => 'Decline refund',
				'icon' => 'las la-ban',
			],
			'customer.cancel' => [
				'label' => 'Cancel order',
				'icon' => 'las la-ban',
			],
			'customer.request_refund' => [
				'label' => 'Request a refund',
				'icon' => 'las la-info-circle',
			],
			'customer.cancel_refund_request' => [
				'label' => 'Cancel refund request',
				'icon' => 'las la-ban',
			],
			'receipt' => [
				'label' => 'View receipt',
				'icon' => 'las la-file-invoice',
			],
			'customer.portal' => [
				'label' => 'Customer portal',
				'icon' => 'lab la-stripe-s',
			],
			'customer.subscriptions.reactivate' => [
				'label' => 'Reactivate subscription',
				'icon' => 'lar la-play-circle',
			],
			'customer.subscriptions.retry_payment' => [
				'label' => 'Retry payment',
				'icon' => 'las la-sync-alt',
			],
			'customer.subscriptions.cancel' => [
				'label' => 'Cancel subscription',
				'icon' => 'lar la-stop-circle',
			],
		];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
