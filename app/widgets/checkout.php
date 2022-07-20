<?php

namespace Voxel\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Checkout extends Base_Widget {

	public function get_name() {
		return 'ts-checkout';
	}

	public function get_title() {
		return __( 'Checkout (27)', 'my-listing' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'voxel', 'basic' ];
	}

	protected function register_controls() {
		//
	}

	protected function render( $instance = [] ) {
		require locate_template( 'templates/widgets/checkout.php' );
	}

	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
