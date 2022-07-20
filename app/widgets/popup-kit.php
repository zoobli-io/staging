<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Kit extends Base_Widget {

	public function get_name() {
		return 'ts-test-widget-1';
	}

	public function get_title() {
		return __( 'Popup Kit (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		/* 
		==============
		Popup: General
		==============
		*/

		

		$this->apply_controls( Option_Groups\Popup_General::class );

		/* 
		===================
		Popup: Head
		===================
		*/

		

		$this->apply_controls( Option_Groups\Popup_Head::class );

		/* 
		==============
		Popup: Controller
		==============
		*/

		

		$this->apply_controls( Option_Groups\Popup_Controller::class );

		/* 
		==============
		Popup: Label and description
		==============
		*/

		

		$this->apply_controls( Option_Groups\Popup_Label::class );


		/* 
		===================
		Popup: Menu styling
		===================
		*/

		$this->apply_controls( Option_Groups\Popup_Menu::class );


		/* 
		===================
		Popup: Checkbox 
		===================
		*/

		$this->apply_controls( Option_Groups\Popup_Checkbox::class );

		/* 
		===================
		Popup: Radio
		===================
		*/

		$this->apply_controls( Option_Groups\Popup_Radio::class );
		

		/* 
		===================
		Popup: Input styling
		===================
		*/

		

		$this->apply_controls( Option_Groups\Popup_Input::class );

		/* 
		===================
		Popup: Popup: File gallery
		===================
		*/


		$this->apply_controls( Option_Groups\File_Field::class );



		$this->start_controls_section(
			'ts_sf_popup_number',
			[
				'label' => __( 'Popup: Number', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


				$this->add_control(
					'ts_popup_number',
					[
						'label' => __( 'Number popup', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);


				$this->add_control(
					'popup_number_input_size',
					[
						'label' => __( 'Input value size', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px'],
						'range' => [
							'px' => [
								'min' => 13,
								'max' => 30,
								'step' => 1,
							],
						],
						'default' => [
							'unit' => 'px',
							'size' => 20,
						],
						'selectors' => [
							'.ts-field-popup .ts-stepper-input input' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);


		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_popup_range',
			[
				'label' => __( 'Popup: Range slider', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'ts_popup_range',
				[
					'label' => __( 'Range slider', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'ts_popup_range_size',
				[
					'label' => __( 'Range value size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 13,
							'max' => 30,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'.range-slider-wrapper .range-value' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_popup_range_val',
				[
					'label' => __( 'Range value color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.range-slider-wrapper .range-value'
						=> 'color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_popup_range_bg',
				[
					'label' => __( 'Range background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.noUi-target'
						=> 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_popup_range_bg_selected',
				[
					'label' => __( 'Selected range background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.noUi-connect'
						=> 'background-color: {{VALUE}}',
					],

				]
			);

			$this->add_control(
				'ts_popup_range_handle',
				[
					'label' => __( 'Handle background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.noUi-handle' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'ts_popup_range_handle_border',
					'label' => __( 'Handle border', 'plugin-domain' ),
					'selector' => '.noUi-handle',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_sf_popup_switch',
			[
				'label' => __( 'Popup: Switch', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

				$this->add_control(
					'ts_popup_switch',
					[
						'label' => __( 'Switch slider', 'plugin-name' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);

				$this->add_control(
					'ts_popup_switch_bg',
					[
						'label' => __( 'Switch slider background (Inactive)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'.onoffswitch .onoffswitch-label'
							=> 'background-color: {{VALUE}}',
						],

					]
				);

				$this->add_control(
					'ts_popup_switch_bg_active',
					[
						'label' => __( 'Switch slider background (Active)', 'plugin-domain' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'.onoffswitch .onoffswitch-checkbox:checked + .onoffswitch-label'
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
							'.onoffswitch .onoffswitch-label:before'
							=> 'background-color: {{VALUE}}',
						],

					]
				);



		$this->end_controls_section();


		

		
		/* 
		===================
		Popup: Icon button
		===================
		*/

		$this->apply_controls( Option_Groups\Popup_Icon_Button::class );

		/* 
		===================
		Popup: Calendar
		===================
		*/

		/* 
		===================
		Popup: Tertiary button
		===================
		*/


		$this->start_controls_section(
			'ts_scndry_btn_popup',
			[
				'label' => __( 'Popup: Tertiary button', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'scndry_btn_tabsn_popup'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'scndry_btn_normaln_popup',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'scndry_btn_icon_colorn_popup',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-btn-4 i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_responsive_control(
						'scndry_btn_icon_sizen_popup',
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
								'.ts-field-popup .ts-btn-4 i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'scndry_btn_bgn_popup',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-btn-4'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'scndry_btn_bordern_popup',
							'label' => __( 'Button border', 'plugin-domain' ),
							'selector' => '.ts-field-popup .ts-btn-4',
						]
					);

					$this->add_responsive_control(
						'scndry_btn_radiusn_popup',
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
								'.ts-field-popup .ts-btn-4' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'scndry_btn_textn_popup',
							'label' => __( 'Typography' ),
							'selector' => '.ts-field-popup .ts-btn-4',
						]
					);

					$this->add_control(
						'scndry_btn_text_colorn_popup',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-btn-4'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'scndry_btn_hovern_popup',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'scndry_btn_icon_color_h_popup',
						[
							'label' => __( 'Button icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-btn-4:hover i'
								=> 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'scndry_btn_bg_hn_popup',
						[
							'label' => __( 'Button background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-btn-4:hover'
								=> 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'scndry_btn_border_h_popup',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-btn-4:hover'
								=> 'border-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'scndry_btn_text_color_h_popup',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup .ts-btn-4:hover'
								=> 'color: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
		
		/* 
		==============
		Popup: Calendar
		==============
		*/

		$this->apply_controls( Option_Groups\Popup_Calendar::class );

		/* 
		==============
		Popup: Notifications
		==============
		*/

		

		$this->apply_controls( Option_Groups\Popup_Notifications::class );

		/* 
		==============
		Popup: Conversation
		==============
		*/

		

		$this->apply_controls( Option_Groups\Popup_Conversation::class );

		/* 
		==============
		Popup: Textarea
		==============
		*/

		$this->start_controls_section(
			'ts_popup_textarea',
			[
				'label' => __( 'Popup: Textarea', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_popup_textarea_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_textarea_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_popup_x_heading',
						[
							'label' => __( 'Textarea', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_sf_popup_textarea_height',
						[
							'label' => __( 'Textarea height', 'plugin-domain' ),
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
								'.ts-field-popup textarea' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'popup_textarea_font',
							'label' => __( 'Typography' ),
							'selector' => '.ts-field-popup textarea',
						]
					);


					$this->add_control(
						'popup_textarea_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup textarea' => 'background: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'popup_textarea_bg_filled',
						[
							'label' => __( 'Background color (Focus)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup textarea:focus' => 'background-color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'popup_textarea_value_col',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup textarea' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_textarea_plc_color',
						[
							'label' => __( 'Placeholder color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
								'.ts-field-popup textarea:-moz-placeholder' => 'color: {{VALUE}}',
								'.ts-field-popup textarea::-moz-placeholder' => 'color: {{VALUE}}',
								'.ts-field-popup textarea:-ms-input-placeholder' => 'color: {{VALUE}}',
							],

						]
					);

					$this->add_control(
						'ts_textarea_padding',
						[
							'label' => __( 'Textarea padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'.ts-field-popup textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);


					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_popup_textarea_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '.ts-field-popup textarea',
						]
					);


				$this->end_controls_tab();


				/* Hover tab */

				$this->start_controls_tab(
					'ts_textarea_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);


					$this->add_control(
						'ts_popup_textarea_h',
						[
							'label' => __( 'Textarea', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_sf_popup_textarea_bg_h',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-field-popup textarea:hover' => 'background: {{VALUE}}',
							],

						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		/* 
		==============
		Popup: Rating
		==============
		*/

		$this->start_controls_section(
			'ts_sf_popup_rating',
			[
				'label' => __( 'Popup: Rating', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'ts_rating_columns',
				[
					'label' => __( 'Number of columns', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'auto',
					'options' => [
						'auto'  => __( 'Auto', 'plugin-domain' ),
						'custom'  => __( 'Custom', 'plugin-domain' ),
					],
				]
			);

			$this->add_responsive_control(
				'ts_rating_percentage',
				[
					'label' => __( 'Column width (%)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 15,
							'max' => 100,
							'step' => 1,
						],
					],
					'condition' => [ 'ts_rating_columns' => 'custom' ],
					'selectors' => [
						'.ts-review-field > ul > li' => 'width: {{SIZE}}%;',
					],
				]
			);

			$this->add_responsive_control(
				'ts_rating_icon_size',
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
					'default' => [
						'unit' => 'px',
						'size' => 30,
					],
					'selectors' => [
						'.ts-review-field li a i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_rating_icon_color',
				[
					'label' => __( 'Icon Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-review-field li a i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_rating_icon_color_selected',
				[
					'label' => __( 'Icon Color (Selected)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-review-field li.rating-selected a i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_rating_typography',
					'label' => __( 'Typography', 'plugin-domain' ),
					'selector' => '.ts-review-field li p',
				]
			);

			$this->add_control(
				'ts_rating_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-review-field li p' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_rating_typography_s',
					'label' => __( 'Typography (Selected)', 'plugin-domain' ),
					'selector' => '.ts-review-field li.rating-selected p',
				]
			);

			$this->add_control(
				'ts_rating_text_color_s',
				[
					'label' => __( 'Text color (Selected)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-review-field li.rating-selected p' => 'color: {{VALUE}}',
					],
				]
			);	

		$this->end_controls_section();	
	}

	protected function render( $instance = [] ) {
		require locate_template( 'templates/widgets/test-widget-1.php' );
	}

	public function get_style_depends() {
		return [ 'vx:popup-kit.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
