<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Product_Form extends Base_Widget {

	public function get_name() {
		return 'ts-product-form';
	}

	public function get_title() {
		return __( 'Product Form (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'ts_general', [
			'label' => __( 'Product form', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

			$post_type = \Voxel\get_current_post_type();
			if ( $post_type ) {
				$options = [ '' => 'Choose product field' ];
				foreach ( $post_type->get_fields() as $field ) {
					if ( $field->get_type() === 'product' ) {
						$options[ $field->get_key() ] = $field->get_label();
					}
				}

				$this->add_control( 'ts_product_field', [
					'label' => __( 'Product', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'label_block' => true,
					'options' => $options,
				] );
			}


			

			

		$this->end_controls_section();

		$this->start_controls_section( 'ts_prform_settings', [
			'label' => __( 'Settings', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

			$this->add_control(
				'show_prform_head',
				[
					'label' => __( 'Form header', 'plugin-domain' ),
					'description' => __( 'Desktop only', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'flex',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'{{WRAPPER}} .booking-head' => 'display: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'show_prform_footer',
				[
					'label' => __( 'Form footer', 'plugin-domain' ),
					'description' => __( 'Desktop only', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'flex',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'{{WRAPPER}} .tcc-container' => 'display: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'prform_stepone_text',
				[
					'label' => __( 'First step heading', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Booking details', 'plugin-domain' ),
					'placeholder' => __( 'Type your text', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'prform_stepone_ico',
				[
					'label' => __( 'First step icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'lar la-calendar',
						'library' => 'la-regular',
					],
				]
			);


			$this->add_control(
				'prform_steptwo_text',
				[
					'label' => __( 'Second step heading', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Additional information', 'plugin-domain' ),
					'placeholder' => __( 'Type your text', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'prform_steptwo_ico',
				[
					'label' => __( 'Second step icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-info-circle',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'prform_continue',
				[
					'label' => __( 'Continue button label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Continue', 'plugin-domain' ),
					'placeholder' => __( 'Type your text', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'prform_checkout',
				[
					'label' => __( 'Checkout button label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Checkout', 'plugin-domain' ),
					'placeholder' => __( 'Type your text', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'sub_con_ico',
				[
					'label' => __( 'Continue icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'lar la-calendar-check',
						'library' => 'la-regular',
					],
				]
			);

			$this->add_control(
				'sub_checkout_ico',
				[
					'label' => __( 'Checkout icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-shopping-bag',
						'library' => 'la-regular',
					],
				]
			);

		$this->end_controls_section();
		$this->start_controls_section(
			'ts_prform_general',
			[
				'label' => __( 'Product form: General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'prform_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-booking-form' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'prform_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-booking-form' => 'padding: {{SIZE}}{{UNIT}};',
					],

				]
			);


			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'prform_border',
					'label' => __( 'Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-booking-form',
				]
			);

			$this->add_responsive_control(
				'prform_radius',
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
						'{{WRAPPER}} .ts-booking-form' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'prform_shadow',
					'label' => __( 'Box Shadow', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-booking-form',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_prform_head',
			[
				'label' => __( 'Product form: Head', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			

			
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'prhead_typo',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .booking-head p',
				]
			);

			$this->add_responsive_control(
				'prhead_icon_c',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}  .booking-head > i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'prhead_text_c',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}}  .booking-head p' => 'color: {{VALUE}}',
					],

				]
			);

		
			$this->add_responsive_control(
				'prhead_border',
				[
					'label' => __( 'Seperator color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .booking-head' => 'border-color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'prhead_icon_size',
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
						'{{WRAPPER}} .booking-head > i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			
			

		$this->end_controls_section();



		$this->start_controls_section(
			'prform_fields_general',
			[
				'label' => __( 'Fields: General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_control(
					'ts_sf_input',
					[
						'label' => __( 'Field', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_responsive_control(
					'field_spacing_value',
					[
						'label' => __( 'Spacing', 'plugin-domain' ),
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
							'{{WRAPPER}} .ts-booking-form > div' => 'grid-gap: {{SIZE}}{{UNIT}};',
						],

					]
				);

				$this->add_control(
					'ts_sf_input_lbl',
					[
						'label' => __( 'Field label', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);


				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_sf_input_label_text',
						'label' => __( 'Typography' ),
						'selector' => '{{WRAPPER}} .ts-form-group label',
					]
				);


				$this->add_responsive_control(
					'ts_sf_input_label_col',
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
						],
					]
				);

				$this->add_control(
					'sf_input_label_padding',
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
					'ts_field_desc_h',
					[
						'label' => __( 'Field description', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);


				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'ts_field_desc_t',
						'label' => __( 'Typography' ),
						'selector' => '{{WRAPPER}} .ts-form-group small',
					]
				);


				$this->add_responsive_control(
					'ts_field_desc_col',
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

					$this->add_responsive_control(
						'ts_intxt_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-form textarea.ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								'{{WRAPPER}} .ts-form input.ts-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'ts_intxt_textarea_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 1500,
									'step' => 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}}  .ts-form textarea.ts-filter' => 'height: {{SIZE}}{{UNIT}};',
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
			'prform_timeslots',
			[
				'label' => __( 'Time/Date slots', 'plugin-name' ),
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
						'{{WRAPPER}} .ts-pick-slot' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
					],
				]
			);

			$this->add_responsive_control(
				'prf_timeslot_gap',
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
						'{{WRAPPER}} .ts-pick-slot' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				
				]
			);

			$this->add_control(
				'prf_timeslot_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'selectors' => [
						'{{WRAPPER}} .ts-pick-slot li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'prf_timeslot_bg',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-pick-slot li a' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'prf_timeslot_radius',
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
						'{{WRAPPER}} .ts-pick-slot li a' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'prf_timeslot_text',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-pick-slot li a span' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'prf_timeslot_typo',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-pick-slot li a span',
				]
			);

			$this->add_responsive_control(
				'prf_timeslot_ico',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-pick-slot li a i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'prf_timeslot_ico_size',
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
						'{{WRAPPER}} .ts-pick-slot li a i' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'prf_timeslot_ico_spacing',
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
						'{{WRAPPER}} .ts-pick-slot li a' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'prf_timeslot_active',
				[
					'label' => __( 'aCTIVE', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
				'prf_timeslot_bg_a',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-pick-slot li.slot-picked a' => 'background: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'prf_timeslot_text_a',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-pick-slot li.slot-picked a span' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_responsive_control(
				'prf_timeslot_ico_a',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-pick-slot li.slot-picked a i' => 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'prf_timeslot_typo_A',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .ts-pick-slot li.slot-picked a span',
				]
			);



		$this->end_controls_section();



		

		$this->start_controls_section(
			'ts_booking_submit',
			[
				'label' => __( 'Submit button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_submit_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_submit_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'submit_general',
						[
							'label' => __( 'General', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					

					$this->add_responsive_control(
						'sub_icon_size',
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
								'{{WRAPPER}} .ts-booking-submit i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_sf_search_button',
						[
							'label' => __( 'Button', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_submit_btn_typo',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-booking-submit',
						]
					);

					$this->add_responsive_control(
						'ts_sf_form_btn_c',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-booking-submit' => 'color: {{VALUE}}',
							],

						]
					);

					


					$this->add_responsive_control(
						'ts_sf_form_btn_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-booking-submit' => 'background: {{VALUE}}',
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
								'{{WRAPPER}} .ts-booking-submit' => 'height: {{SIZE}}{{UNIT}};',
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
								'{{WRAPPER}} .ts-booking-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_sf_search_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-booking-submit',
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
								'{{WRAPPER}} .ts-booking-submit' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
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
								'{{WRAPPER}}  .ts-booking-submit i' => 'padding-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_submit_ico_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}}  .ts-booking-submit',
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
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-booking-submit:hover' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_form_btn_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-booking-submit:hover' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_sf_form_btn_border_h',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}}  .ts-booking-submit:hover' => 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_submit_ico_shadow_h',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}}  .ts-booking-submit:hover',
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
			'prform_calculator',
			[
				'label' => __( 'Form: Price calculator', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'calc_list_gap',
				[
					'label' => __( 'List spacing', 'plugin-domain' ),
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
						'{{WRAPPER}} .ts-cost-calculator' => 'grid-gap: {{SIZE}}{{UNIT}};',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'calc_text',
					'label' => __( 'Typography' ),
					'selector' => '{{WRAPPER}} .ts-cost-calculator li p',
				]
			);

			$this->add_control(
				'calc_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-cost-calculator li p'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'calc_text_total',
					'label' => __( 'Typography (Total)' ),
					'selector' => '{{WRAPPER}} .ts-cost-calculator li.ts-total p',
				]
			);

			$this->add_control(
				'calc_text_color_total',
				[
					'label' => __( 'Text color (Total)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-cost-calculator li.ts-total p'
						=> 'color: {{VALUE}}',
					],

				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'number_add',
			[
				'label' => __( 'Form: Number addition', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'number_add_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'no_add_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'no_add_value',
						[
							'label' => __( 'Input', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'noadd_value_typo',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-stepper-input input',
						]
					);
					
					$this->add_control(
						'noadd_button',
						[
							'label' => __( 'Button styling', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'noadd_btn_size',
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
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'noadd_btn_color',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'noadd_btn_icon_size',
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
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'noadd_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'noadd_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-stepper-input .ts-icon-btn',
						]
					);

					$this->add_responsive_control(
						'noadd_btn_radius',
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
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					

				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'noadd_button_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'noadd_btn_h',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn:hover i'
								=> 'color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'noadd_btn_bg_h',
						[
							'label' => __( 'Button background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn:hover'
								=> 'background-color: {{VALUE}};',
							],

						]
					);

					$this->add_control(
						'noadd_border_c_h',
						[
							'label' => __( 'Button border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-stepper-input .ts-icon-btn:hover'
								=> 'border-color: {{VALUE}};',
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
								'{{WRAPPER}} .ts-btn-4 i'
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
								'{{WRAPPER}} .ts-btn-4 i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'tertiary_btn_bg',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'tertiary_btn_border',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-btn-4',
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
								'{{WRAPPER}} .ts-btn-4' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'tertiary_btn_text',
							'label' => __( 'Typography' ),
							'selector' => '{{WRAPPER}} .ts-btn-4',
						]
					);

					$this->add_control(
						'tertiary_btn_text_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-btn-4'
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
								'{{WRAPPER}} .ts-btn-4:hover i'
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
								'{{WRAPPER}} .ts-btn-4:hover'
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
								'{{WRAPPER}} .ts-btn-4:hover'
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
								'{{WRAPPER}} .ts-btn-4:hover'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		


	}

	protected function render( $instance = [] ) {
		$post = \Voxel\get_current_post();
		$field = $post ? $post->get_field( $this->get_settings_for_display( 'ts_product_field' ) ) : null;
		$product_type = $field ? $field->get_product_type() : null;
		$config = $field ? $field->get_product_form_config() : null;
		if ( ! ( $post && $field && $product_type && $config && $config['enabled'] ) ) {
			return;
		}

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/product-form.php' );

		if ( \Voxel\is_edit_mode() ) {
			printf( '<script type="text/javascript">%s</script>', 'window.render_product_form();' );
		}
	}

	public function get_script_depends() {
		return [
			'pikaday',
			'vx:product-form.js',
		];
	}

	public function get_style_depends() {
		return [
			'pikaday',
			'vx:product-form.css',
		];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
