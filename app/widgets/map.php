<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Map extends Base_Widget {

	public function get_name() {
		return 'ts-map';
	}

	public function get_title() {
		return __( 'Map (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'post_feed_settings', [
			'label' => __( 'Map settings', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'ts_source', [
			'label' => __( 'Markers', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'search-form',
			'label_block' => true,
			'options' => [
				'search-form' => __( 'Get markers from Search Form widget', 'voxel' ),
				'current-post' => __( 'Show marker of current post', 'voxel' ),
			],
		] );

		$this->add_control( 'cpt_search_form', [
			'label' => __( 'Link to search form', 'voxel' ),
			'type' => 'voxel-relation',
			'vx_group' => 'mapToSearch',
			'vx_target' => 'elementor-widget-ts-search-form',
			'vx_side' => 'right',
			'condition' => [ 'ts_source' => 'search-form' ],
		] );

		$this->add_control( 'ts_drag_search', [
			'label' => __( 'Trigger search on map drag', 'voxel' ),
			'description' => __( 'If enabled, dragging the map will trigger a search for posts within the visible map bounds.', 'voxel' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => 'yes',
			'label_on' => __( 'Yes', 'my-listing' ),
			'label_off' => __( 'No', 'my-listing' ),
			'return_value' => 'yes',
			'condition' => [ 'ts_source' => 'search-form' ],
		] );

		$this->add_control( 'ts_drag_search_default', [
			'label' => __( 'Map drag checkbox default state', 'voxel' ),
			'description' => __( 'If enabled, dragging the map will trigger a search for posts within the visible map bounds.', 'voxel' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'unchecked',
			'options' => [
				'checked' => 'Checked',
				'unchecked' => 'Unchecked',
			],
			'condition' => [ 'ts_source' => 'search-form', 'ts_drag_search' => 'yes' ],
		] );

		$this->add_responsive_control(
			'ts_map_height',
			[
				'label' => __( 'Height', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1200,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 400,
				],
				'selectors' => [
					'{{WRAPPER}} .ts-map' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'enable_calc_height',
			[
				'label' => __( 'Calculate height?', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no'
			]
		);

		$this->add_responsive_control(
			'map_calc_height',
			[
				'label' => esc_html__( 'Calculation', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'calc()', 'plugin-name' ),
				'description' => __( 'Use CSS calc() to calculate height e.g calc(100vh - 215px)', 'plugin-domain' ),
				'selectors' => [
					'{{WRAPPER}} .ts-map' => 'height: {{VALUE}};',
				],
				'condition' => [ 'enable_calc_height' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'pg_radius',
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
					'{{WRAPPER}} .ts-map' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section( 'ts_map_defaults', [
			'label' => __( 'Default map location', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'ts_default_lat', [
			'label'   => _x( 'Default latitude', 'Explore map', 'my-listing' ),
			'type'    => \Elementor\Controls_Manager::NUMBER,
			'default' => 51.492,
			'min'     => -90,
			'max'     => 90,
			'classes' => 'ts-half-width',
			'label_block' => true,
		] );

		$this->add_control( 'ts_default_lng', [
			'label'   => _x( 'Default longitude', 'Explore map', 'my-listing' ),
			'type'    => \Elementor\Controls_Manager::NUMBER,
			'default' => -0.130,
			'min'     => -180,
			'max'     => 180,
			'classes' => 'ts-half-width',
			'label_block' => true,
		] );

		$this->add_control( 'ts_default_zoom', [
			'label'   => _x( 'Default zoom level', 'Explore map', 'my-listing' ),
			'type'    => \Elementor\Controls_Manager::NUMBER,
			'default' => 11,
			'min'     => 0,
			'max'     => 30,
		] );

		$this->add_control( 'ts_min_zoom', [
			'label'   => _x( 'Minimum zoom level', 'Explore map', 'my-listing' ),
			'type'    => \Elementor\Controls_Manager::NUMBER,
			'default' => 2,
			'min'     => 0,
			'max'     => 30,
			'classes' => 'ts-half-width',
			'label_block' => true,
		] );

		$this->add_control( 'ts_max_zoom', [
			'label'   => _x( 'Maximum zoom level', 'Explore map', 'my-listing' ),
			'type'    => \Elementor\Controls_Manager::NUMBER,
			'default' => 18,
			'min'     => 0,
			'max'     => 30,
			'classes' => 'ts-half-width',
			'label_block' => true,
		] );

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_map_markers',
			[
				'label' => __( 'Map: Marker styling', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'm_marker_width',
				[
					'label' => __( 'Width', 'plugin-domain' ),
					'description' => __( 'Leave empty for auto width', 'plugin-domain' ),
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
						'{{WRAPPER}} .map-marker' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'm_marker_height',
				[
					'label' => __( 'Height', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'description' => __( 'Leave empty for auto height', 'plugin-domain' ),
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .map-marker' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'm_marker_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'selectors' => [
						'{{WRAPPER}} .map-marker' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'm_marker_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .map-marker' => 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'm_marker_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .map-marker',
				]
			);

			$this->add_responsive_control(
				'm_marker_radius',
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
						'{{WRAPPER}} .map-marker' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'm_marker_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .map-marker',
				]
			);

			

			$this->add_control(
				'm_ico_marker',
				[
					'label' => __( 'Icon marker', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'm_ico_size',
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
						'{{WRAPPER}} .map-marker.marker-type-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			

			$this->add_responsive_control(
				'm_ico_col',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .map-marker.marker-type-icon i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'm_text_marker',
				[
					'label' => __( 'Text marker', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'm_text_marker_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .map-marker.marker-type-text',
				]
			);

			$this->add_responsive_control(
				'm_text_marker_col',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .map-marker.marker-type-text' => 'color: {{VALUE}}',
					],

				]
			);





		$this->end_controls_section();

		$this->start_controls_section(
			'ts_map_preview',
			[
				'label' => __( 'Map: View card box', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


			$this->add_responsive_control(
				'm_mp_width',
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
						'{{WRAPPER}} .ts-marker .gm-style-iw-d' => 'width: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_responsive_control(
				'm_mp_max_height',
				[
					'label' => __( 'Max eight', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'description' => __( 'Leave empty for auto height', 'plugin-domain' ),
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ts-marker .gm-style-iw-d' => 'max-height: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_control(
				'm_mp_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'selectors' => [
						'{{WRAPPER}} .ts-marker .gm-style-iw-d' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'm_mp_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-marker .gm-style-iw-c' => 'background-color: {{VALUE}} !important;',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'm_mp_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-marker .gm-style-iw-c',
				]
			);

			$this->add_responsive_control(
				'm_mp_radius',
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
						'{{WRAPPER}} .ts-marker .gm-style-iw-c' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'm_mp_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-marker .gm-style-iw-c',
				]
			);

		$this->end_controls_section();


		$this->start_controls_section(
			'ts_move_search',
			[
				'label' => __( 'Map: Search as I move the map', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


			$this->add_responsive_control(
				'm_move_spacing',
				[
					'label' => __( 'Margin', 'plugin-domain' ),
					'description' => __( 'Leave empty for auto width', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-map-drag' => 'padding: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_move_justify',
				[
					'label' => __( 'Justify', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'flex-start'  => __( 'Left', 'plugin-domain' ),
						'center' => __( 'Center', 'plugin-domain' ),
						'flex-end' => __( 'Right', 'plugin-domain' ),
					],

					'selectors' => [
						'{{WRAPPER}} .ts-map-drag' => 'justify-content: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'm_move_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'selectors' => [
						'{{WRAPPER}} .ts-map-drag .switch-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'm_move_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-map-drag .switch-slider' => 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'm_move_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-map-drag .switch-slider',
				]
			);

			$this->add_responsive_control(
				'm_move_radius',
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
						'{{WRAPPER}} .ts-map-drag .switch-slider' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'm_move_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-map-drag .switch-slider',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'm_move_text',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-map-drag .switch-slider > p',
				]
			);

			$this->add_responsive_control(
				'm_move_text_color',
				[
					'label' => __( 'Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-map-drag .switch-slider > p' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'm_move_text_margin',
				[
					'label' => __( 'Margin between text and switcher', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-map-drag .switch-slider > p' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
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




	}

	protected function render( $instance = [] ) {
		$source = $this->get_settings_for_display( 'ts_source' );

		if ( $source === 'current-post' ) {
			$post = \Voxel\get_current_post();
			if ( ! $post ) {
				return;
			}

			$location = $post->get_field('location');
			if ( ! $location ) {
				return;
			}

			$address = $location->get_value();
			if ( ! ( $address['latitude'] && $address['longitude'] ) ) {
				return;
			}
		} else {
			$search_form = \Voxel\get_related_widget( $this, $this->_get_template_id(), 'mapToSearch', 'right' );
			if ( ! $search_form ) {
				return;
			}

			$widget = new \Voxel\Widgets\Search_Form( $search_form, [] );

			$switchable_desktop = $widget->get_settings( 'mf_switcher_desktop' ) === 'yes';
			$hidden_desktop = $widget->get_settings( 'switcher_desktop_default' ) === 'feed';
			$switchable_tablet = $widget->get_settings( 'mf_switcher_tablet' ) === 'yes';
			$hidden_tablet = $widget->get_settings( 'switcher_tablet_default' ) === 'feed';
			$switchable_mobile = $widget->get_settings( 'mf_switcher_mobile' ) === 'yes';
			$hidden_mobile = $widget->get_settings( 'switcher_mobile_default' ) === 'feed';

			$this->add_render_attribute( '_wrapper', 'class', [
				$switchable_desktop && $hidden_desktop ? 'vx-hidden-desktop' : '',
				$switchable_tablet && $hidden_tablet ? 'vx-hidden-tablet' : '',
				$switchable_mobile && $hidden_mobile ? 'vx-hidden-mobile' : '',
			] );
		}

		require locate_template( 'templates/widgets/map.php' );
	}

	public function get_script_depends() {
		return [
			'vx:google-maps.js',
			'google-maps',
		];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
