<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Term_Order_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'voxel/backend/screen:reorder-terms', '@render_reorder_screen', 30 );
		$this->on( 'admin_post_voxel_save_term_order', '@save_term_order' );
		$this->filter( 'get_terms_orderby', '@apply_custom_order', 100, 3 );
		$this->filter( 'admin_footer', '@add_reorder_link' );
	}

	protected function render_reorder_screen() {
		$key = $_GET['taxonomy'] ?? null;
		$taxonomy = \Voxel\Taxonomy::get( $key );
		if ( ! ( $key && $taxonomy ) ) {
			return;
		}

		wp_enqueue_script( 'sortable' );
		wp_enqueue_script( 'vue-draggable' );

		$terms = \Voxel\get_terms( $taxonomy->get_key(), [
			'fields' => [ 'id', 'label', 'slug', 'parent', 'order' ],
		] );

		require locate_template( 'templates/backend/taxonomies/reorder-terms.php' );
	}

	protected function save_term_order() {
		check_admin_referer( 'voxel_save_term_order' );
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		if ( empty( $_POST['taxonomy'] ) ) {
			die;
		}

		$taxonomy_key = sanitize_text_field( $_POST['taxonomy'] );
		$taxonomy = \Voxel\Taxonomy::get( $taxonomy_key );
        if ( ! ( $taxonomy_key && $taxonomy ) || empty( $_POST['terms'] ) ) {
        	die;
        }

		$terms = json_decode( stripslashes( $_POST['terms'] ), true );

		// create term order array
		$order = [];
		foreach ( $terms as $index => $term ) {
			$this->_get_term_order( $term, $index, $order );
		}

		if ( empty( $order ) ) {
			die;
		}

		// generate sql
		global $wpdb;

		$cases = [];
		foreach ( $order as $term_id => $term_order ) {
			$cases[] = sprintf( ' WHEN term_id = %1$d THEN %2$d ', $term_id, $term_order );
		}

		$cases = join( "\n", $cases );
		$sql = "UPDATE {$wpdb->terms} SET `voxel_order` = ( CASE \n{$cases}\n END )";
		$wpdb->query( $sql );

		$taxonomy->update_version();

		wp_safe_redirect( admin_url( 'admin.php?page=voxel-taxonomies&action=reorder-terms&taxonomy='.$taxonomy->get_key() ) );
		die;
	}

	private function _get_term_order( $term, $index, &$order ) {
		$order[ (int) $term['id'] ] = $index;

		foreach ( $term['terms'] as $index => $item ) {
			$this->_get_term_order( $item, $index, $order );
		}

		return $order;
	}

	protected function apply_custom_order( $orderby, $query_vars, $taxonomy ) {
		if ( is_admin() && function_exists('get_current_screen') && ( $screen = get_current_screen() ) && $screen->base === 'edit-tags' ) {
			return 't.voxel_order, t.name';
		}

		return $orderby;
	}

	protected function add_reorder_link() {
		if ( get_current_screen()->base !== 'edit-tags' ) {
			return;
		}
		$href = admin_url( 'admin.php?page=voxel-taxonomies&action=reorder-terms&taxonomy='.get_current_screen()->taxonomy );
		?>
		<script type="text/javascript">
			jQuery('.tablenav.top .bulkactions').append(
				jQuery('<a></a>')
					.addClass('button')
					.attr('href', <?= wp_json_encode( $href ) ?>)
					.text('Reorder terms')
			);
		</script>
		<?php
	}
}
