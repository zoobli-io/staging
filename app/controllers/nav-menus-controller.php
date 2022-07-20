<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Nav_Menus_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'init', '@register_menus' );
		$this->on( 'wp_nav_menu_item_custom_fields', '@add_custom_fields', 100, 5 );
		$this->on( 'wp_update_nav_menu_item', '@save_custom_fields', 100, 3 );
		$this->on( 'wp_update_nav_menu_item', '@save_custom_fields', 100, 3 );
		$this->on( 'wp_setup_nav_menu_item', '@render_nav_menu_tags' );
	}

	protected function register_menus() {
		register_nav_menus( [
			'voxel-desktop-menu' => __( 'Desktop Menu', 'voxel' ),
			'voxel-mobile-menu' => __( 'Mobile menu', 'voxel' ),
			'voxel-user-menu' => __( 'User Dashboard Menu', 'voxel' ),
			'voxel-create-menu' => __( 'Create post menu', 'voxel' ),
		] );
	}

	protected function add_custom_fields( $item_id, $item, $depth, $args, $id ) {
		$icon_string = get_post_meta( $item_id, '_voxel_item_icon', true );
		$label = get_post_meta( $item_id, '_voxel_item_label', true );
		$url = get_post_meta( $item_id, '_voxel_item_url', true );
		require locate_template( 'templates/backend/nav-menus/menu-item-fields.php' );
	}

	protected function save_custom_fields( $menu_id, $menu_item_db_id, $args ) {
		$icons = $_POST['voxel_item_icon'] ?? [];
		$icon_string = sanitize_text_field( $icons[ $menu_item_db_id ] ?? '' );
		if ( empty( $icon_string ) ) {
			delete_post_meta( $menu_item_db_id, '_voxel_item_icon' );
		} else {
			update_post_meta( $menu_item_db_id, '_voxel_item_icon', $icon_string );
		}

		$labels = $_POST['voxel_item_label'] ?? [];
		$label = trim( $labels[ $menu_item_db_id ] ?? '' );
		if ( empty( $label ) ) {
			delete_post_meta( $menu_item_db_id, '_voxel_item_label' );
		} else {
			update_post_meta( $menu_item_db_id, '_voxel_item_label', $label );
		}

		$urls = $_POST['voxel_item_url'] ?? [];
		$url = trim( $urls[ $menu_item_db_id ] ?? '' );
		if ( empty( $url ) ) {
			delete_post_meta( $menu_item_db_id, '_voxel_item_url' );
		} else {
			update_post_meta( $menu_item_db_id, '_voxel_item_url', $url );
		}
	}

	protected function render_nav_menu_tags( $item ) {
		if ( is_admin() && ! \Voxel\is_edit_mode() && ! \Voxel\is_elementor_ajax() ) {
			return $item;
		}

		if ( ! empty( $item->_voxel_item_label ) ) {
			$item->title = \Voxel\render( $item->_voxel_item_label );
		}

		if ( ! empty( $item->_voxel_item_url ) ) {
			$item->url = \Voxel\render( $item->_voxel_item_url );
		}

		return $item;
	}

}
