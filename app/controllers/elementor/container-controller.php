<?php

namespace Voxel\Controllers\Elementor;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Container_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'elementor/element/container/section_layout/after_section_end', '@register_container_settings' );
	}

	protected function register_container_settings( $container ) {
		$container->start_controls_section( '_voxel_container_settings', [
			'label' => __( 'Container options', 'voxel' ),
			'tab' => 'tab_voxel',
		] );

		/* Container sticky options */
		$container->add_control( 'sticky_option', [
			'label' => __( 'Sticky position', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$container->add_control( 'sticky_container', [
			'label' => __( 'Enable?', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'sticky',
		] );

		$container->add_control(
			'sticky_container_desktop',
			[
				'label' => __( 'Enable on desktop', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'sticky'  => __( 'Enable', 'plugin-domain' ),
					'initial' => __( 'Disable', 'plugin-domain' ),
				],

				'selectors' => [
					'(desktop){{WRAPPER}}' => 'position: {{VALUE}}',
				],
				'condition' => [ 'sticky_container' => 'sticky' ],
			]
		);

		$container->add_control(
			'sticky_container_tablet',
			[
				'label' => __( 'Enable on tablet', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'sticky'  => __( 'Enable', 'plugin-domain' ),
					'initial' => __( 'Disable', 'plugin-domain' ),
				],

				'selectors' => [
					'(tablet){{WRAPPER}}' => 'position: {{VALUE}}',
				],
				'condition' => [ 'sticky_container' => 'sticky' ],
			]
		);

		$container->add_control(
			'sticky_container_mobile',
			[
				'label' => __( 'Enable on mobile', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'sticky'  => __( 'Enable', 'plugin-domain' ),
					'initial' => __( 'Disable', 'plugin-domain' ),
				],

				'selectors' => [
					'(mobile){{WRAPPER}}' => 'position: {{VALUE}}',
				],
				'condition' => [ 'sticky_container' => 'sticky' ],
			]
		);



		$container->add_responsive_control( 'sticky_top_value', [
			'label' => __( 'Top', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
			],
			'condition' => [ 'sticky_container' => 'sticky' ],
		] );

		$container->add_responsive_control( 'sticky_left_value', [
			'label' => __( 'Left', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}};',
			],
			'condition' => [ 'sticky_container' => 'sticky' ],
		] );

		$container->add_responsive_control( 'sticky_right_value', [
			'label' => __( 'Right', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}};',
			],
			'condition' => [ 'sticky_container' => 'sticky' ],
		] );

		$container->add_responsive_control( 'sticky_bottom_value', [
			'label' => __( 'Bottom', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}};',
			],
			'condition' => [ 'sticky_container' => 'sticky' ],
		] );

		$container->add_control( 'con_fixed_Width_heading', [
			'label' => __( 'Fixed width', 'plugin-name' ),
			'description' => __( 'Apply fixed width to this container', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$container->add_responsive_control( 'enable_fixed_Width', [
			'label' => __( 'Enable?', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'fixed_width',
		] );

		$container->add_responsive_control( 'fixed_width_value', [
			'label' => __( 'Width', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'enable_fixed_Width' => 'fixed_width' ],
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 1000,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$container->add_control( 'con_fixed_height_heading', [
			'label' => __( 'Fixed height', 'plugin-name' ),
			'description' => __( 'Apply fixed height to this container', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$container->add_responsive_control( 'enable_fixed_height', [
			'label' => __( 'Enable?', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'fixed_height',
		] );

		$container->add_responsive_control( 'fixed_height_value', [
			'label' => __( 'Height', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'enable_fixed_height' => 'fixed_height' ],
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 1000,
					'step' => 1,
				],
			],
			'selectors' => [
				'{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$container->add_control( 'con_calc_height_heading', [
			'label' => __( 'Calculated dimensions', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$container->add_responsive_control(
			'enable_con_calc_h',
			[
				'label' => __( 'Calculate min height?', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no'
			]
		);

		

		$container->add_responsive_control(
			'mcon_calc_height',
			[
				'label' => esc_html__( 'Calculation', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'calc()', 'plugin-name' ),
				'description' => __( 'Use CSS calc() to calculate min-height e.g calc(100vh - 215px). If you want to disable in responsive mode, just type "initial" to allow elementor height options to have priority', 'plugin-domain' ),
				'selectors' => [
					'{{WRAPPER}}' => 'min-height: {{VALUE}};',
				],
				'condition' => [ 'enable_con_calc_h' => 'yes' ],
			]
		);

		$container->add_responsive_control(
			'enable_con_calc_w',
			[
				'label' => __( 'Calculate width?', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'no'
			]
		);

		$container->add_responsive_control(
			'mcon_calc_width',
			[
				'label' => esc_html__( 'Calculation', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'calc()', 'plugin-name' ),
				'description' => __( 'Use CSS calc() to calculate width e.g calc(100vh - 215px). If you want to disable in responsive mode, just type "initial" to allow elementor height options to have priority', 'plugin-domain' ),
				'selectors' => [
					'{{WRAPPER}}' => 'width: {{VALUE}};',
				],
				'condition' => [ 'enable_con_calc_w' => 'yes' ],
			]
		);


		$container->end_controls_section();
	}
}
