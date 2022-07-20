<?php

namespace Voxel\Controllers\Frontend;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Search_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_search_posts', '@search_posts' );
		$this->on( 'voxel_ajax_nopriv_search_posts', '@search_posts' );
	}

	protected function search_posts() {
		$results = \Voxel\get_search_results( $_GET, [
			'limit' => $_GET['limit'] ?? 10,
		] );
		echo $results['render'];
		printf(
			'<script class="info" data-has-prev="%s" data-has-next="%s"></script>',
			$results['has_prev'] ? 'true' : 'false',
			$results['has_next'] ? 'true' : 'false'
		);
	}
}
