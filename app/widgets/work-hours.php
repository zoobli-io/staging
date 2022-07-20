<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Work_Hours extends Base_Widget {

	public function get_name() {
		return 'ts-work-hours';
	}

	public function get_title() {
		return __( 'Work hours (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'ts_wh_general',
			[
				'label' => __( 'General', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'ts_wh_collapse',
				[
					'label' => __( 'Collapse', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'wh-default',
					'options' => [
						'wh-default'  => __( 'Yes', 'plugin-domain' ),
						'wh-expanded' => __( 'No', 'plugin-domain' ),
					],
				]
			);

		$post_type = \Voxel\get_current_post_type();
		if ( $post_type ) {
			$options = [ '' => 'Choose field' ];
			foreach ( $post_type->get_fields() as $field ) {
				if ( $field->get_type() === 'work-hours' ) {
					$options[ $field->get_key() ] = $field->get_label();
				}
			}

			$this->add_control( 'ts_source_field', [
				'label' => __( 'Work hours field', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'work-hours',
				'label_block' => true,
				'options' => $options,
			] );
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_wh_open',
			[
				'label' => __( 'Open', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'ts_wh_open_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-door-open',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_wh_open_text',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Open now', 'plugin-domain' ),
					'placeholder' => __( 'Enter label', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'ts_wh_open_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.open i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_open_icon_con_bg',
				[
					'label' => __( 'Icon container background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.open .wh-icon-con' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_open_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.open p' => 'color: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_wh_opening',
			[
				'label' => __( 'Opening soon', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'ts_wh_opening_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-clock',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_wh_opening_text',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Opening soon', 'plugin-domain' ),
					'placeholder' => __( 'Enter label', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'ts_wh_opening_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.opening-soon i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_opening_icon_con_bg',
				[
					'label' => __( 'Icon container background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.opening-soon .wh-icon-con' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_opening_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.opening-soon p' => 'color: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_wh_closed',
			[
				'label' => __( 'Closed', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'ts_wh_closed_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-door-closed',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_wh_closed_text',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Closed', 'plugin-domain' ),
					'placeholder' => __( 'Enter label', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'ts_wh_closed_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.closed i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_closed_icon_con_bg',
				[
					'label' => __( 'Icon container background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.closed .wh-icon-con' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_closed_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.closed p' => 'color: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'ts_wh_closing',
			[
				'label' => __( 'Closing soon', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'ts_wh_closing_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-clock',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_wh_closing_text',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Closing soon', 'plugin-domain' ),
					'placeholder' => __( 'Enter label', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'ts_wh_closing_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.closing-soon i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_closing_icon_con_bg',
				[
					'label' => __( 'Icon container background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.closing-soon .wh-icon-con' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_closing_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.closing-soon p' => 'color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'ts_wh_appt',
				[
					'label' => __( 'Appointment only', 'plugin-name' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'ts_wh_appt_icon',
				[
					'label' => __( 'Icon', 'text-domain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'las la-calendar-alt',
						'library' => 'la-solid',
					],
				]
			);

			$this->add_control(
				'ts_wh_appt_text',
				[
					'label' => __( 'Label', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Appointment only', 'plugin-domain' ),
					'placeholder' => __( 'Enter label', 'plugin-domain' ),
				]
			);

			$this->add_control(
				'ts_wh_appt_icon_color',
				[
					'label' => __( 'Icon color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.appt-only i' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_appt_icon_con_bg',
				[
					'label' => __( 'Icon container background', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.appt-only .wh-icon-con' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'ts_wh_appt_text_color',
				[
					'label' => __( 'Text color', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ts-open-status.appt-only p' => 'color: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();



	}

	protected function render( $instance = [] ) {
		$post = \Voxel\get_current_post();
		$field = $post ? $post->get_field( $this->get_settings_for_display( 'ts_source_field' ) ) : null;
		if ( ! ( $post && $field && $field->get_type() === 'work-hours' ) ) {
			return;
		}

		$schedule = $field->get_schedule();
		if ( ! $schedule ) {
			return;
		}

		$is_open_now = $field->is_open_now();
		$weekdays = \Voxel\get_weekdays();
		$keys = array_flip( \Voxel\get_weekday_indexes() );
		$timezone = $post->get_timezone();
		$local_time = new \DateTime( 'now', $timezone );
		$today = $keys[ $local_time->format('w') ];

		wp_print_styles( $this->get_style_depends() );
		require locate_template( 'templates/widgets/work-hours.php' );
	}

	public function get_style_depends() {
		return [ 'vx:work-hours.css' ];
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
