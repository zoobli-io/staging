<?php

namespace Voxel\Dynamic_Tags;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Site_Group extends Base_Group {

	public function get_key(): string {
		return 'site';
	}

	public function get_title(): string {
		return _x( 'Site', 'groups', 'voxel' );
	}

	protected function properties(): array {
		$post_types = [
			'label' => 'Post types',
			'type' => \Voxel\T_OBJECT,
			'properties' => [],
		];

		foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
			$post_types['properties'][ $post_type->get_key() ] = [
				'label' => $post_type->get_label(),
				'type' => \Voxel\T_OBJECT,
				'properties' => [
					'singular' => [
						'label' => 'Singular name',
						'type' => \Voxel\T_STRING,
						'callback' => function() use ($post_type) {
							return $post_type->get_singular_name();
						},
					],

					'plural' => [
						'label' => 'Plural name',
						'type' => \Voxel\T_STRING,
						'callback' => function() use ($post_type) {
							return $post_type->get_plural_name();
						},
					],

					'icon' => [
						'label' => 'Icon',
						'type' => \Voxel\T_STRING,
						'callback' => function() use ($post_type) {
							return $post_type->get_icon();
						},
					],

					'archive' => [
						'label' => 'Archive link',
						'type' => \Voxel\T_URL,
						'callback' => function() use ($post_type) {
							return $post_type->get_archive_link();
						},
					],

					'create' => [
						'label' => 'Create post link',
						'type' => \Voxel\T_URL,
						'callback' => function() use ($post_type) {
							return $post_type->get_create_post_link();
						},
					],
				],
			];
		}

		return [
			'post_types' => $post_types,

			'title' => [
				'label' => 'Title',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return get_bloginfo('name');
				},
			],

			'tagline' => [
				'label' => 'Tagline',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return get_bloginfo('description');
				},
			],

			'url' => [
				'label' => 'URL',
				'type' => \Voxel\T_URL,
				'callback' => function() {
					return get_bloginfo('url');
				},
			],

			'admin_url' => [
				'label' => 'WP Admin URL',
				'type' => \Voxel\T_URL,
				'callback' => function() {
					return admin_url();
				},
			],

			'login_url' => [
				'label' => 'Login URL',
				'type' => \Voxel\T_URL,
				'callback' => function() {
					return \Voxel\get_auth_url();
				},
			],

			'register_url' => [
				'label' => 'Register URL',
				'type' => \Voxel\T_URL,
				'callback' => function() {
					return add_query_arg( 'register', '', \Voxel\get_auth_url() );
				},
			],

			'logout_url' => [
				'label' => 'Logout URL',
				'type' => \Voxel\T_URL,
				'callback' => function() {
					return \Voxel\get_logout_url();
				},
			],

			'language' => [
				'label' => 'Language',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return get_bloginfo('language');
				},
			],

			'date' => [
				'label' => 'Date',
				'type' => \Voxel\T_DATE,
				'callback' => function() {
					return current_time('Y-m-d H:i:s');
				},
			],
		];
	}

	protected function methods(): array {
		return [
			'option' => Methods\Site_Option::class,
		];
	}
}
