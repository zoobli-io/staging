<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Search_Form extends Base_Widget {

	public function get_name() {
		return 'ts-search-form';
	}

	public function get_title() {
		return __( 'Search form (27)', 'my-listing' );
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
				'label' => __( 'Post types', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$post_types = [];
			foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
				$post_types[ $post_type->get_key() ] = $post_type->get_label();
			}

			$this->add_control(
				'ts_choose_post_types',
				[
					'label' => __( 'Choose post types', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $post_types,
				]
			);

			$this->add_control(
				'cpt_filter_show',
				[
					'label' => __( 'Show custom post type filter', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control( 'ts_on_submit', [
				'label' => __( 'On form submit', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'post-to-feed',
				'label_block' => true,
				'options' => [
					'post-to-feed' => __( 'Post results to widget', 'voxel' ),
					'submit-to-archive' => __( 'Submit to post type archive', 'voxel' ),
					'submit-to-page' => __( 'Submit to page', 'voxel' ),
				],
			] );

			$this->add_control( 'ts_post_to_feed', [
				'label' => __( 'Choose Post Feed widget', 'voxel' ),
				'type' => 'voxel-relation',
				'vx_group' => 'feedToSearch',
				'vx_target' => 'elementor-widget-ts-post-feed',
				'vx_side' => 'left',
				'condition' => [ 'ts_on_submit' => 'post-to-feed' ],
			] );

			$this->add_control( 'ts_post_to_map', [
				'label' => __( 'Choose Map widget', 'voxel' ),
				'type' => 'voxel-relation',
				'vx_group' => 'mapToSearch',
				'vx_target' => 'elementor-widget-ts-map',
				'vx_side' => 'left',
				'condition' => [ 'ts_on_submit' => 'post-to-feed' ],
			] );

			$this->add_control( 'ts_update_url', [
				'label' => __( 'Update URL with search values?', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'your-plugin' ),
				'label_off' => __( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [ 'ts_on_submit' => 'post-to-feed' ],
			] );

			$this->add_control( 'ts_search_on', [
				'label' => __( 'Perform search:', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'submit',
				'options' => [
					'submit' => 'When the search button is clicked',
					'filter_update' => 'When any filter value is updated',
				],
				'condition' => [ 'ts_on_submit' => 'post-to-feed' ],
			] );

			$this->add_control( 'ts_submit_to_page', [
				'label' => __( 'Enter page ID', 'voxel' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'condition' => [ 'ts_on_submit' => 'submit-to-page' ],
			] );

			$this->add_responsive_control(
				'ts_post_type_width',
				[
					'label' => __( 'Width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
					'selectors' => [
						'{{WRAPPER}} .choose-cpt-filter' => 'width: {{SIZE}}%;',
					],
				]
			);



		$this->end_controls_section();

		foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
			$this->start_controls_section( sprintf( 'ts_sf_filters__%s', $post_type->get_key() ), [
				'label' => sprintf( '➡️ %s Filters', $post_type->get_singular_name() ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [ 'ts_choose_post_types' => $post_type->get_key() ],
			] );

			$repeater = new \Elementor\Repeater;

			$filters = [];
			$defaults = [];
			foreach ( $post_type->get_filters() as $filter ) {
				$filters[ $filter->get_key() ] = $filter->get_label();
				$defaults[] = [
					'ts_choose_filter' => $filter->get_key(),
					'ts_filter_width' => 50,
				];
			}

			$repeater->add_control( 'ts_choose_filter', [
				'label' => __( 'Choose filter', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $filters,
			] );

			$repeater->add_responsive_control( 'ts_filter_width', [
				'label' => __( 'Width', 'plugin-domain' ),
				'description' => __( 'Leave empty for auto width', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'min' => 0,
					'max' => 100,
					'step' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}%;',
				],
			] );

			$repeater->add_control( 'ts_default_value', [
				'label' => __( 'Add default value', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			] );

			foreach ( $post_type->get_filters() as $filter ) {
				foreach ( $filter->get_elementor_controls() as $control_key => $control_args ) {
					$control_args['condition'] = [
						'ts_choose_filter' => $filter->get_key(),
					];

					if ( ( $control_args['conditional'] ?? null ) !== false ) {
						$control_args['condition']['ts_default_value'] = 'yes';
					}

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


		$this->start_controls_section(
			'ts_sf_buttons',
			[
				'label' => __( 'Buttons', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'ts_show_search_btn',
				[
					'label' => __( 'Show search button', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'true',
					'options' => [
						'true'  => __( 'Yes', 'plugin-domain' ),
						'false' => __( 'No', 'plugin-domain' ),
					],
				]
			);

			$this->add_control(
				'ts_show_reset_btn',
				[
					'label' => __( 'Show reset button', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'false',
					'options' => [
						'true'  => __( 'Yes', 'plugin-domain' ),
						'false' => __( 'No', 'plugin-domain' ),
					],
				]
			);

			$this->add_responsive_control(
				'ts_search_btn_width',
				[
					'label' => __( 'Width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
					'selectors' => [
						'{{WRAPPER}} .ts-form-group.ts-form-submit' => 'width: {{SIZE}}%;',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_map_feed_switch',
			[
				'label' => __( 'Map/Feed Switcher', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			
			$this->add_control(
				'mf_switcher_desktop',
				[
					'label' => __( 'Enable on desktop', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'switcher_desktop_default',
				[
					'label' => __( 'Visible by default', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'feed',
					'condition' => [ 'mf_switcher_desktop' => 'yes' ],
					'options' => [
						'feed'  => __( 'Feed', 'plugin-domain' ),
						'map' => __( 'Map', 'plugin-domain' ),
					],
				]
			);


			$this->add_control(
				'mf_switcher_tablet',
				[
					'label' => __( 'Enable on tablet', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'switcher_tablet_default',
				[
					'label' => __( 'Visible by default', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'feed',
					'condition' => [ 'mf_switcher_tablet' => 'yes' ],
					'options' => [
						'feed'  => __( 'Feed', 'plugin-domain' ),
						'map' => __( 'Map', 'plugin-domain' ),
					],
				]
			);

			$this->add_control(
				'mf_switcher_mobile',
				[
					'label' => __( 'Enable on mobile', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'switcher_mobile_default',
				[
					'label' => __( 'Visible by default', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'feed',
					'condition' => [ 'mf_switcher_mobile' => 'yes' ],
					'options' => [
						'feed'  => __( 'Feed', 'plugin-domain' ),
						'map' => __( 'Map', 'plugin-domain' ),
					],
				]
			);


		$this->end_controls_section();


		$this->start_controls_section(
			'ts_sf_styling_general',
			[
				'label' => __( 'Search form: General', 'plugin-name' ),
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
							'{{WRAPPER}} .ts-filter-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'ts_sf_general_bg',
					[
						'label' => __( 'Background color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-filter-wrapper' => 'background-color: {{VALUE}}',
						],
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
						'default' => [
							'unit' => 'px',
							'size' => 5,
						],
						'selectors' => [
							'{{WRAPPER}} .ts-filter-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'ts_sf_general_shadow',
						'label' => __( 'Box Shadow', 'plugin-domain' ),
						'selector' => '{{WRAPPER}} .ts-filter-wrapper',
					]
				);

				$this->add_control(
					'ts_sf_filter_wrap',
					[
						'label' => __( 'Filter wrap', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_sf_wrap_desktop',
					[
						'label' => __( 'Wrap settings (Desktop)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'wrap',
						'options' => [
							'wrap'  => __( 'Wrap', 'plugin-domain' ),
							'nowrap' => __( 'Nowrap', 'plugin-domain' ),
						],
						'selectors' => [
							'(desktop){{WRAPPER}} .ts-filter-wrapper' => 'flex-wrap: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_sf_wrap_tablet',
					[
						'label' => __( 'Wrap settings (Tablet)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'wrap',
						'options' => [
							'wrap'  => __( 'Wrap', 'plugin-domain' ),
							'nowrap' => __( 'Nowrap', 'plugin-domain' ),
						],
						'selectors' => [
							'(tablet){{WRAPPER}} .ts-filter-wrapper' => 'flex-wrap: {{VALUE}}',
						],
					]
				);

				$this->add_control(
					'ts_sf_wrap_mobile',
					[
						'label' => __( 'Wrap settings (Mobile)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SELECT,
						'default' => 'wrap',
						'options' => [
							'wrap'  => __( 'Wrap', 'plugin-domain' ),
							'nowrap' => __( 'Nowrap', 'plugin-domain' ),
						],
						'selectors' => [
							'(mobile){{WRAPPER}} .ts-filter-wrapper' => 'flex-wrap: {{VALUE}}',
						],
					]
				);

			

				$this->add_control(
					'horizontal_scroll_color',
					[
						'label' => __( 'Scrollbar color', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .ts-filter-wrapper' => '--ts-scroll-color: {{VALUE}}',
						],
					]
				);

				$this->add_responsive_control(
					'ks_nowrap_max_width',
					[
						'label' => __( 'Max filter width', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 100,
								'max' => 500,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						'selectors' => [
							'{{WRAPPER}} .ts-filter' => 'max-width: {{SIZE}}{{UNIT}};',
						],
					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_styling_filters',
			[
				'label' => __( 'Search form: Filters', 'plugin-name' ),
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

					

					$this->add_control(
						'ts_sf_input_lbl',
						[
							'label' => __( 'Label', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_sf_input_label',
						[
							'label' => __( 'Show label', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SWITCHER,
							'return_value' => 'yes',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_input_label_text',
							'label' => __( 'Label typography' ),
							'selector' => '{{WRAPPER}} .ts-form .ts-form-group > label',
						]
					);


					$this->add_responsive_control(
						'ts_sf_input_label_col',
						[
							'label' => __( 'Label color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-form-group > label' => 'color: {{VALUE}}',
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
						'ts_sf_form_group_padding',
						[
							'label' => __( 'Filter margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} form > .elementor-row > .ts-form-group' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
								'{{WRAPPER}} .ts-form .ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
								'{{WRAPPER}} .ts-filter' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-filter',
						]
					);




					$this->add_responsive_control(
						'ts_sf_input_bg',
						[
							'label' => __( 'Filter background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter' => 'background: {{VALUE}}',
							],

						]
					);


					$this->add_responsive_control(
						'ts_sf_input_value_col',
						[
							'label' => __( 'Filter text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_sf_input_input_typo',
							'label' => __( 'Filter typography' ),
							'selector' => '{{WRAPPER}} .ts-form .ts-filter',
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_doube',
						[
							'label' => __( 'Double filter spacing', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-form .ts-double-input .ts-filter:last-child' => 'margin-left: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_input_border',
							'label' => __( 'Filter border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-filter',
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
								'{{WRAPPER}} .ts-form .ts-filter' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_col',
						[
							'label' => __( 'Filter icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-filter i' => 'color: {{VALUE}}',
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

					$this->add_responsive_control(
						'ts_sf_input_value_col_h',
						[
							'label' => __( 'Filter text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter:hover .ts-filter-text' => 'color: {{VALUE}}',
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

					$this->add_responsive_control(
						'ts_sf_input_icon_col_h',
						[
							'label' => __( 'Filter icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-filter:hover i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow_hover',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-filter:hover',
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
							'selector' => '{{WRAPPER}} .ts-filter.ts-filled',
						]
					);

					$this->add_control(
						'ts_sf_input_background_filled',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter.ts-filled' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_value_col_filled',
						[
							'label' => __( 'Filter text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-filter.ts-filled .ts-filter-text' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_input_icon_col_filled',
						[
							'label' => __( 'Filter icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-filter.ts-filled i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_input_border_filled',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form .ts-filter.ts-filled' => 'border-color: {{VALUE}}',
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
								'{{WRAPPER}} .ts-form .ts-filter.ts-filled' => 'border-width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_sf_input_shadow_active',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-filter.ts-filled',
						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_styling_buttons',
			[
				'label' => __( 'Search form: Submit & Reset button', 'plugin-name' ),
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

					$this->add_control(
						'ts_sf_form_button_general',
						[
							'label' => __( 'General', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_sf_submit_double_space',
						[
							'label' => __( 'Double button spacing', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-form-submit .ts-btn:nth-child(2)' => 'margin-left: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_sf_form_btn_icon',
						[
							'label' => __( 'Search button icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-search',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_control(
						'ts_sf_form_btn_reset_icon',
						[
							'label' => __( 'Reset button icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-redo-alt',
								'library' => 'la-solid',
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
							'default' => [
								'unit' => 'px',
								'size' => 24,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_sf_search_button',
						[
							'label' => __( 'Search button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_submit_btn_typo',
							'label' => __( 'Typography', 'plugin-domain' ),
							'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
							'selector' => '{{WRAPPER}} .ts-form-submit .ts-btn-2',
						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_c',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-2' => 'color: {{VALUE}}',
							],

						]
					);

					


					$this->add_responsive_control(
						'ts_sf_form_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-2' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_height',
						[
							'label' => __( 'Button Height', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-form-submit .ts-btn-2' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_search_padding',
						[
							'label' => __( 'Button padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_search_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form-submit .ts-btn-2',
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
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-2' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_submit_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form-submit .ts-btn-2',
						]
					);

					$this->add_responsive_control(
						'ts_submit_ico_pad',
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
								'{{WRAPPER}} .ts-form-submit .ts-btn-2 i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_sf_reset_btn',
						[
							'label' => __( 'Reset button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_reset_btn_typo',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form-submit .ts-btn-1',
						]
					);

					$this->add_responsive_control(
						'ts_sf_reset_btn_c',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-1' => 'color: {{VALUE}}',
							],

						]
					);

					


					$this->add_responsive_control(
						'ts_sf_reset_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-1' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_sf_reset_btn_height',
						[
							'label' => __( 'Button Height', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-form-submit .ts-btn-1' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_sf_reset_padding',
						[
							'label' => __( 'Button padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_reset_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form-submit .ts-btn-1',
						]
					);

					$this->add_responsive_control(
						'ts_sf_reset_btn_radius',
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
								'{{WRAPPER}} .ts-form-submit .ts-btn-1' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_reset_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-1',
						]
					);

					$this->add_responsive_control(
						'ts_reset_ico_pad',
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
								'{{WRAPPER}} .ts-form-submit .ts-btn-1 i' => 'padding-right: {{SIZE}}{{UNIT}};',
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


					$this->add_control(
						'ts_sf_form_btn_c_h',
						[
							'label' => __( 'Search text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-2:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_form_btn_bg_h',
						[
							'label' => __( 'Search background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-2:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_form_btn_border_h',
						[
							'label' => __( 'Search border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-2:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_submit_shadow_hover',
							'label' => __( 'Search box shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form-submit .ts-btn-2:hover',
						]
					);


					$this->add_control(
						'ts_sf_form_btn_c_reset_h',
						[
							'label' => __( 'Reset text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-1:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_form_btn_bg_reset_h',
						[
							'label' => __( 'Reset background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-1:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_reset_btn_border_h',
						[
							'label' => __( 'Reset border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-form-submit .ts-btn-1:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_reset_shadow_hover',
							'label' => __( 'Reset box shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-form-submit .ts-btn-1:hover',
						]
					);



				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();



		

		$this->start_controls_section(
			'ts_feed_switcher',
			[
				'label' => __( 'Map/feed switcher', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_fswitch_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_fswitch_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);


					
					$this->add_control(
						'ts_freset_container',
						[
							'label' => __( 'Container', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'm_freset_spacing',
						[
							'label' => __( 'Bottom', 'plugin-domain' ),
							'description' => __( 'Distance from bottom of the screen', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-switcher-btn' => 'bottom: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'm_freset_margin',
						[
							'label' => __( 'Side margin', 'plugin-domain' ),
							'description' => __( 'Distance from left/right edges of the screen', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-switcher-btn' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_freset_justify',
						[
							'label' => __( 'Justify', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'options' => [
								'flex-start'  => __( 'Left', 'plugin-domain' ),
								'center' => __( 'Center', 'plugin-domain' ),
								'flex-end' => __( 'Right', 'plugin-domain' ),
							],

							'selectors' => [
								'{{WRAPPER}} .ts-switcher-btn' => 'justify-content: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_freset_button',
						[
							'label' => __( 'Button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);
					


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_fswitch_btn_typo',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-switcher-btn .ts-btn',
						]
					);

					$this->add_responsive_control(
						'ts_fswitch_btn_c',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-switcher-btn .ts-btn' => 'color: {{VALUE}}',
							],

						]
					);

					


					$this->add_responsive_control(
						'ts_fswitch_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-switcher-btn .ts-btn' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'ts_fswitch_btn_height',
						[
							'label' => __( 'Button Height', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-switcher-btn .ts-btn' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_fswitch_btn_padding',
						[
							'label' => __( 'Button padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-switcher-btn .ts-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_fswitch_btn_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-switcher-btn .ts-btn',
						]
					);

					$this->add_responsive_control(
						'ts_fswitch_btn_btn_radius',
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
								'{{WRAPPER}} .ts-switcher-btn .ts-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_fswitch_btn_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}}  .ts-switcher-btn .ts-btn',
						]
					);

					$this->add_responsive_control(
						'ts_fswitch_btn_i_pad',
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
								'{{WRAPPER}}  .ts-switcher-btn .ts-btn i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'fswitch_icon_size',
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
								'{{WRAPPER}} .ts-switcher-btn .ts-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_fswitch_btn_i',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-switcher-btn .ts-btn i' => 'color: {{VALUE}}',
							],

						]
					);

					




				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_fswitch_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);


					$this->add_control(
						'ts_fswitch_btn_c_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-switcher-btn .ts-btn:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_fswitch_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-switcher-btn .ts-btn:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_fswitch_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}}  .ts-switcher-btn .ts-btn:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_fswitch_btn_icon_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}}  .ts-switcher-btn .ts-btn:hover i' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_fswitch_btn_shadow_h',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}}  .ts-switcher-btn .ts-btn:hover',
						]
					);



				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render( $instance = [] ) {
		$post_types = [];
		$default_post_type = $this->_get_default_post_type();
		$post_type_config = [];

		foreach ( (array) $this->get_settings_for_display('ts_choose_post_types') as $post_type_key ) {
			$post_type = \Voxel\Post_Type::get( $post_type_key );
			if ( ! $post_type ) {
				continue;
			}

			$post_types[] = $post_type;
			$post_type_config[ $post_type->get_key() ] = $this->_get_post_type_config( $post_type );
		}

		$switchable_desktop = $this->get_settings( 'mf_switcher_desktop' ) === 'yes';
		$desktop_default = $this->get_settings( 'switcher_desktop_default' );
		$switchable_tablet = $this->get_settings( 'mf_switcher_tablet' ) === 'yes';
		$tablet_default = $this->get_settings( 'switcher_tablet_default' );
		$switchable_mobile = $this->get_settings( 'mf_switcher_mobile' ) === 'yes';
		$mobile_default = $this->get_settings( 'switcher_mobile_default' );

		$general_config = [
			'showLabels' => $this->get_settings_for_display('ts_sf_input_label') === 'yes',
			'defaultType' => $default_post_type ? $default_post_type->get_key() : null,
			'onSubmit' => [],
			'searchOn' => \Voxel\from_list( $this->get_settings_for_display('ts_search_on'), [ 'submit', 'filter_update' ], 'submit' ),
		];

		if ( $this->get_settings_for_display( 'ts_on_submit' ) === 'submit-to-archive' ) {
			$general_config['onSubmit'] = [
				'action' => 'submit-to-archive',
			];
		} elseif ( $this->get_settings_for_display( 'ts_on_submit' ) === 'submit-to-page' ) {
			$page_id = $this->get_settings_for_display('ts_submit_to_page');
			$general_config['onSubmit'] = [
				'action' => 'submit-to-page',
				'pageId' => $page_id,
				'pageLink' => get_permalink( $page_id ),
			];
		} else {
			$post_feed = \Voxel\get_related_widget( $this, $this->_get_template_id(), 'feedToSearch', 'left' );
			$map = \Voxel\get_related_widget( $this, $this->_get_template_id(), 'mapToSearch', 'left' );
			// dump($post_feed);

			$general_config['onSubmit'] = [
				'action' => 'post-to-feed',
				'postFeedId' => $post_feed['id'] ?? null,
				'mapId' => $map['id'] ?? null,
				'updateUrl' => $this->_update_url(),
			];
		}

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/search-form.php' );

		if ( \Voxel\is_edit_mode() ) {
			printf( '<script type="text/javascript">%s</script>', 'window.render_search_form();' );
		}
	}

	public function get_script_depends() {
		return [
			'vx:search-form.js',
		];
	}

	// public function get_style_depends() {
	// 	return [
	// 		'vx:search-form.css',
	// 	];
	// }

	public function _get_post_type_config( $post_type ) {
		$update_url = $this->_update_url();
		$has_url_query = $this->_has_url_query();

		$config = [
			'key' => $post_type->get_key(),
			'label' => $post_type->get_label(),
			'icon' => \Voxel\get_icon_markup( $post_type->get_icon() ),
			'archive' => $post_type->get_archive_link(),
			'filters' => [],
		];

		$filter_list = (array) $this->get_settings_for_display( sprintf( 'ts_filter_list__%s', $post_type->get_key() ) );
		foreach ( $filter_list as $filter_config ) {
			if ( $filter = $post_type->get_filter( $filter_config['ts_choose_filter'] ?? '' ) ) {
				$controls = [];
				foreach ( array_keys( $filter->get_elementor_controls() ) as $control_key ) {
					$controls[ $control_key ] = $filter_config[ sprintf( '%s:%s', $filter->get_key(), $control_key ) ] ?? null;
				}
				$filter->set_elementor_config( $controls );

				$filter_value = null;
				if ( $update_url ) {
					$filter_value = $_GET[ $filter->get_key() ] ?? null;
				}

				$fallback_value = null;
				if ( ( ! $update_url || ! $has_url_query ) && ( $filter_config['ts_default_value'] ?? null ) === 'yes' ) {
					$fallback_value = $filter->get_default_value_from_elementor( $controls );
				}

				$filter->set_value( $filter_value ?? $fallback_value );
				$config['filters'][ $filter->get_key() ] = $filter->get_frontend_config();
			}
		}

		return $config;
	}

	public function _ssr_filters() {
		$post_type = $this->_get_default_post_type();
		if ( ! $post_type ) {
			return;
		}

		$show_labels = $this->get_settings_for_display('ts_sf_input_label') === 'yes';

		if ( $this->get_settings_for_display('cpt_filter_show') === 'yes' ) { ?>
			<div v-if="false" class="ts-form-group elementor-column choose-cpt-filter">
				<?php if ( $show_labels ): ?>
					<label><?= _x( 'Post type', 'search form widget', 'voxel' ) ?></label>
				<?php endif ?>
				<div class="ts-filter ts-popup-target ts-filled">
					<span><?= \Voxel\get_icon_markup( $post_type->get_icon() ) ?></span>
					<div class="ts-filter-text"><?= $post_type->get_label() ?></div>
				</div>
			</div>
		<?php }

		$filter_list = (array) $this->get_settings_for_display(
			sprintf( 'ts_filter_list__%s', $post_type->get_key() )
		);

		foreach ( $filter_list as $filter_config ) {
			$filter = $post_type->get_filter( $filter_config['ts_choose_filter'] ?? '' );
			if ( $filter ) { ?>
				<?php $filter->ssr( [
					'show_labels' => $show_labels,
					'wrapper_class' => 'ts-form-group elementor-column elementor-repeater-item-'.$filter_config['_id'],
				] ) ?>
			<?php }
		}
	}

	public function _get_default_post_type() {
		$chosen_types = (array) $this->get_settings_for_display('ts_choose_post_types');

		if ( ! $this->_update_url() ) {
			$post_type_key = ! empty( $chosen_types ) ? $chosen_types[0] : 'post';
			return \Voxel\Post_Type::get( $post_type_key );
		}

		$post_type_key = isset( $_GET['type'] ) && in_array( $_GET['type'], $chosen_types )
			? sanitize_text_field( $_GET['type'] )
			: ( ! empty( $chosen_types ) ? $chosen_types[0] : 'post' );

		return \Voxel\Post_Type::get( $post_type_key );
	}

	public function _has_url_query() {
		$chosen_types = (array) $this->get_settings_for_display('ts_choose_post_types');
		return isset( $_GET['type'] ) && in_array( $_GET['type'], $chosen_types );
	}

	public function _update_url() {
		return $this->get_settings_for_display('ts_update_url') === 'yes';
	}
}
