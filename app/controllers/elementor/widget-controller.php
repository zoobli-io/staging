<?php

namespace Voxel\Controllers\Elementor;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Widget_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'elementor/element/common/_section_style/after_section_end', '@register_widget_settings' );
	}

	protected function register_widget_settings( $widget ) {
		$widget->start_controls_section( '_voxel_widget_settings', [
			'label' => __( 'Widget options', 'voxel' ),
			'tab' => 'tab_voxel',
		] );

		/* Container sticky options */
		// $widget->add_control( 'sticky_option', [
		// 	'label' => __( 'Sticky position', 'plugin-name' ),
		// 	'type' => \Elementor\Controls_Manager::HEADING,
		// 	'separator' => 'before',
		// ] );

		// $widget->add_control( 'sticky_container', [
		// 	'label' => __( 'Enable?', 'plugin-domain' ),
		// 	'type' => \Elementor\Controls_Manager::SWITCHER,
		// 	'return_value' => 'sticky',
		// 	'selectors' => [
		// 		'{{WRAPPER}}' => 'position:{{VALUE}};',
		// 	],
		// ] );

		// $widget->add_responsive_control( 'sticky_top_value', [
		// 	'label' => __( 'Top', 'plugin-domain' ),
		// 	'type' => \Elementor\Controls_Manager::SLIDER,
		// 	'condition' => [ 'sticky_container' => 'sticky' ],
		// 	'size_units' => [ 'px', '%', 'vh'],
		// 	'range' => [
		// 		'px' => [
		// 			'min' => 0,
		// 			'max' => 500,
		// 			'step' => 1,
		// 		],
		// 	],
		// 	'selectors' => [
		// 		'{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
		// 	],
		// ] );

		// $widget->add_responsive_control( 'sticky_left_value', [
		// 	'label' => __( 'Left', 'plugin-domain' ),
		// 	'type' => \Elementor\Controls_Manager::SLIDER,
		// 	'condition' => [ 'sticky_container' => 'sticky' ],
		// 	'size_units' => [ 'px', '%', 'vh'],
		// 	'range' => [
		// 		'px' => [
		// 			'min' => 0,
		// 			'max' => 500,
		// 			'step' => 1,
		// 		],
		// 	],
		// 	'selectors' => [
		// 		'{{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}};',
		// 	],
		// ] );

		// $widget->add_responsive_control( 'sticky_right_value', [
		// 	'label' => __( 'Right', 'plugin-domain' ),
		// 	'type' => \Elementor\Controls_Manager::SLIDER,
		// 	'condition' => [ 'sticky_container' => 'sticky' ],
		// 	'size_units' => [ 'px', '%', 'vh'],
		// 	'range' => [
		// 		'px' => [
		// 			'min' => 0,
		// 			'max' => 500,
		// 			'step' => 1,
		// 		],
		// 	],
		// 	'selectors' => [
		// 		'{{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}};',
		// 	],
		// ] );

		// $widget->add_responsive_control( 'sticky_bottom_value', [
		// 	'label' => __( 'Bottom', 'plugin-domain' ),
		// 	'type' => \Elementor\Controls_Manager::SLIDER,
		// 	'condition' => [ 'sticky_container' => 'sticky' ],
		// 	'size_units' => [ 'px', '%', 'vh'],
		// 	'range' => [
		// 		'px' => [
		// 			'min' => 0,
		// 			'max' => 500,
		// 			'step' => 1,
		// 		],
		// 	],
		// 	'selectors' => [
		// 		'{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}};',
		// 	],
		// ] );

		/* Container sticky options */
		$widget->add_control( 'sticky_option', [
			'label' => __( 'Sticky position', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$widget->add_control( 'sticky_container', [
			'label' => __( 'Enable?', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'sticky',
		] );

		$widget->add_control(
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

		$widget->add_control(
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

		$widget->add_control(
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



		$widget->add_responsive_control( 'sticky_top_value', [
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

		$widget->add_responsive_control( 'sticky_left_value', [
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

		$widget->add_responsive_control( 'sticky_right_value', [
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

		$widget->add_responsive_control( 'sticky_bottom_value', [
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

		$widget->end_controls_section();
	}
}
