<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Print_Template extends Base_Widget {

	public function get_name() {
		return 'ts-print-template';
	}

	public function get_title() {
		return __( 'Print template (27)', 'voxel' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {

		$this->start_controls_section( 'ts_print_template', [
			'label' => __( 'Print an Elementor template', 'voxel' ),
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		] );


		$this->add_control( 'ts_template_id', [
			'label' => __( 'Template ID', 'elementor' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
		] );

		$this->end_controls_section();
	}

	protected function render( $instance = [] ) {
		if ( $template_id = $this->get_settings_for_display( 'ts_template_id' ) ) {
			\Voxel\print_template( $template_id );
		}
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
