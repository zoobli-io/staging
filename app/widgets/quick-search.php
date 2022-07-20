<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Quick_Search extends Base_Widget {

	public function get_name() {
		return 'quick-search';
	}

	public function get_title() {
		return __( 'Quick Search (27)', 'my-listing' );
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

			$this->add_control(
				'ts_choose_post_types',
				[
					'label' => __( 'Choose post types', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => [
						'places'  => __( 'Places', 'plugin-domain' ),
						'events' => __( 'Events', 'plugin-domain' ),
						'real estate' => __( 'Real estate', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-form-group' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();




	}

	protected function render( $instance = [] ) {
		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/quick-search.php' );
	}

	public function get_style_depends() {
		return [ 'vx:quick-search.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
