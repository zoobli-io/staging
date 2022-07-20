<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Navbar extends Base_Widget {

	public function get_name() {
		return 'ts-navbar';
	}

	public function get_title() {
		return __( 'Navbar (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {



		/*
		=========================
		Navbar(27) widget options
		=========================
		*/






		/* 
		=======
		Source
		=======
		*/

		$this->start_controls_section(
			'ts_navbar_source',
			[
				'label' => __( 'Source', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'navbar_choose_source',
				[
					'label' => __( 'Choose source', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'add_links_manually',
					'options' => [
						'add_links_manually' => __( 'Add links manually', 'plugin-domain' ),
						'select_wp_menu'  => __( 'Select existing menu', 'plugin-domain' ),
					],
				]
			);

			$this->add_control(
				'ts_choose_menu',
				[
					'label' => __( 'Choose menu', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'condition' => [ 'navbar_choose_source' => 'select_wp_menu' ],
					'options' => get_registered_nav_menus(),
					'default' => 'voxel-desktop-menu',
				]
			);

			$this->add_control(
				'ts_choose_mobile_menu',
				[
					'label' => __( 'Choose mobile menu', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'condition' => [ 'navbar_choose_source' => 'select_wp_menu' ],
					'options' => get_registered_nav_menus(),
					'default' => 'voxel-mobile-menu',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_navbar_settings',
			[
				'label' => __( 'Settings', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


			$this->add_control(
				'ts_navbar_orientation',
				[
					'label' => __( 'Orientation', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => [
						'horizontal'  => __( 'Horizontal', 'plugin-domain' ),
						'vertical' => __( 'Vertical', 'plugin-domain' ),
					],
				]
			);

			$this->add_control(
				'ts_navbar_justify',
				[
					'label' => __( 'Justify', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'condition' => [ 'ts_navbar_orientation' => 'horizontal' ],
					'options' => [
						'left'  => __( 'Left', 'plugin-domain' ),
						'center' => __( 'Center', 'plugin-domain' ),
						'flex-end' => __( 'Right', 'plugin-domain' ),
						'space-between' => __( 'Space between', 'plugin-domain' ),
						'space-around' => __( 'Space around', 'plugin-domain' ),
					],

					'selectors' => [
						'{{WRAPPER}} .ts-nav' => 'justify-content: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_burger_settings',
				[
					'label' => __( 'Hamburger menu', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [ 'navbar_choose_source' => 'select_wp_menu' ],

				]
			);


			$this->add_control(
				'show_burger_desktop',
				[
					'label' => __( 'Show on desktop', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'condition' => [ 'navbar_choose_source' => 'select_wp_menu' ],
					'selectors' => [
						'(desktop){{WRAPPER}} .ts-mobile-menu' => 'display: flex;',
						'(desktop){{WRAPPER}} .ts-wp-menu .menu-item' => 'display: none;',
					],
				]
			);

			$this->add_control(
				'show_burger_tablet',
				[
					'label' => __( 'Show on tablet and mobile', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'return_value' => 'yes',
					'condition' => [ 'navbar_choose_source' => 'select_wp_menu' ],
					'default' => 'yes',
					'selectors' => [
						'(tablet){{WRAPPER}} .ts-mobile-menu' => 'display: flex;',
						'(tablet){{WRAPPER}} .ts-wp-menu .menu-item' => 'display: none;',
					],
				]
			);

			$this->add_control(
				'show_menu_label',
				[
					'label' => __( 'Show label?', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'your-plugin' ),
					'label_off' => __( 'Hide', 'your-plugin' ),
					'condition' => [ 'navbar_choose_source' => 'select_wp_menu' ],
					'return_value' => 'yes',
					'default' => 'no',
				]
			);

			$this->add_control(
				'ts_mobile_menu_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'condition' => [ 'navbar_choose_source' => 'select_wp_menu' ],
					'default' => [
						'value' => 'las la-bars',
						'library' => 'la-bars',
					],
				]
			);

		

		$this->end_controls_section();





		/* 
		================
		Content repeater
		=================
		*/

		$this->start_controls_section(
			'ts_navbar_content',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [ 'navbar_choose_source' => 'add_links_manually' ],
			]
		);


			$repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'ts_navbar_item',
				[
					'label' => __( 'Navbar item', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',

				]
			);


			$repeater->add_control(
				'ts_navbar_item_text',
				[
					'label' => __( 'Title', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Action', 'plugin-domain' ),
					'placeholder' => __( 'Action title', 'plugin-domain' ),
				]
			);

			$repeater->add_control(
				'ts_navbar_item_icon',
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
				'ts_navbar_item_link',
				[
					'label' => __( 'Link', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::URL,
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
				'navbar_item__active',
				[
					'label' => __( 'Active?', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'your-plugin' ),
					'label_off' => __( 'Off', 'your-plugin' ),
					'return_value' => 'current-menu-item',

				]
			);




			$this->add_control(
				'ts_navbar_items',
				[
					'label' => __( 'Items', 'elementor' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
				]
			);

		$this->end_controls_section();





		/* 
		===============
		Navbar: General
		===============
		*/

		$this->start_controls_section(
			'ts_nav_style',
			[
				'label' => __( 'Navbar: General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->start_controls_tabs(
				'style_tabs'
			);
				/* Normal tab */

				$this->start_controls_tab(
					'style_normal_tab',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					
					$this->add_control(
						'ts_comp_text',
						[
							'label' => __( 'Menu item', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_content_typography',
							'label' => __( 'Typography', 'plugin-domain' ),
							'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
							'selector' => '{{WRAPPER}} .ts-item-link',
						]
					);

					$this->add_control(
						'ts_navbar_color',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link p' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_navbar_link_bg',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_link_margin',
						[
							'label' => __( 'Margin', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'{{WRAPPER}} .ts-item-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
								'{{WRAPPER}} .ts-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Border::get_type(),
						[
							'name' => 'ts_navbar_border',
							'label' => __( 'Border', 'plugin-domain' ),
							'selector' => '{{WRAPPER}} .ts-nav-menu .ts-item-link',
						]
					);

					$this->add_responsive_control(
						'ts_navbar_border_radius',
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
								'{{WRAPPER}} .ts-item-link' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);



					$this->add_control(
						'ts_comp_icon_heading',
						[
							'label' => __( 'Menu item icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_action_icon_show',
						[
							'label' => __( 'Show icon', 'plugin-domain' ),
							'description' => __( 'Desktop only', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::SELECT,
							'default' => 'flex',
							'options' => [
								'flex'  => __( 'Yes', 'plugin-domain' ),
								'none' => __( 'No', 'plugin-domain' ),
							],

							'selectors' => [
								'{{WRAPPER}} .menu-item .ts-item-icon' => 'display: {{VALUE}}',
							],
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
							'selectors' => [
								'{{WRAPPER}} .ts-item-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
							'selectors' => [
								'{{WRAPPER}} .ts-item-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_action_icon_con_bg',
						[
							'label' => __( 'Container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-icon' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_responsive_control(
						'ts_action_icon_margin',
						[
							'label' => __( 'Container right margin', 'plugin-domain' ),
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
								'{{WRAPPER}}  .ts-item-link .ts-item-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
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
							'selectors' => [
								'{{WRAPPER}} .ts-item-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
								'{{WRAPPER}} .ts-item-icon > svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$this->add_control(
						'ts_action_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-icon > i' => 'color: {{VALUE}};',
								'{{WRAPPER}} .ts-item-icon > svg' => 'fill: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'ts_menu_hscroll',
						[
							'label' => __( 'Horizontal scroll', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_hscroll_color',
						[
							'label' => __( 'Scroll background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-nav-horizontal.min-scroll' => '--ts-scroll-color: {{VALUE}}',
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
						'ts_nav_dropdown_icon',
						[
							'label' => __( 'Icon', 'text-domain' ),
							'type' => \Elementor\Controls_Manager::ICONS,
							'default' => [
								'value' => 'las la-angle-down',
								'library' => 'la-solid',
							],
						]
					);


					$this->add_control(
						'ts_dropdown_icon_color',
						[
							'label' => __( 'Chevron color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link .ts-has-children-icon' => 'color: {{VALUE}}',
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
							'selectors' => [
								'{{WRAPPER}} .ts-item-link .ts-has-children-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
							'selectors' => [
								'{{WRAPPER}} .ts-item-link .ts-has-children-icon' => 'padding-left: {{SIZE}}{{UNIT}};',
							],
						]
					);


					
				$this->end_controls_tab();

				/* Hover tab */

				$this->start_controls_tab(
					'style_hover_tab',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					$this->add_control(
						'ts_comp_text_hover',
						[
							'label' => __( 'Menu item', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_navbar_color_hover',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link:hover p' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_navbar_link_bg_hover',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link:hover' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_navbar_link_border_hover',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link:hover' => 'border-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_dropdown_icon_color_hover',
						[
							'label' => __( 'Chevron color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link:hover .ts-has-children-icon' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_comp_icon_heading_hover',
						[
							'label' => __( 'Menu item icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_action_icon_color_hover',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link:hover .ts-item-icon > i' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_action_icon_con_bg_hover',
						[
							'label' => __( 'Item container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .ts-item-link:hover .ts-item-icon' => 'background-color: {{VALUE}}',
							],
						]
					);

				$this->end_controls_tab();

				/* Current item tab */

				$this->start_controls_tab(
					'style_active_tab',
					[
						'label' => __( 'Current', 'plugin-name' ),
					]
				);

					
					$this->add_control(
						'ts_comp_text_current',
						[
							'label' => __( 'Menu item', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_content_typography_c',
							'label' => __( 'Typography', 'plugin-domain' ),
							'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
							'selector' => '{{WRAPPER}} li.current-menu-item > .ts-item-link',
						]
					);

					$this->add_control(
						'ts_navbar_color_current',
						[
							'label' => __( 'Color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}}  .current-menu-item .ts-item-link p' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_navbar_link_bg_current',
						[
							'label' => __( 'Background color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .current-menu-item  .ts-item-link' => 'background-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_navbar_link_border_current',
						[
							'label' => __( 'Border color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .current-menu-item .ts-item-link' => 'border-color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_dropdown_icon_color_current',
						[
							'label' => __( 'Chevron color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .current-menu-item .ts-has-children-icon' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_comp_icon_heading_current',
						[
							'label' => __( 'Menu item icon', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$this->add_control(
						'ts_action_icon_color_current',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .current-menu-item .ts-item-icon > i, {{WRAPPER}} .current-menu-item .ts-item-link:hover .ts-item-icon > i' => 'color: {{VALUE}}',
							],
						]
					);

					$this->add_control(
						'ts_action_icon_con_bg_current',
						[
							'label' => __( 'Item container background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .current-menu-item .ts-item-icon, {{WRAPPER}} .current-menu-item .ts-item-link:hover .ts-item-icon' => 'background-color: {{VALUE}}',
							],
						]
					);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();


	}

	protected function render( $instance = [] ) {
		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/navbar.php' );

		if ( \Voxel\is_edit_mode() ) {
			printf( '<script type="text/javascript">%s</script>', 'window.render_static_popups();' );
		}
	}

	public function get_style_depends() {
		return [ 'vx:nav-menu.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
