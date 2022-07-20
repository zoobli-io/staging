<?php

namespace Voxel\Widgets\Option_Groups;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Popup_Notifications {

	public static function controls( $widget ) {
		$widget->start_controls_section(
			'pg_notifications',
			[
				'label' => __( 'Popup: Notifications & Messages', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$widget->start_controls_tabs(
				'pg_notifications_tabs'
			);

				/* Normal tab */

				$widget->start_controls_tab(
					'pg_notifications_normal',
					[
						'label' => __( 'Normal', 'plugin-name' ),
					]
				);

					
					$widget->add_control(
						'pg_notf',
						[
							'label' => __( 'Single notification', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'pg_notf_title_color',
						[
							'label' => __( 'Title color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-notification-list li a .notification-details p' => 'color: {{VALUE}}',
								'.ts-empty-user-tab p' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'pg_notf_title_typo',
							'label' => __( 'Title typography', 'plugin-domain' ),
							'selector' => '.ts-notification-list li a .notification-details p',
						]
					);

					$widget->add_control(
						'pg_notf_subtitle',
						[
							'label' => __( 'Subtitle color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-notification-list li a .notification-details span' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'pg_notf_subtitle_typo',
							'label' => __( 'Subitle typography', 'plugin-domain' ),
							'selector' => '.ts-notification-list li a .notification-details span',
						]
					);



					$widget->add_control(
						'pg_notf_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.notification-image i' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_notf_icon_bg',
						[
							'label' => __( 'Icon background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.notification-image ' => 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_not_ico_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
								'.notification-image i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_not_con_size',
						[
							'label' => __( 'Icon container size', 'plugin-domain' ),
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
								'.notification-image' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}; min-width:{{SIZE}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_not_avatar_size',
						[
							'label' => __( 'Avatar size', 'plugin-domain' ),
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
								'.convo-avatar img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}; min-width:{{SIZE}}{{UNIT}};',
							],
						]
					);

					
					$widget->add_control(
						'pg_notf_unread',
						[
							'label' => __( 'Unread notification', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'pg_notf_title_typo_new',
							'label' => __( 'Title typography', 'plugin-domain' ),
							'selector' => 'li.ts-new-notification a .notification-details p',
						]
					);

					$widget->add_control(
						'pg_unread_bg',
						[
							'label' => __( 'Unread background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								' li.ts-new-notification a' => 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_unread_title',
						[
							'label' => __( 'Unread title color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'li.ts-new-notification a .notification-details p' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_unread_icon_color',
						[
							'label' => __( 'Unread icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								' li.ts-new-notification a .notification-image i' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_unread_icon_bg',
						[
							'label' => __( 'Unread icon background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'li.ts-new-notification a .notification-image' => 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'ts_empty_notifications',
						[
							'label' => __( 'No notifications', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_responsive_control(
						'ts_empty_notf_padding',
						[
							'label' => __( 'Padding', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%', 'em' ],
							'selectors' => [
								'.ts-empty-user-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$widget->add_control(
						'ts_empty_icon_size',
						[
							'label' => __( 'Icon size', 'plugin-domain' ),
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
								'size' => 35,
							],
							'selectors' => [
								'.ts-empty-user-tab i' => 'font-size: {{SIZE}}{{UNIT}};',
							],
						]
					);


					$widget->add_control(
						'ts_empty_icon_color',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-empty-user-tab i' => 'color: {{VALUE}}',
							],
						]
					);

					$widget->add_control(
						'ts_empty_title_color',
						[
							'label' => __( 'Title color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-empty-user-tab p' => 'color: {{VALUE}}',
							],
						]
					);

					$widget->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'name' => 'ts_empty_title_text',
							'label' => __( 'Title typography', 'plugin-domain' ),
							'selector' => '.ts-empty-user-tab p',
						]
					);

					


				$widget->end_controls_tab();

				/* Hover tab */

				$widget->start_controls_tab(
					'pg_notifications_hover',
					[
						'label' => __( 'Hover', 'plugin-name' ),
					]
				);

					

					$widget->add_control(
						'pg_notf_h',
						[
							'label' => __( 'Notifications/Messages item', 'plugin-name' ),
							'type' => \Elementor\Controls_Manager::HEADING,
							'separator' => 'before',
						]
					);

					$widget->add_control(
						'pg_notf_bg_h',
						[
							'label' => __( 'Background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-notification-list li a:hover' => 'background-color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_notf_title_color_hover',
						[
							'label' => __( 'Title color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-notification-list li a:hover .notification-details p' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_notf_subtitle_hover',
						[
							'label' => __( 'Subtitle color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-notification-list li a:hover .notification-details span' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_notf_icon_color_h',
						[
							'label' => __( 'Icon color', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-notification-list li a:hover .notification-image i' => 'color: {{VALUE}}',
							],

						]
					);

					$widget->add_control(
						'pg_notf_icon_bg_h',
						[
							'label' => __( 'Icon background', 'plugin-domain' ),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'.ts-notification-list li a:hover .notification-image i' => 'background-color: {{VALUE}}',
							],

						]
					);


				$widget->end_controls_tab();



			$widget->end_controls_tabs();

		$widget->end_controls_section();

	}

}
