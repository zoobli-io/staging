<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Advanced_List extends Base_Widget {

	public function get_name() {
		return 'ts-advanced-list';
	}

	public function get_title() {
		return __( 'Action list (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'ts_action_content',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'ts_action_content_default',
				[
					'label' => __( 'Action content (Default)', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',

				]
			);


			$repeater->add_control(
				'ts_action_type',
				[
					'label' => __( 'Choose action', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'none'  => __( 'None', 'plugin-domain' ),
						'action_link' => __( 'Link', 'plugin-domain' ),
						'action_follow_post' => __( 'Follow post', 'plugin-domain' ),
						'action_follow' => __( 'Follow author', 'plugin-domain' ),
						'action_save' => __( 'Save post to collection', 'plugin-domain' ),
						'direct_message' => __( 'Direct Message', 'plugin-domain' ),
						'edit_post' => __( 'Edit post', 'plugin-domain' ),
						'share_post' => __( 'Share post', 'plugin-domain' ),
					],
				]
			);

			$repeater->add_control(
				'ts_action_link',
				[
					'label' => __( 'Link', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'plugin-domain' ),
					'condition' => [ 'ts_action_type' => 'action_link' ],
					'show_external' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
						'nofollow' => true,
					],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_text',
				[
					'label' => __( 'Text', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Action', 'plugin-domain' ),
					'placeholder' => __( 'Action title', 'plugin-domain' ),
				]
			);

			$repeater->add_control(
				'ts_acw_initial_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-bell',
						'library' => 'la-solid',
					],
				]
			);


			$repeater->add_control(
				'ts_acw_reveal_heading',
				[
					'label' => __( 'Active state', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [ 'ts_action_type' => [ 'action_follow', 'action_save', 'action_follow_post' ] ],
				]
			);

			$repeater->add_control(
				'ts_acw_reveal_text',
				[
					'label' => __( 'Text', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Action', 'plugin-domain' ),
					'placeholder' => __( 'Action title', 'plugin-domain' ),
					'condition' => [ 'ts_action_type' => [ 'action_follow', 'action_save', 'action_follow_post' ] ],
				]
			);

			$repeater->add_control(
				'ts_acw_reveal_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-bell',
						'library' => 'la-solid',
					],
					'condition' => [ 'ts_action_type' => [ 'action_follow', 'action_save', 'action_follow_post' ] ],
				]
			);

			$repeater->add_control(
				'ts_acw_intermediate_heading',
				[
					'label' => __( 'Intermediate state', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [ 'ts_action_type' => [ 'action_follow', 'action_follow_post' ] ],
				]
			);

			$repeater->add_control(
				'ts_acw_intermediate_text',
				[
					'label' => __( 'Text', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Action', 'plugin-domain' ),
					'placeholder' => __( 'Action title', 'plugin-domain' ),
					'condition' => [ 'ts_action_type' => [ 'action_follow', 'action_follow_post' ] ],
				]
			);

			$repeater->add_control(
				'ts_acw_intermediate_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-bell',
						'library' => 'la-solid',
					],
					'condition' => [ 'ts_action_type' => [ 'action_follow', 'action_follow_post' ] ],
				]
			);

			$repeater->add_control(
				'ts_acw_custom_style',
				[
					'label' => __( 'Custom style', 'plugin-domain' ),
					'description' => __( 'Use custom styling for this specific item only, overwrites default style', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'your-plugin' ),
					'label_off' => __( 'Off', 'your-plugin' ),
					'default' => '',
				]
			);

			

			$repeater->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ts_acw_custom_typo',
					'label' => __( 'Label typography' ),
					'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con',
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_heading_custom',
				[
					'label' => __( 'Colors', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_color_custom',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con' => 'color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_color_h_custom',
				[
					'label' => __( 'Text color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con:hover' => 'color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_color_a_custom',
				[
					'label' => __( 'Text color (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .active.ts-action-con' => 'color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_bg_custom',
				[
					'label' => __( 'Background color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con' => 'background: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);



			$repeater->add_control(
				'ts_acw_initial_bg_h_custom',
				[
					'label' => __( 'Background color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con:hover' => 'background: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_bg_a_custom',
				[
					'label' => __( 'Background color (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .active.ts-action-con' => 'background: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_border_heading_custom',
				[
					'label' => __( 'Border', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_border_radius_custom',
				[
					'label' => __( 'Border radius', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 70,
							'step' => 1,
						],
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}  .ts-action-con' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$repeater->add_control(
				'ts_acw_border_c_custom',
				[
					'label' => __( 'Border color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con' => 'border-color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_border_h_custom',
				[
					'label' => __( 'Border color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con:hover' => 'border-color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_initial_border_a_custom',
				[
					'label' => __( 'Border color (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .active.ts-action-con' => 'border-color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			

			

			$repeater->add_control(
				'ts_acw_icon_container_custom',
				[
					'label' => __( 'Icon container', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_con_size_custom',
				[
					'label' => __( 'Size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 70,
							'step' => 1,
						],
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_con_bg_custom',
				[
					'label' => __( 'Background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-icon' => 'background: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_con_bg_h_custom',
				[
					'label' => __( 'Background (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con:hover .ts-action-icon' => 'background: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_con_bg_a_custom',
				[
					'label' => __( 'Background (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .active.ts-action-con .ts-action-icon' => 'background: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);


			$repeater->add_responsive_control(
				'ts_acw_icon_margin_custom',
				[
					'label' => __( 'Margin against the text', 'plugin-domain' ),
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
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_heading_custom',
				[
					'label' => __( 'Icon', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_size_custom',
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
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_color_custom',
				[
					'label' => __( 'Icon Color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-icon i' => 'color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_color_h_custom',
				[
					'label' => __( 'Icon Color (Hover)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .ts-action-con:hover .ts-action-icon i' => 'color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);

			$repeater->add_control(
				'ts_acw_icon_color_a_custom',
				[
					'label' => __( 'Icon Color (Active)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}} .active.ts-action-con .ts-action-icon i' => 'color: {{VALUE}}',
					],
					'condition' => [ 'ts_acw_custom_style' => 'yes' ],
				]
			);


			$this->add_control(
				'ts_actions',
				[
					'label' => __( 'Items', 'elementor' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_advanced_list_general',
			[
				'label' => __( 'List', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
			

			$this->add_control(
				'ts_al_columns_no',
				[
					'label' => __( 'Item width', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'elementor-col-auto',
					'options' => [
						'elementor-col-auto'  => __( 'Auto', 'plugin-domain' ),
						'elementor-col-cstm'  => __( 'Custom item width', 'plugin-domain' ),
					],
				]
			);

			$this->add_responsive_control(
				'ts_al_columns_cstm',
				[
					'label' => __( 'Width (px)', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 50,
							'max' => 200,
							'step' => 1,
						],
					],
					'condition' => [ 'ts_al_columns_no' => 'elementor-col-cstm' ],
					'selectors' => [
						'{{WRAPPER}} .ts-advanced-list .ts-action.elementor-col-cstm' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'ts_al_justify',
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
						'{{WRAPPER}} .ts-advanced-list' => 'justify-content: {{VALUE}}',
					],
				]
			);




		$this->end_controls_section();

		$this->start_controls_section(
			'ts_advanced_list_item',
			[
				'label' => __( 'List item', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'al_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'al_normal_tab',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'al_item_general',
						[
							'label' => __( 'General', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_al_align',
						[
							'label' => __( 'Justify content', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'left',
							'options' => [
								'left'  => __( 'Left', 'plugin-domain' ),
								'center' => __( 'Center', 'plugin-domain' ),
								'flex-end' => __( 'Right', 'plugin-domain' ),
							],

							'selectors' => [
								'{{WRAPPER}} .ts-action  .ts-action-con' => 'justify-content: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-action  .ts-action-con' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_margin',
						[
							'label' => __( 'Margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_acw_height',
						[
							'label' => __( 'Height', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 200,
									'step' => 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ts-action  .ts-action-con' => 'height: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$this->add_control(
						'al_item_border',
						[
							'label' => __( 'Border', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_acw_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-action-con',
						]
					);

					$this->add_control(
						'ts_acw_border_radius',
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
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-action-con' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_acw_border_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}}  .ts-action-con',
						]
					);

					$this->add_control(
						'al_item_typo',
						[
							'label' => __( 'Typography', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_acw_typography',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}}  .ts-action-con',
						]
					);

					$this->add_control(
						'ts_item_colors',
						[
							'label' => __( 'Item colors', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_acw_initial_color',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-con' => 'color: {{VALUE}}',
							],
						]
					);


					$this->add_control(
						'ts_acw_initial_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}}  .ts-action-con' => 'background: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_icon_container',
						[
							'label' => __( 'Icon container', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_acw_icon_con_bg',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-icon' => 'background: {{VALUE}}',
							],
						]
					);

					

					

					$this->add_responsive_control(
						'ts_acw_icon_con_size',
						[
							'label' => __( 'Size', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 30,
									'max' => 70,
									'step' => 1,
								],
							],
							'selectors' => [
								'{{WRAPPER}} .ts-action-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_acw_icon_con_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-action-icon',
						]
					);

					$this->add_control(
						'ts_acw_icon_con_radius',
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
							'default' => [
								'unit' => 'px',
								'size' => 26,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-action-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_acw_icon_margin',
						[
							'label' => __( 'Margin against the text', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 20,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 0,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-action-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_acw_icon_heading',
						[
							'label' => __( 'Icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);


					$this->add_responsive_control(
						'ts_acw_icon_size',
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
								'size' => 26,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-action-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_acw_icon_color',
						[
							'label' => __( 'Icon Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-icon i' => 'color: {{VALUE}}',
							],
						]
					);

					

					


				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'al_hover_tab',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_acw_border_shadow_h',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-action:hover > .ts-action-con',
						]
					);

					$this->add_control(
						'ts_item_colors_h',
						[
							'label' => __( 'Item colors', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_acw_border_h',
						[
							'label' => __( 'Border color (Hover)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-con:hover' => 'border-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_initial_color_h',
						[
							'label' => __( 'Text color (Hover)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-con:hover' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_initial_bg_h',
						[
							'label' => __( 'Background color (Hover)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-con:hover' => 'background: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_icon_con_hover',
						[
							'label' => __( 'Icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_acw_icon_con_bg_h',
						[
							'label' => __( 'Background (Hover)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-con:hover .ts-action-icon' => 'background: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_icon_color_h',
						[
							'label' => __( 'Icon Color (Hover)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-action-con:hover .ts-action-icon i' => 'color: {{VALUE}}',
							],
						]
					);

				$this->end_controls_tab();

				/* Active tab */

				$this->start_controls_tab(
					'al_active_tab',
					[
						'label' => __( 'Active', 'plugin-name' ),
					]
				);

				

					$this->add_control(
						'ts_item_colors_a',
						[
							'label' => __( 'Item colors', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_acw_initial_color_a',
						[
							'label' => __( 'Text color (Active)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .active.ts-action-con' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_initial_bg_a',
						[
							'label' => __( 'Background color (Active)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .active.ts-action-con' => 'background: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_initial_border_a',
						[
							'label' => __( 'Border color (Active)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .active.ts-action-con' => 'border-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_icon_con_active',
						[
							'label' => __( 'Icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_acw_icon_con_bg_a',
						[
							'label' => __( 'Background (Active)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .active.ts-action-con .ts-action-icon' => 'background: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_acw_icon_color_a',
						[
							'label' => __( 'Icon Color (Active)', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .active.ts-action-con .ts-action-icon i' => 'color: {{VALUE}}',
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();


		$this->end_controls_section();


	}

	protected function render( $instance = [] ) {
		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/advanced-list.php' );
	}

	public function get_style_depends() {
		return [ 'vx:action.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
