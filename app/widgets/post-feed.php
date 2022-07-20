<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Feed extends Base_Widget {

	public function get_name() {
		return 'ts-post-feed';
	}

	public function get_title() {
		return __( '27 Post Feed', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'post_feed_settings', [
			'label' => __( 'Post Feed settings', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$post_types = [];
		foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
			$post_types[ $post_type->get_key() ] = $post_type->get_label();
		}

		$this->add_control( 'ts_source', [
			'label' => __( 'Data source', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'search-form',
			'label_block' => true,
			'options' => [
				'search-form' => __( 'Search Form widget', 'voxel' ),
				'search-filters' => __( 'Filters', 'voxel' ),
				'manual' => __( 'Manual selection', 'voxel' ),
			],
		] );

		$this->add_control( 'cpt_search_form', [
			'label' => __( 'Link to search form', 'voxel' ),
			'type' => 'voxel-relation',
			'vx_group' => 'feedToSearch',
			'vx_target' => 'elementor-widget-ts-search-form',
			'vx_side' => 'right',
			'condition' => [ 'ts_source' => 'search-form' ],
		] );

		$this->add_control( 'ts_pagination', [
			'label' => __( 'Pagination', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'load_more',
			'options' => [
				'load_more' => __( 'Load more button', 'voxel' ),
				'prev_next' => __( 'Prev/Next buttons', 'voxel' ),
				'none' => __( 'None', 'voxel' ),
			],
			'condition' => [ 'ts_source' => 'search-form' ],
		] );

		$this->add_control( 'ts_posts_per_page', [
			'label' => __( 'Posts per page', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => 10,
			'min' => 0,
			'condition' => [ 'ts_source' => 'search-form' ],
		] );

		$post_types = [];
		foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
			$post_types[ $post_type->get_key() ] = $post_type->get_label();
		}

		$this->add_control( 'ts_manual_post_type', [
			'label' => __( 'Choose post type', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'options' => $post_types,
			'condition' => [ 'ts_source' => 'manual' ],
		] );


		$this->add_control( 'ts_manual_posts', [
			'label' => __( 'Choose posts', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::REPEATER,
			'condition' => [ 'ts_source' => 'manual' ],
			'fields' => [ [
				'name' => 'post_id',
				'label' => __( 'Post ID', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
			] ],
		] );

		$this->add_control( 'ts_choose_post_type', [
			'label' => __( 'Choose post type', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'options' => $post_types,
			'condition' => [ 'ts_source' => 'search-filters' ],
		] );

		$this->add_control( 'ts_post_number', [
			'label' => __( 'Number of posts to load', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'default' => 10,
			'min' => 0,
			'condition' => [ 'ts_source' => 'search-filters' ],
		] );

		$this->end_controls_section();
		$this->start_controls_section( 'post_feed_layout', [
			'label' => __( 'Layout', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

			$this->add_control(
				'ts_wrap_feed',
				[
					'label' => __( 'Wrapping', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'ts-feed-grid-default',
					'options' => [
						'ts-feed-grid-default'  => __( 'Default', 'plugin-domain' ),
						'ts-feed-nowrap' => __( 'Nowrap (Horizontal scroll)', 'plugin-domain' ),
					],
				]
			);

			$this->add_responsive_control(
				'ts_nowrap_item_width',
				[
					'label' => __( 'Item width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 50,
							'max' => 500,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .post-feed-grid > div' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [ 'ts_wrap_feed' => 'ts-feed-nowrap' ]
				]
			);

		
			


			$this->add_responsive_control(
				'ts_feed_column_no',
				[
					'label' => __( 'Number of columns', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 6,
					'step' => 1,
					'default' => 3,
					'selectors' => [
						'{{WRAPPER}} .post-feed-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
					],
					'condition' => [ 'ts_wrap_feed' => 'ts-feed-grid-default' ]
				]
			);




			$this->add_responsive_control(
				'ts_feed_col_gap',
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

					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .post-feed-grid' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				
				]
			);

			$this->add_responsive_control(
				'ts_scroll_padding',
				[
					'label' => __( 'Scroll padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],

					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .post-feed-grid' => 'padding: 0 {{SIZE}}{{UNIT}}; scroll-padding: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .post-feed-grid > div:last-of-type' => 'margin-right: {{SIZE}}{{UNIT}}',
					],
					'condition' => [ 'ts_wrap_feed' => 'ts-feed-nowrap' ]
				]
			);



			



			
		$this->end_controls_section();

		/*
		==========
		Feed: Loading
		==========
		*/

		$this->start_controls_section(
			'ts_feed_loading',
			[
				'label' => __( 'Feed: Loading', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'ts_loading_style',
				[
					'label' => __( 'Loading style', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'vx-opacity',
					'options' => [
						'vx-none'  => __( 'None', 'plugin-domain' ),
						'vx-opacity' => __( 'Opacity', 'plugin-domain' ),
						'vx-skeleton' => __( 'Skeleton', 'plugin-domain' ),
					],
				]
			);

			$this->add_control(
				'vx_opacity_value',
				[
					'label' => __( 'Opacity', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'condition' => [ 'ts_loading_style' => 'vx-opacity' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => 0.01,
						],
					],

					
					'selectors' => [
						'{{WRAPPER}}.vx-loading .vx-opacity' => 'opacity: {{SIZE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'vx_skeleton_bg',
					'label' => esc_html__( 'Background', 'plugin-name' ),
					'types' => [ 'classic', 'gradient', 'video' ],
					'condition' => [ 'ts_loading_style' => 'vx-skeleton' ],
					'selector' => '{{WRAPPER}}.vx-loading .vx-skeleton .ts-preview',
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
				'label' => __( 'Feed: No results', 'plugin-name' ),
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
						'{{WRAPPER}} .ts-no-posts > i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ts_nopost_ico_col',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-no-posts > i' => 'color: {{VALUE}}',
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
						'{{WRAPPER}} .ts-no-posts > p' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_no_results_ico',
				[
					'label' => __( 'Choose icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-search',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_no_results_reset_ico',
				[
					'label' => __( 'Reset icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-redo-alt',
						'library' => 'la-solid',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_feed_reset',
			[
				'label' => __( 'No results: Reset button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_freset_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_freset_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_freset_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-feed-reset',
						]
					);

					$this->add_control(
						'ts_freset_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px'],
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_freset_btn_height',
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
								'{{WRAPPER}} .ts-feed-reset' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_freset_btn_width',
						[
							'label' => __( 'Width', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%'],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 500,
									'step' => 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_freset_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-feed-reset',
						]
					);

					$this->add_responsive_control(
						'ts_freset_btn_radius',
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
								'{{WRAPPER}} .ts-feed-reset' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'ts_freset_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_freset_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset' => 'background: {{VALUE}}',
							],

						]
					);



					$this->add_responsive_control(
						'ts_freset_btn_icon_size',
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
								'{{WRAPPER}} .ts-feed-reset i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_freset_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_freset_icon_spacing',
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
								'{{WRAPPER}} .ts-feed-reset i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);



				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'ts_freset_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_responsive_control(
						'ts_freset_btn_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset:hover' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_freset_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_freset_btn_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-feed-reset:hover i' => 'color: {{VALUE}}',
							],

						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_feed_pag',
			[
				'label' => __( 'Pagination: Buttons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_fpag_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_fpag_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);



					$this->add_responsive_control(
						'ts_fpag_top',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .feed-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_fpag_justify',
						[
							'label' => __( 'Justify', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'options' => [
								'flex-start'  => __( 'Left', 'plugin-domain' ),
								'center' => __( 'Center', 'plugin-domain' ),
								'flex-end' => __( 'Right', 'plugin-domain' ),
								'space-between' => __( 'Space between', 'plugin-domain' ),
								'space-around' => __( 'Space around', 'plugin-domain' ),
							],

							'selectors' => [
								'{{WRAPPER}} .feed-pagination' => 'justify-content: {{VALUE}}',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_fpag_btn_typo',
							'label' => __( 'Button typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .feed-pagination .ts-btn-1',
						]
					);

					$this->add_control(
						'ts_fpag_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px'],
							'selectors' => [
								'{{WRAPPER}}  .feed-pagination .ts-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_fpag_btn_height',
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
								'{{WRAPPER}} .feed-pagination .ts-btn-1' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_fpag_btn_width',
						[
							'label' => __( 'Width', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', '%'],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 500,
									'step' => 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .feed-pagination .ts-btn-1' => 'width: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_fpag_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .feed-pagination .ts-btn-1',
						]
					);

					$this->add_responsive_control(
						'ts_fpag_btn_radius',
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
								'{{WRAPPER}} .feed-pagination .ts-btn-1' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_responsive_control(
						'ts_fpag_btn_c',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .feed-pagination .ts-btn-1' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_fpag_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .feed-pagination .ts-btn-1' => 'background: {{VALUE}}',
							],

						]
					);



					$this->add_responsive_control(
						'ts_fpag_btn_icon_size',
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
								'{{WRAPPER}} .feed-pagination .ts-btn-1 i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_fpag_btn_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .feed-pagination .ts-btn-1 i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_fpag_icon_spacing',
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
								'{{WRAPPER}} .feed-pagination .ts-btn-1:nth-child(1) i' => 'padding-right: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .feed-pagination .ts-btn-1:nth-child(2) i' => 'padding-left: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'fpag_next_icon',
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
						'fpag_prev_icon',
						[
							'label' => __( 'Prev icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-angle-left',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'fpag_load_icon',
						[
							'label' => __( 'Load more icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-sync',
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
			'ts_form_nav',
			[
				'label' => __( 'Slider: Next/Prev buttons', 'plugin-name' ),
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



					$this->add_responsive_control(
						'ts_fnav_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .post-feed-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);



					$this->add_control(
						'ts_fnav_justify',
						[
							'label' => __( 'Justify', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'options' => [
								'flex-start'  => __( 'Left', 'plugin-domain' ),
								'center' => __( 'Center', 'plugin-domain' ),
								'flex-end' => __( 'Right', 'plugin-domain' ),
								'space-between' => __( 'Space between', 'plugin-domain' ),
								'space-around' => __( 'Space around', 'plugin-domain' ),
							],

							'selectors' => [
								'{{WRAPPER}} .post-feed-nav' => 'justify-content: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_fnav_margin',
						[
							'label' => __( 'Spacing between buttons', 'plugin-domain' ),
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
								'{{WRAPPER}} .post-feed-nav  li:first-child' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_fnav_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .post-feed-nav .ts-icon-btn'
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
								'{{WRAPPER}} .post-feed-nav .ts-icon-btn' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_fnav_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .post-feed-nav .ts-icon-btn'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_fnav_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .post-feed-nav .ts-icon-btn',
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
								'{{WRAPPER}} .post-feed-nav  .ts-icon-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
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
								'{{WRAPPER}} .post-feed-nav .ts-icon-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'feed_nav_icons',
						[
							'label' => __( 'Icons', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'feed_next_icon',
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
						'feed_prev_icon',
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

		



		foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
			$this->start_controls_section( sprintf( 'ts_sf_filters__%s', $post_type->get_key() ), [
				'label' => sprintf( '➡️ %s Filters', $post_type->get_singular_name() ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [ 'ts_choose_post_type' => $post_type->get_key() ],
			] );

			$repeater = new \Elementor\Repeater;

			$filters = [];
			$defaults = [];
			foreach ( $post_type->get_filters() as $filter ) {
				$filters[ $filter->get_key() ] = $filter->get_label();
				$defaults[] = [
					'ts_choose_filter' => $filter->get_key(),
				];
			}

			$repeater->add_control( 'ts_choose_filter', [
				'label' => __( 'Choose filter', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $filters,
			] );

			foreach ( $post_type->get_filters() as $filter ) {
				foreach ( $filter->get_elementor_controls() as $control_key => $control_args ) {
					if ( ( $control_args['conditional'] ?? null ) === false ) {
						continue;
					}

					$control_args['condition'] = [
						'ts_choose_filter' => $filter->get_key(),
					];

					$repeater->add_control( $filter->get_key().':'.$control_key, $control_args );
				}
			}

			$this->add_control( sprintf( 'ts_filter_list__%s', $post_type->get_key() ), [
				'label' => __( 'Add filters', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => $defaults,
				'title_field' => '{{ts_choose_filter}}',
				'_disable_loop' => true,
			] );

			$this->end_controls_section();
		}

		
	}

	protected function render( $instance = [] ) {
		if ( $this->get_settings('ts_source') === 'search-filters' ) {
			$post_type = \Voxel\Post_Type::get( $this->get_settings( 'ts_choose_post_type' ) );
			if ( ! $post_type ) {
				return;
			}

			$args = [];
			$args['type'] = $post_type->get_key();

			$filter_list = (array) $this->get_settings_for_display(
				sprintf( 'ts_filter_list__%s', $post_type->get_key() )
			);

			foreach ( $filter_list as $filter_config ) {
				if ( $filter = $post_type->get_filter( $filter_config['ts_choose_filter'] ?? null ) ) {
					$controls = [];
					foreach ( array_keys( $filter->get_elementor_controls() ) as $control_key ) {
						$controls[ $control_key ] = $filter_config[ sprintf( '%s:%s', $filter->get_key(), $control_key ) ] ?? null;
					}

					$filter->set_elementor_config( $controls );
					$args[ $filter->get_key() ] = $filter->get_default_value_from_elementor( $controls );
				}
			}

			$results = \Voxel\get_search_results( $args, [
				'limit' => absint( $this->get_settings_for_display( 'ts_post_number' ) ),
			] );
		} elseif ( $this->get_settings('ts_source') === 'manual' ) {
			$post_type = \Voxel\Post_Type::get( $this->get_settings( 'ts_manual_post_type' ) );
			if ( ! $post_type ) {
				return;
			}

			$args = [];
			$args['type'] = $post_type->get_key();

			$results = \Voxel\get_search_results( [
				'type' => $post_type->get_key(),
			], [
				'ids' => array_column( (array) $this->get_settings_for_display('ts_manual_posts'), 'post_id' ),
			] );

		} else {
			$search_form = \Voxel\get_related_widget( $this, $this->_get_template_id(), 'feedToSearch', 'right' );
			if ( ! $search_form ) {
				return;
			}

			$widget = new \Voxel\Widgets\Search_Form( $search_form, [] );
			$post_type = $widget->_get_default_post_type();
			if ( ! $post_type ) {
				return;
			}

			$config = $widget->_get_post_type_config( $post_type );
			$args = [];
			$args['type'] = $post_type->get_key();
			foreach ( $config['filters'] as $filter ) {
				if ( $filter['value'] !== null ) {
					$args[ $filter['key'] ] = $filter['value'];
				}
			}

			if ( $widget->_update_url() ) {
				$args['pg'] = $_GET['pg'] ?? null;
			}

			$posts_per_page = absint( $this->get_settings_for_display( 'ts_posts_per_page' ) );
			$results = \Voxel\get_search_results( $args, [
				'limit' => $posts_per_page,
			] );

			$this->add_render_attribute( '_wrapper', 'data-per-page', $posts_per_page );

			$switchable_desktop = $widget->get_settings( 'mf_switcher_desktop' ) === 'yes';
			$hidden_desktop = $widget->get_settings( 'switcher_desktop_default' ) === 'map';
			$switchable_tablet = $widget->get_settings( 'mf_switcher_tablet' ) === 'yes';
			$hidden_tablet = $widget->get_settings( 'switcher_tablet_default' ) === 'map';
			$switchable_mobile = $widget->get_settings( 'mf_switcher_mobile' ) === 'yes';
			$hidden_mobile = $widget->get_settings( 'switcher_mobile_default' ) === 'map';

			$this->add_render_attribute( '_wrapper', 'class', [
				$switchable_desktop && $hidden_desktop ? 'vx-hidden-desktop' : '',
				$switchable_tablet && $hidden_tablet ? 'vx-hidden-tablet' : '',
				$switchable_mobile && $hidden_mobile ? 'vx-hidden-mobile' : '',
			] );
		}

		$pagination = $this->get_settings_for_display( 'ts_pagination' );
		$this->add_render_attribute( '_wrapper', 'data-paginate', esc_attr( $pagination ) );

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/post-feed.php' );
	}

	public function get_style_depends() {
		return [ 'vx:post-feed.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
