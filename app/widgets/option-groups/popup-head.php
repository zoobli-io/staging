<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Head {

	public static function controls( $widget ) {
		$widget->start_controls_section(
			'popup_head_section',
			[
				'label' => __( 'Popup: Head', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->add_responsive_control(
				'ts_head_padding',
				[
					'label' => __( 'Padding', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'.ts-popup-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$widget->add_control(
				'pg_popup_title',
				[
					'label' => __( 'Popup title', 'plugin-name' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$widget->add_responsive_control(
				'pg_title_icon_size',
				[
					'label' => __( 'Icon size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 40,
							'step' => 1,
						],
					],
					'selectors' => [
						'.ts-popup-name i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_responsive_control(
				'pg_title_icon_margin',
				[
					'label' => __( 'Icon right margin', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 40,
							'step' => 1,
						],
					],
					'selectors' => [
						'.ts-popup-head .ts-popup-name > i, .ts-popup-head .ts-popup-name > svg,
						.ts-popup-head .ts-popup-name > img, .ts-popup-head .ts-popup-name > span' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);


			$widget->add_control(
				'pg_title_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-popup-name i' => 'color: {{VALUE}}',
					],
				]
			);

			

			$widget->add_control(
				'pg_title_color',
				[
					'label' => __( 'Title color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-popup-name p, .ts-popup-name p a' => 'color: {{VALUE}}',
					],
				]
			);

			$widget->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'pg_title_typo',
					'label' => __( 'Title typography', 'plugin-domain' ),
					'selector' => '.ts-popup-name p, .ts-popup-name p a',
				]
			);

			$widget->add_responsive_control(
				'pg_title_avatar_size',
				[
					'label' => __( 'Avatar size', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 40,
							'step' => 1,
						],
					],
					'selectors' => [
						'.ts-popup-head .ts-popup-name img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_responsive_control(
				'pg_title_avatar_radius',
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
						'.ts-popup-head .ts-popup-name img' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$widget->add_control(
				'pg_title_separator',
				[
					'label' => __( 'Separator color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'.ts-popup-head' => 'border-color: {{VALUE}}',
					],

				]
			);

			


		$widget->end_controls_section();

	}
}
