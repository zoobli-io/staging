<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class User_Bar extends Base_Widget {

	public function get_name() {
		return 'ts-user-bar';
	}

	public function get_title() {
		return __( 'User bar (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {

		
		$this->start_controls_section(
			'user_area_repeater',
			[
				'label' => __( 'User area components', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'ts_component_heading',
				[
					'label' => __( 'Component details', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',

				]
			);

			$repeater->add_control(
				'ts_component_type',
				[
					'label' => __( 'Component type', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'notifications'  => __( 'Notifications', 'plugin-domain' ),
						'messages' => __( 'Messages', 'plugin-domain' ),
						'user_menu' => __( 'User Menu', 'plugin-domain' ),
						'select_wp_menu' => __( 'Menu', 'plugin-domain' ),
						'link' => __( 'Custom link', 'plugin-domain' ),
					],
				]
			);

			$repeater->add_control(
				'ts_choose_menu',
				[
					'label' => __( 'Choose menu', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'condition' => [ 'ts_component_type' => [ 'select_wp_menu', 'user_menu' ] ],
					'options' => get_registered_nav_menus(),
				]
			);

			$repeater->add_control(
				'choose_component_icon',
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
				'component_url',
				[
					'label' => __( 'Link', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::URL,
					'condition' => [ 'ts_component_type' => 'link' ],
					'placeholder' => __( 'https://your-link.com', 'plugin-domain' ),
					'show_external' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
						'nofollow' => true,
					],
				]
			);


			$repeater->add_control(
				'component_title',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'placeholder' => __( 'Type your title here', 'plugin-domain' ),
					'condition' => [ 'ts_component_type' => 'link' ],
				]
			);


			$repeater->add_control(
				'messages_title',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'placeholder' => __( 'Type your title here', 'plugin-domain' ),
					'default' => __( 'Messages', 'plugin-domain' ),
					'condition' => [ 'ts_component_type' => 'messages' ],
				]
			);

			$repeater->add_control(
				'wp_menu_title',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'placeholder' => __( 'Type your title here', 'plugin-domain' ),
					'default' => __( 'Menu', 'plugin-domain' ),
					'condition' => [ 'ts_component_type' => 'select_wp_menu' ],
				]
			);

			$repeater->add_control(
				'notifications_title',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'placeholder' => __( 'Type your title here', 'plugin-domain' ),
					'default' => __( 'Notifications', 'plugin-domain' ),
					'condition' => [ 'ts_component_type' => 'notifications' ],
				]
			);

			$repeater->add_control(
				'label_visibility',
				[
					'label' => __( 'Enable label visibility', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no'
				]
			);

			// $repeater->add_control(
			// 	'label_visibility_desktop',
			// 	[
			// 		'label' => __( 'Show on Desktop', 'plugin-domain' ),
			// 		'type' => \Elementor\Controls_Manager::SWITCHER,
			// 		'label_on' => __( 'Show', 'your-plugin' ),
			// 		'label_off' => __( 'Hide', 'your-plugin' ),
			// 		'return_value' => 'flex',
			// 		'default' => 'none',
			// 		'selectors' => [
			// 			'(desktop){{WRAPPER}} {{CURRENT_ITEM}} .ts_comp_label' => 'display: {{VALUE}}',
			// 		],
			// 		'condition' => [ 'label_visibility' => 'yes' ],
			// 	]
			// );
			$repeater->add_control(
				'label_visibility_desktop',
				[
					'label' => __( 'Show on desktop', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'(desktop){{WRAPPER}} {{CURRENT_ITEM}} .ts_comp_label' => 'display: {{VALUE}}',
					],
					'condition' => [ 'label_visibility' => 'yes' ],
				]
			);

			// $repeater->add_control(
			// 	'label_visibility_tablet',
			// 	[
			// 		'label' => __( 'Show on Tablet', 'plugin-domain' ),
			// 		'type' => \Elementor\Controls_Manager::SWITCHER,
			// 		'label_on' => __( 'Show', 'your-plugin' ),
			// 		'label_off' => __( 'Hide', 'your-plugin' ),
			// 		'return_value' => 'flex',
			// 		'default' => 'none',
			// 		'selectors' => [
			// 			'(tablet){{WRAPPER}} {{CURRENT_ITEM}} .ts_comp_label' => 'display: {{VALUE}}',
			// 		],
			// 		'condition' => [ 'label_visibility' => 'yes' ],
			// 	]
			// );

			$repeater->add_control(
				'label_visibility_tablet',
				[
					'label' => __( 'Show on tablet', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'(tablet){{WRAPPER}} {{CURRENT_ITEM}} .ts_comp_label' => 'display: {{VALUE}}',
					],
					'condition' => [ 'label_visibility' => 'yes' ],
				]
			);

			// $repeater->add_control(
			// 	'label_visibility_mobile',
			// 	[
			// 		'label' => __( 'Show on Mobile', 'plugin-domain' ),
			// 		'type' => \Elementor\Controls_Manager::SWITCHER,
			// 		'label_on' => __( 'Show', 'your-plugin' ),
			// 		'label_off' => __( 'Hide', 'your-plugin' ),
			// 		'return_value' => 'flex',
			// 		'default' => 'none',
			// 		'selectors' => [
			// 			'(mobile){{WRAPPER}} {{CURRENT_ITEM}} .ts_comp_label' => 'display: {{VALUE}}',
			// 		],
			// 		'condition' => [ 'label_visibility' => 'yes' ],
			// 	]
			// );

			$repeater->add_control(
				'label_visibility_mobile',
				[
					'label' => __( 'Show on mobile', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'(mobile){{WRAPPER}} {{CURRENT_ITEM}} .ts_comp_label' => 'display: {{VALUE}}',
					],
					'condition' => [ 'label_visibility' => 'yes' ],
				]
			);

			$repeater->add_control(
				'component_visibility',
				[
					'label' => __( 'Component visibility', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => 'no'
				]
			);

			$repeater->add_control(
				'user_bar_visibility_desktop',
				[
					'label' => __( 'Show on desktop', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'(desktop){{WRAPPER}} {{CURRENT_ITEM}}' => 'display: {{VALUE}}',
					],
					'condition' => [ 'component_visibility' => 'yes' ],
				]
			);

			// $repeater->add_control(
			// 	'user_bar_visibility_desktop',
			// 	[
			// 		'label' => __( 'Show on Desktop', 'plugin-domain' ),
			// 		'type' => \Elementor\Controls_Manager::SWITCHER,
			// 		'label_on' => __( 'Show', 'your-plugin' ),
			// 		'label_off' => __( 'Hide', 'your-plugin' ),
			// 		'return_value' => 'flex',
			// 		'default' => 'none',
			// 		'selectors' => [
			// 			'(desktop){{WRAPPER}} {{CURRENT_ITEM}}' => 'display: {{VALUE}}',
			// 		],
			// 		'condition' => [ 'component_visibility' => 'yes' ],
			// 	]
			// );


			$repeater->add_control(
				'user_bar_visibility_tablet',
				[
					'label' => __( 'Show on tablet', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'(tablet){{WRAPPER}} {{CURRENT_ITEM}}' => 'display: {{VALUE}}',
					],
					'condition' => [ 'component_visibility' => 'yes' ],
				]
			);


			// $repeater->add_control(
			// 	'user_bar_visibility_tablet',
			// 	[
			// 		'label' => __( 'Show on tablet', 'plugin-domain' ),
			// 		'type' => \Elementor\Controls_Manager::SWITCHER,
			// 		'label_on' => __( 'Show', 'your-plugin' ),
			// 		'label_off' => __( 'Hide', 'your-plugin' ),
			// 		'return_value' => 'flex',
			// 		'default' => 'none',
			// 		'selectors' => [
			// 			'(tablet){{WRAPPER}} {{CURRENT_ITEM}}' => 'display: {{VALUE}}',
			// 		],
			// 		'condition' => [ 'component_visibility' => 'yes' ],
			// 	]
			// );


			$repeater->add_control(
				'user_bar_visibility_mobile',
				[
					'label' => __( 'Show on tablet', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'flex'  => __( 'Show', 'plugin-domain' ),
						'none' => __( 'Hide', 'plugin-domain' ),
					],

					'selectors' => [
						'(mobile){{WRAPPER}} {{CURRENT_ITEM}}' => 'display: {{VALUE}}',
					],
					'condition' => [ 'component_visibility' => 'yes' ],
				]
			);


			// $repeater->add_control(
			// 	'user_bar_visibility_mobile',
			// 	[
			// 		'label' => __( 'Show on Mobile', 'plugin-domain' ),
			// 		'type' => \Elementor\Controls_Manager::SWITCHER,
			// 		'label_on' => __( 'Show', 'your-plugin' ),
			// 		'label_off' => __( 'Hide', 'your-plugin' ),
			// 		'return_value' => 'flex',
			// 		'default' => 'none',
			// 		'selectors' => [
			// 			'(mobile){{WRAPPER}} {{CURRENT_ITEM}}' => 'display: {{VALUE}}',
			// 		],
			// 		'condition' => [ 'component_visibility' => 'yes' ],
			// 	]
			// );

			


			$this->add_control(
				'ts_userbar_items',
				[
					'label' => __( 'Items', 'elementor' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
				]
			);

		$this->end_controls_section();

		/* User area action styling */

		$this->start_controls_section(
			'ts_action_styling',
			[
				'label' => __( 'User area: General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'ts_action_styling_tabs'
			);

				/* Normal tab */

				$this->start_controls_tab(
					'ts_action_styling_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_action_justify',
						[
							'label' => __( 'Align items', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-user-area > ul' => 'justify-content: {{VALUE}}',
							],
						]
					);

					




					$this->add_control(
						'ts_comp_items',
						[
							'label' => __( 'Item', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_comp_orientation',
						[
							'label' => __( 'Item content orientation', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SWITCHER,
							'label_on' => __( 'Vertical', 'your-plugin' ),
							'label_off' => __( 'Horizontal', 'your-plugin' ),
							'return_value' => 'column',
							'default' => 'initial',
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a' => 'flex-direction: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_comp_col_align',
						[
							'label' => __( 'Align item content', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'left',
							'options' => [
								'left'  => __( 'Left', 'plugin-domain' ),
								'center' => __( 'Center', 'plugin-domain' ),
								'right' => __( 'Right', 'plugin-domain' ),
							],

							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a' => 'align-items: {{VALUE}}',
							],
							'condition' => [ 'ts_comp_orientation' => 'column' ],
						]
					);

					$this->add_responsive_control(
						'ts_link_margin',
						[
							'label' => __( 'Margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_link_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_navbar_link_bg',
						[
							'label' => __( 'Item background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_navbar_link_border',
						[
							'label' => __( 'Item border radius', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-user-area > ul > li > a' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_navbar_link_shadow',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-user-area > ul > li > a',
						]
					);

					$this->add_control(
						'ts_comp_icon_heading',
						[
							'label' => __( 'Item icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_action_icon_con_size',
						[
							'label' => __( 'Container size', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 30,
									'max' => 80,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 40,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-user-area .ts-comp-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_icon_con_radius',
						[
							'label' => __( 'Container border radius', 'plugin-domain' ),
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
								'size' => 40,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-user-area .ts-comp-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_action_icon_con_bg',
						[
							'label' => __( 'Container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area .ts-comp-icon' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
							'description' => __( 'Must be equal or smaller than icon container', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 20,
									'max' => 50,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 28,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-user-area .ts-comp-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .ts-user-area .ts-comp-icon > svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_action_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area .ts-comp-icon > i' => 'color: {{VALUE}}',
								'{{WRAPPER}} .ts-user-area .ts-comp-icon > svg' => 'fill: {{VALUE}}',
							],
						]
					);


					

					$this->add_control(
						'ts_action_indicator_color',
						[
							'label' => __( 'Unread indicator color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area span.unread-indicator' => 'background: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_indicator_margin',
						[
							'label' => __( 'Indicator top margin', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-user-area span.unread-indicator' => 'top: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_indicator_size',
						[
							'label' => __( 'Indicator size', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-user-area span.unread-indicator' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_action_avatar',
						[
							'label' => __( 'Avatar', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_responsive_control(
						'ts_action_avatar_size',
						[
							'label' => __( 'Avatar size', 'plugin-domain' ),
							'description' => __( 'Must be equal or smaller than icon container', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-user-area > ul > li.ts-user-area-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_avatar_radius',
						[
							'label' => __( 'Avatar radius', 'plugin-domain' ),
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
								'{{WRAPPER}} .ts-user-area > ul > li.ts-user-area-avatar img' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_comp_item_text',
						[
							'label' => __( 'Item label', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					
					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_action_text',
							'label' => __( 'Typography', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-user-area > ul > li > a > p',
						]
					);

					$this->add_control(
						'ts_action_text_color',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a > p' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_text_margin',
						[
							'label' => __( 'Left margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 20,
									'max' => 50,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts_comp_label' => 'padding-left: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_comp_item_chevron',
						[
							'label' => __( 'Chevron', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_dropdown_icon_color',
						[
							'label' => __( 'Chevron color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-popup-component .ts-has-children-icon' => 'color: {{VALUE}}',
							],
						]
					);


					$this->add_control(
						'ts_component_chevron',
						[
							'label' => __( 'Icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-angle-down',
								'library' => 'la-solid',
							],
						]
					);

					$this->add_responsive_control(
						'ts_chevron_size',
						[
							'label' => __( 'Chevron size', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 10,
									'max' => 50,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 16,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-popup-component .ts-has-children-icon' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_responsive_control(
						'ts_chevron_margin',
						[
							'label' => __( 'Chevron left margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px'],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 50,
									'step' => 1,
								],
							],
							'default' => [
								'unit' => 'px',
								'size' => 5,
							],
							'selectors' => [
								'{{WRAPPER}} .ts-popup-component .ts-has-children-icon' => 'padding-left: {{SIZE}}{{UNIT}};',
							],
						]
					);

					

				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'ts_action_styling_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_navbar_link_bg_h',
						[
							'label' => __( 'Item background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'default' => '#fff',
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a:hover' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_action_icon_con_bg_hover',
						[
							'label' => __( 'Icon container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a:hover .ts-comp-icon' => 'background-color: {{VALUE}}',
							],
						]
					);


					$this->add_control(
						'ts_action_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a:hover .ts-comp-icon i' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_action_text_color_h',
						[
							'label' => __( 'Text color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-user-area > ul > li > a:hover p' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'ts_navbar_link_shadow_h',
							'label' => __( 'Box Shadow', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-user-area > ul > li > a:hover',
						]
					);

				$this->end_controls_tab();
			$this->end_controls_tabs();

		$this->end_controls_section();




		

		
		
	}

	protected function render( $instance = [] ) {
		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/user-bar.php' );

		if ( \Voxel\is_edit_mode() ) {
			printf( '<script type="text/javascript">%s</script>', 'window.render_static_popups();' );
		}
	}

	public function get_style_depends() {
		return [ 'vx:user-area.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
