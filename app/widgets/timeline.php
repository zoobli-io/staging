<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Timeline extends Base_Widget {

	public function get_name() {
		return 'ts-timeline';
	}

	public function get_title() {
		return __( 'Timeline (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'ts_timeline_settings',
			[
				'label' => __( 'Timeline settings', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control( 'ts_mode', [
			'label' => __( 'Display mode', 'plugin-domain' ),
			'label_block' => true,
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'user_feed',
			'options' => [
				'post_reviews' => 'Current post reviews',
				'post_wall' => 'Current post wall',
				'post_timeline' => 'Current post timeline',
				'author_timeline' => 'Current author timeline',
				'user_feed' => 'Logged-in user news feed',
			],
		] );

		$repeater = new \Elementor\Repeater;

		$repeater->add_control( 'ts_order', [
			'label' => __( 'Order', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'latest',
			'options' => [
				'latest' => __( 'Latest', 'plugin-domain' ),
				'earliest' => __( 'Earliest', 'plugin-domain' ),
				'most_liked' => __( 'Most liked', 'plugin-domain' ),
				'most_discussed' => __( 'Most discussed', 'plugin-domain' ),
				'most_popular' => __( 'Most popular (likes+comments)', 'plugin-domain' ),
				'best_rated' => __( 'Best rated (reviews only)', 'plugin-domain' ),
				'worst_rated' => __( 'Worst rated (reviews only)', 'plugin-domain' ),
			],
		] );

		$repeater->add_control( 'ts_time', [
			'label' => __( 'Timeframe', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'all_time',
			'options' => [
				'today' => __( 'Today', 'plugin-domain' ),
				'this_week' => __( 'This week', 'plugin-domain' ),
				'this_month' => __( 'This month', 'plugin-domain' ),
				'this_year' => __( 'This year', 'plugin-domain' ),
				'all_time' => __( 'All time', 'plugin-domain' ),
				'custom' => __( 'Custom', 'plugin-domain' ),
			],
		] );

		$repeater->add_control( 'ts_time_custom', [
			'label' => __( 'Show items from the past number of days', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => 7,
			'condition' => [ 'ts_time' => 'custom' ],
		] );

		$repeater->add_control( 'ts_label', [
			'label' => __( 'Label', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'default' => 'Latest',
		] );

		$this->add_control( 'ts_ordering_options', [
			'label' => __( 'Ordering options', 'elementor' ),
			'type' => \Elementor\Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'_disable_loop' => true,
			'title_field' => '{{{ ts_label }}}',
		] );

		$this->add_control(
			'add_status_text',
			[
				'label' => __( 'Create status text', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type your text', 'plugin-domain' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_timeline_tabs_section',
			[
				'label' => __( 'Timeline: Tabs', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_timeline_el_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_tabs_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);
					

					$this->add_control(
						'ts_timeline_tabs',
						[
							'label' => __( 'Timeline tabs', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_tabs_justify',
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
						'ts_tabs_padding',
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
						'ts_tabs_margin',
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
							'name' => 'ts_tabs_text',
							'label' => __( 'Tab typography' ),
							'selector' => '{{WRAPPER}} .ts-generic-tabs li a',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_tabs_text_active',
							'label' => __( 'Active tab typography' ),
							'selector' => '{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a',
						]
					);


					$this->add_control(
						'ts_tabs_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li a' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_active_text_color',
						[
							'label' => __( 'Active text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_tabs_bg_color',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li a' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_tabs_bg_active_color',
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
							'name' => 'ts_tabs_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-generic-tabs li a',
						]
					);

					$this->add_control(
						'ts_tabs_border_active',
						[
							'label' => __( 'Active border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_tabs_radius',
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
					'ts_tabs_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_timeline_tabs_h',
						[
							'label' => __( 'Timeline tabs', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_tabs_text_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'color: {{VALUE}}',
							],

						]
					);

					

					$this->add_control(
						'ts_tabs_active_text_color_h',
						[
							'label' => __( 'Active text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_tabs_border_color_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_tabs_border_h_active',
						[
							'label' => __( 'Active border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li.ts-tab-active a:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_tabs_bg_color_h',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-generic-tabs li a:hover' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_bg_active_color_h',
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
			'ts_timeline_post_general',
			[
				'label' => __( 'Timeline: General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'ts_timeline_post_spacing',
				[
					'label' => __( 'Post spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-status-list' => 'grid-gap: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .ts-status:after' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_status_separator',
				[
					'label' => __( 'Enable post divider?', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'flex;',
					'selectors' => [
						'{{WRAPPER}} .ts-status:after' => 'display: {{VALUE}}',
					],
					
				]
			);

			$this->add_responsive_control(
				'ts_status_divider_c',
				[
					'label' => __( 'Divider color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-status:after' => 'background: {{VALUE}}',
					],
					

				]
			);

			$this->add_responsive_control(
				'ts_status_divider_width',
				[
					'label' => __( 'Divider height', 'plugin-domain' ),
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
						'{{WRAPPER}}  .ts-status:after' => 'height: {{SIZE}}{{UNIT}};',
					],
				
				]
			);

			$this->add_control(
				'ts_post__content_spacing',
				[
					'label' => __( 'Post content spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-status > .ts-status-head, {{WRAPPER}} .ts-review-score, {{WRAPPER}} .ts-post-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						

						'{{WRAPPER}} .ts-status-footer.ts-parent' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);


			$this->add_responsive_control(
				'ts_status_bg',
				[
					'label' => __( 'Post background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-status' => 'background: {{VALUE}}',
					],

				]
			);


			$this->add_responsive_control(
				'ts_status_padding',
				[
					'label' => __( 'Post padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .ts-status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_post_border',
					'label' => __( 'Post border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-status',
				]
			);

			$this->add_responsive_control(
				'ts_status_radius',
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
						'{{WRAPPER}}  .ts-status' => 'border: {{SIZE}}{{UNIT}};',
					],
				]
			);

			

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_styling_filters',
			[
				'label' => __( 'Timeline: Post/comment button', 'plugin-name' ),
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
								'{{WRAPPER}} .ts-form .ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_height',
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
								'{{WRAPPER}} .ts-form .ts-filter' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);





					$this->add_responsive_control(
						'ts_sf_input_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter' => 'background: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_sf_input_value_col',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_input_input_typo',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-form .ts-filter',
						]
					);



					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_input_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-filter',
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
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_col',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-filter i' => 'color: {{VALUE}}',
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
								'{{WRAPPER}} .ts-filter i' => 'font-size: {{SIZE}}{{UNIT}};',
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
								'{{WRAPPER}} .ts-filter i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_create_icon',
						[
							'label' => __( 'Create status icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'lar la-newspaper',
								'library' => 'la-regular',
							],
						]
					);

					$this->add_control(
						'ts_supdate_bottom_space',
						[
							'label' => __( 'Bottom spacing (Create status)', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-social-feed .ts-add-status' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

					$this->add_responsive_control(
						'ts_sf_input_value_col_H',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter:hover .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();


			$this->end_controls_tabs();

		$this->end_controls_section();

		/*
		==========
		No posts
		==========
		*/

		$this->start_controls_section(
			'ts_no_posts',
			[
				'label' => __( 'Timeline: Loading/No posts', 'plugin-name' ),
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
			'ts_timeline_post_head',
			[
				'label' => __( 'Post: Head', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

				$this->add_control(
					'ts_top_post',
					[
						'label' => __( 'Post head', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
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
							'{{WRAPPER}} .ts-status-head.ts-parent .ts-status-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
							'{{WRAPPER}} .ts-status-avatar' => 'border-radius: {{SIZE}}{{UNIT}};',
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
							'{{WRAPPER}}  .ts-status-head.ts-parent > a' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_top_post_name',
						'label' => __( 'Link typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-status-head.ts-parent a',
					]
				);

				$this->add_control(
					'ts_top_post_name_color',
					[
						'label' => __( 'Link color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-head.ts-parent a' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_top_post_name_color_h',
					[
						'label' => __( 'Link color (Hover)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-head.ts-parent a:hover' => 'color: {{VALUE}}',
						],
					]
				);


				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_top_post_details',
						'label' => __( 'Details typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-status-head.ts-parent span',
					]
				);

				$this->add_control(
					'ts_top_post_name_details_color',
					[
						'label' => __( 'Details color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-head.ts-parent span' => 'color: {{VALUE}}',
						],
					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_timeline_body',
			[
				'label' => __( 'Post: Body', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_control(
					'ts_post_body',
					[
						'label' => __( 'Post body', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_post_body_typo',
						'label' => __( 'Typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-status-body.ts-parent > p,{{WRAPPER}} .ts-status-body.ts-parent > p > p',
					]
				);

				$this->add_control(
					'ts_post_body_color',
					[
						'label' => __( 'Text Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-body.ts-parent > p,{{WRAPPER}} .ts-status-body.ts-parent > p > p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_post_body_link_color',
					[
						'label' => __( 'Link Color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-status-body.ts-parent > p a' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_post_body_radius',
					[
						'label' => __( 'Post image radius', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-status-body img, {{WRAPPER}} .ts-external-link .ts-external-image' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_post_body_link_title',
						'label' => __( 'Shared link title', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-external-link .ts-external-details p',
					]
				);

				$this->add_control(
					'ts_post_body_link_title_c',
					[
						'label' => __( 'Shared link title color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-external-link .ts-external-details p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_post_body_link_description',
						'label' => __( 'Shared link description', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-external-link .ts-external-details span',
					]
				);

				$this->add_control(
					'ts_post_body_link_description_c',
					[
						'label' => __( 'Shared link description color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-external-link .ts-external-details span' => 'color: {{VALUE}}',
						],
					]
				);



		$this->end_controls_section();

		$this->start_controls_section(
			'ts_timeline_review',
			[
				'label' => __( 'Post: Reviews', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_control(
					'ts_review_general',
					[
						'label' => __( 'General', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_review_padding',
					[
						'label' => __( 'Review padding', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .ts-review-score' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_review_icon_size',
					[
						'label' => __( 'Review icon size', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-review-score i' => 'font-size: {{SIZE}}{{UNIT}};',
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
							'{{WRAPPER}} .ts-review-score i' => 'margin-right: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_review_radius',
					[
						'label' => __( 'Review border radius', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-review-score' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_review_typo',
						'label' => __( 'Review score typography', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-review-score p',
					]
				);

				$this->add_control(
					'ts_review_excellent',
					[
						'label' => __( 'Excellent', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_review_excellent_bg',
					[
						'label' => __( 'Background color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.excellent' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_excellent_icon_color',
					[
						'label' => __( 'Icon color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.excellent i' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_excellent_text_color',
					[
						'label' => __( 'Text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.excellent p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_excellent_icon',
					[
						'label' => __( 'Choose icon', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'las la-laugh',
							'library' => 'la-solid',
						],
					]
				);

				$this->add_control(
					'ts_review_very_good',
					[
						'label' => __( 'Very good', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_review_very_good_bg',
					[
						'label' => __( 'Background color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.very-good' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_very_good_icon_color',
					[
						'label' => __( 'Icon color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.very-good i' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_very_good_text_color',
					[
						'label' => __( 'Text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.very-good p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_very_good_icon',
					[
						'label' => __( 'Choose icon', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'las la-smile-beam',
							'library' => 'la-solid',
						],
					]
				);

				$this->add_control(
					'ts_review_good',
					[
						'label' => __( 'Good', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_review_good_bg',
					[
						'label' => __( 'Background color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.good' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_good_icon_color',
					[
						'label' => __( 'Icon color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.good i' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_good_text_color',
					[
						'label' => __( 'Text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.good p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_good_icon',
					[
						'label' => __( 'Choose icon', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'las la-smile',
							'library' => 'la-solid',
						],
					]
				);

				$this->add_control(
					'ts_review_fair',
					[
						'label' => __( 'Fair', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_review_fair_bg',
					[
						'label' => __( 'Background color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.fair' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_fair_icon_color',
					[
						'label' => __( 'Icon color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.fair i' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_fair_text_color',
					[
						'label' => __( 'Text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.fair p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_fair_icon',
					[
						'label' => __( 'Choose icon', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'las la-meh',
							'library' => 'la-solid',
						],
					]
				);

				$this->add_control(
					'ts_review_poor',
					[
						'label' => __( 'Poor', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_review_poor_bg',
					[
						'label' => __( 'Background color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.poor' => 'background-color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_poor_icon_color',
					[
						'label' => __( 'Icon color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.poor i' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_poor_text_color',
					[
						'label' => __( 'Text color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-review-score.poor p' => 'color: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_review_poor_icon',
					[
						'label' => __( 'Choose icon', 'text-domain' ),
						'type' => \Elementor\Controls_Manager::ICONS,
						'default' => [
							'value' => 'las la-frown',
							'library' => 'la-solid',
						],
					]
				);

				





		$this->end_controls_section();

		$this->start_controls_section(
			'ts_timeline_footer',
			[
				'label' => __( 'Post: Footer', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_post_footer_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_timeline_posts_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_post_footer',
						[
							'label' => __( 'Post footer', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_post_footer_like_icon',
						[
							'label' => __( 'Like icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'lar la-heart',
								'library' => 'la-regular',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_liked_icon',
						[
							'label' => __( 'Liked icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-heart',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_comment_icon',
						[
							'label' => __( 'Comment icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-comment',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_reply_icon',
						[
							'label' => __( 'Reply icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-reply',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_delete_icon',
						[
							'label' => __( 'Delete icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-trash-alt',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_edit_icon',
						[
							'label' => __( 'Edit icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-pencil-alt',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_responsive_control(
						'ts_post_footer_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 70,
									'step' => 1,
								],
							],

							'selectors' => [
								'{{WRAPPER}} .ts-status-footer.ts-parent > ul a i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_footer_icon_spacing',
						[
							'label' => __( 'Item spacing', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-status-footer.ts-parent > ul > li' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-status-footer > ul a i' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_liked_color',
						[
							'label' => __( 'Icon color (Liked state)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-status-footer > ul a.ts-liked i' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_post_footer_text',
							'label' => __( 'Text', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-status-footer.ts-parent > ul span',
						]
					);

					$this->add_control(
						'ts_post_footer_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-status-footer.ts-parent > ul span' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_footer_text_spacing',
						[
							'label' => __( 'Text left margin', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-status-footer.ts-parent > ul span' => 'margin-left: {{SIZE}}{{UNIT}};',
							],
						]
					);

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_timeline_posts_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);
					$this->add_control(
						'ts_post_footer_h',
						[
							'label' => __( 'Post footer', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_control(
						'ts_post_footer_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-status-footer > ul a:hover i' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_liked_color_h',
						[
							'label' => __( 'Icon color (Liked state)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-status-footer > ul a.ts-liked:hover i' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_post_footer_text_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-status-footer > ul a:hover span' => 'color: {{VALUE}}',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_timeline_comments',
			[
				'label' => __( 'Post: Comments', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'ts_commentt_spacing',
				[
					'label' => __( 'Spacing between comments', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-reply' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .status-comments-list ' => 'padding-top: {{SIZE}}{{UNIT}};',
						
					],
				]
			);

			$this->add_control(
				'ts_comment_avatar_heading',
				[
					'label' => __( 'Comment avatar', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'ts_comment_avatar_size',
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
						'{{WRAPPER}} .status-comments-list .ts-status-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
				]
			);


			$this->add_responsive_control(
				'ts_comment_avatar_margin',
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
						'{{WRAPPER}}  .ts-status-comments > ul > li a.ts-user-avatar' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_comment_head_heading',
				[
					'label' => __( 'Comment head', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_comment_name',
					'label' => __( 'Name typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .status-comments-list .ts-status-head a',
				]
			);

			$this->add_control(
				'ts_comment_name_color',
				[
					'label' => __( 'Name color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .status-comments-list .ts-status-head a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_comment_details',
					'label' => __( 'Comment details typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .status-comments-list .ts-status-head span',
				]
			);

			$this->add_control(
				'ts_comment_details_color',
				[
					'label' => __( 'Comment details color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .status-comments-list .ts-status-head span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_comment_body_heading',
				[
					'label' => __( 'Comment body', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_comments_body_typo',
					'label' => __( 'Comment typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .status-comments-list .ts-status-body  p',
				]
			);

			$this->add_control(
				'ts_comment_body_color',
				[
					'label' => __( 'Comment Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .status-comments-list .ts-status-body p' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_comment_footer_heading',
				[
					'label' => __( 'Comment footer', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'ts_comment_footer_icon_size',
				[
					'label' => __( 'Icon size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 70,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .status-comments-list .ts-status-footer > ul a i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_comment_footer_icon_spacing',
				[
					'label' => __( 'Item spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .status-comments-list .ts-status-footer > ul > li' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_comment_footer_text',
					'label' => __( 'Text', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .status-comments-list .ts-status-footer > ul a span',
				]
			);

			
			$this->add_control(
				'ts_comment_footer_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .status-comments-list .ts-status-footer > ul a span' => 'color: {{VALUE}}',
					],
				]
			);


			$this->add_control(
				'ts_comment_footer_text_spacing',
				[
					'label' => __( 'Text left margin', 'plugin-domain' ),
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
						'{{WRAPPER}} .status-comments-list .ts-status-footer > ul a span' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_comment_levels',
				[
					'label' => __( 'Comment levels', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'ts_comment_level_space',
				[
					'label' => __( 'Inner comment left padding', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-status-comments > ul > li .status-comments-list' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_comment_line',
				[
					'label' => __( 'Comment level line', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'ts_comment_line_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} li.ts-reply::before' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_comment_line_width',
				[
					'label' => __( 'Width', 'plugin-domain' ),
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
						'{{WRAPPER}} li.ts-reply::before' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_comment_line_top',
				[
					'label' => __( 'Top spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} li.ts-reply::before' => 'top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_comment_line_left',
				[
					'label' => __( 'Left spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} li.ts-reply::before' => 'left: {{SIZE}}{{UNIT}};',
					],
				]
			);



		$this->end_controls_section();

		$this->start_controls_section(
			'ts_timeline_load',
			[
				'label' => __( 'Posts: Load more', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_timeline_load_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_timeline_load_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_timeline_load_btn',
						[
							'label' => __( 'Load more button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_timeline_load_padding',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-load-more',
						]
					);

					$this->add_control(
						'ts_timeline_load_height',
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
								'{{WRAPPER}} .ts-load-more' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_timeline_load_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-load-more',
						]
					);

					$this->add_control(
						'ts_timeline_load_radius',
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
								'{{WRAPPER}} .ts-load-more' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_timeline_load_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_timeline_load_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_timeline_load_icon',
						[
							'label' => __( 'Icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-sync-alt',
								'library' => 'la-solid',
							],
						]
					);




				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'ts_timeline_load_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_timeline_load_btn_h',
						[
							'label' => __( 'Load more button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_timeline_load_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_timeline_load_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_timeline_load_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();



			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_comments_load',
			[
				'label' => __( 'Comments: Load more', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_comments_load_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_comments_load_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_comments_load_btn',
						[
							'label' => __( 'Load more button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_comments_load_padding',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-load-more-comments',
						]
					);

					$this->add_control(
						'ts_comments_load_height',
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
								'{{WRAPPER}} .ts-load-more-comments' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_comments_load_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-load-more-comments',
						]
					);

					$this->add_control(
						'ts_comments_load_radius',
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
								'{{WRAPPER}} .ts-load-more-comments' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_comments_load_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more-comments' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_comments_load_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more-comments' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_comments_load_icon',
						[
							'label' => __( 'Icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-sync-alt',
								'library' => 'la-solid',
							],
						]
					);




				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'ts_comments_load_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_comments_load_btn_h',
						[
							'label' => __( 'Load more button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_comments_load_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more-comments:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_comments_load_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more-comments:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_comments_load_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-load-more-comments:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();



			$this->end_controls_tabs();

		$this->end_controls_section();



	}

	protected function render( $instance = [] ) {
		$ratings = [
			[
				'label' => _x( 'Excellent', 'rating', 'voxel' ),
				'icon' => \Voxel\get_icon_markup( $this->get_settings('ts_review_excellent_icon') ),
				'key' => 'excellent',
				'score' => 2,
			],
			[
				'label' => _x( 'Very good', 'rating', 'voxel' ),
				'icon' => \Voxel\get_icon_markup( $this->get_settings('ts_review_very_good_icon') ),
				'key' => 'very-good',
				'score' => 1,
			],
			[
				'label' => _x( 'Good', 'rating', 'voxel' ),
				'icon' => \Voxel\get_icon_markup( $this->get_settings('ts_review_good_icon') ),
				'key' => 'good',
				'score' => 0,
			],
			[
				'label' => _x( 'Fair', 'rating', 'voxel' ),
				'icon' => \Voxel\get_icon_markup( $this->get_settings('ts_review_fair_icon') ),
				'key' => 'fair',
				'score' => -1,
			],
			[
				'label' => _x( 'Poor', 'rating', 'voxel' ),
				'icon' => \Voxel\get_icon_markup( $this->get_settings('ts_review_poor_icon') ),
				'key' => 'poor',
				'score' => -2,
			],
		];

		$ordering_options = [];
		foreach ( (array) $this->get_settings( 'ts_ordering_options' ) as $ordering_option ) {
			$ordering_options[] = [
				'_id' => $ordering_option['_id'],
				'label' => $ordering_option['ts_label'],
				'order' => $ordering_option['ts_order'],
				'time' => $ordering_option['ts_time'],
				'time_custom' => $ordering_option['ts_time_custom'],
			];
		}

		if ( empty( $ordering_options ) ) {
			$ordering_options = [ [
				'_id' => '_latest',
				'label' => 'Latest',
				'order' => 'latest',
				'time' => 'all_time',
				'time_custom' => null,
			] ];
		}

		$user = \Voxel\current_user();
		$post = \Voxel\get_current_post( true );
		$mode = $this->get_settings( 'ts_mode' );

		$can_post = false;
		$name = null;
		$avatar = null;

		if ( $post && $user ) {
			$author = $post->get_author();
			$name = $user->get_display_name();
			$avatar = $user->get_avatar_markup();

			if ( $mode === 'post_reviews' && $user->can_review_post( $post->get_id() ) ) {
				$can_post = true;
			} elseif ( $mode === 'post_wall' && $user->can_post_to_wall( $post->get_id() ) ) {
				$can_post = true;
			} elseif ( $mode === 'post_timeline' && $post->is_editable_by_current_user() ) {
				$can_post = true;
				$name = $post->get_title();
				$avatar = $post->get_logo_markup();
			} elseif ( $mode === 'author_timeline' && $author && $author->get_id() === $user->get_id() ) {
				$can_post = true;
			} elseif ( $mode === 'user_feed' ) {
				$can_post = true;
			}
		}

		$config = [
			'ratingLevels' => $ratings,
			'statusId' => ! empty( $_GET['status_id'] ) ? absint( $_GET['status_id'] ) : null,
			'replyId' => ! empty( $_GET['reply_id'] ) ? absint( $_GET['reply_id'] ) : null,
			'mode' => $mode,
			'orderingOptions' => $ordering_options,
			'postSubmission' => [
				'editable' => !! \Voxel\get( 'settings.timeline.posts.editable', true ),
				'maxlength' => \Voxel\get( 'settings.timeline.posts.maxlength', 5000 ),
				'gallery' => !! \Voxel\get( 'settings.timeline.posts.images.enabled', true ),
			],
			'replySubmission' => [
				'editable' => !! \Voxel\get( 'settings.timeline.replies.editable', true ),
				'maxlength' => \Voxel\get( 'settings.timeline.replies.maxlength', 2000 ),
				'max_nest_level' => \Voxel\get( 'settings.timeline.replies.max_nest_level', null ),
			],
			'postId' => $post ? $post->get_id() : null,
			'authorId' => $post ? $post->get_author_id() : null,
			'user' => [
				'can_post' => $can_post,
				'name' => $name,
				'avatar' => $avatar,
			],
		];

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/timeline.php' );

		if ( \Voxel\is_edit_mode() ) {
			printf( '<script type="text/javascript">%s</script>', 'window.render_timeline();' );
		}
	}

	public function get_script_depends() {
		return [
			'vx:timeline.js',
		];
	}

	public function get_style_depends() {
		return [
			'vx:social-feed.css',
		];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
