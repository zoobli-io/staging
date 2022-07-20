<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class User_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'init', '@register_post_type', 0 );
		$this->on( 'wp_insert_post', '@cache_user_post_stats', 10 );
		$this->on( 'after_delete_post', '@cache_user_post_stats', 10 );
		$this->on( 'get_avatar_url', '@show_custom_avatar', 35, 3 );
		$this->filter( 'show_admin_bar', '@should_show_admin_bar' );
	}

	protected function register_post_type() {
		register_post_type( 'profile', [
			'labels' => [
				'name' => 'Profiles',
				'singular_name' => 'Profile',
			],
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			'query_var'           => true,
			'supports'            => ['author'],
			'menu_position'       => 70,
			'delete_with_user'    => true,
			'_is_created_by_voxel' => true,
			'has_archive' => true,
			'rewrite' => [
				'slug' => 'profiles',
			],
		] );
	}

	protected function cache_user_post_stats( $post_id ) {
		$post = \Voxel\Post::get( $post_id );
		if ( $post && $post->post_type && $post->post_type->is_managed_by_voxel() ) {
			\Voxel\cache_user_post_stats( $post->get_author_id() );
		}
	}

	protected function show_custom_avatar( $url, $id_or_email, $args ) {
		if ( (bool) $args['force_default'] === true ) {
			return $url;
		}

		if ( ! ( $user = \Voxel\get_user_by_id_or_email( $id_or_email ) ) ) {
			return $url;
		}

		$avatar_id = $user->get_avatar_id();
		$avatar_url = wp_get_attachment_image_url( $avatar_id, 'thumbnail' );
		if ( $avatar_id && $avatar_url ) {
			return $avatar_url;
		}

		return $url;
	}

	protected function should_show_admin_bar( $should_show ) {
		$user = \Voxel\current_user();
		if ( ! ( $user && ( $user->has_role( 'administrator' ) || $user->has_role( 'editor' ) ) ) ) {
			return false;
		}

		return $should_show;
	}
}
