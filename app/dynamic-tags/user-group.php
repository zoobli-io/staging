<?php

namespace Voxel\Dynamic_Tags;

if ( ! defined('ABSPATH') ) {
	exit;
}

class User_Group extends Base_Group {

	public $user;

	public function get_key(): string {
		return 'user';
	}

	public function get_title(): string {
		return _x( 'User', 'groups', 'voxel' );
	}

	protected function editor_init(): void {
		$this->user = \Voxel\User::get( wp_get_current_user() ) ?? \Voxel\User::dummy();
	}

	protected function frontend_init(): void {
		$this->user = \Voxel\User::get( wp_get_current_user() ) ?? \Voxel\User::dummy();
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
					'published' => [
						'label' => 'Published count',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() use ($post_type) {
							$stats = $this->user->get_post_stats();
							return $stats[ $post_type->get_key() ]['publish'] ?? 0;
						},
					],
					'pending' => [
						'label' => 'Pending count',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() use ($post_type) {
							$stats = $this->user->get_post_stats();
							return $stats[ $post_type->get_key() ]['pending'] ?? 0;
						},
					],
					'archive' => [
						'label' => 'Archive link',
						'type' => \Voxel\T_URL,
						'callback' => function() use ($post_type) {
							$filters = $post_type->get_filters();
							$key = 'user';
							foreach ( $filters as $filter ) {
								if ( $filter->get_type() === 'user' ) {
									$key = $filter->get_key();
								}
							}

							return add_query_arg( $key, $this->user->get_id(), $post_type->get_archive_link() );
						},
					],
				],
			];
		}

		return [
			':id' => [
				'label' => 'ID',
				'type' => \Voxel\T_NUMBER,
				'callback' => function() {
					return $this->user->get_id();
				},
			],

			':username' => [
				'label' => 'Username',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return $this->user->get_username();
				},
			],

			':email' => [
				'label' => 'Email',
				'type' => \Voxel\T_EMAIL,
				'callback' => function() {
					return $this->user->get_email();
				},
			],

			':first_name' => [
				'label' => 'First Name',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return $this->user->get_first_name();
				},
			],

			':last_name' => [
				'label' => 'Last Name',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return $this->user->get_last_name();
				},
			],

			':display_name' => [
				'label' => 'Display Name',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return $this->user->get_display_name();
				},
			],

			':avatar' => [
				'label' => 'Avatar',
				'type' => \Voxel\T_NUMBER,
				'callback' => function() {
					return $this->user->get_avatar_id();
				},
			],

			':profile_url' => [
				'label' => 'Profile URL',
				'type' => \Voxel\T_URL,
				'callback' => function() {
					if ( ! is_user_logged_in() ) {
						return '';
					}

					return get_author_posts_url( $this->user->get_id() );
				},
			],

			'post_types' => $post_types,

			':followers' => [
				'label' => 'Followers',
				'type' => \Voxel\T_OBJECT,
				'properties' => [
					'accepted' => [
						'label' => 'Follow count',
						'description' => 'Number of users that are following this user',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->user->get_follow_stats();
							return absint( $stats['followed'][ \Voxel\FOLLOW_ACCEPTED ] ?? 0 );
						},
					],
					'requested' => [
						'label' => 'Follow requested count',
						'description' => 'Number of users that have requested to follow this user',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->user->get_follow_stats();
							return absint( $stats['followed'][ \Voxel\FOLLOW_REQUESTED ] ?? 0 );
						},
					],
					'blocked' => [
						'label' => 'Block count',
						'description' => 'Number of users that have been blocked by this user',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->user->get_follow_stats();
							return absint( $stats['followed'][ \Voxel\FOLLOW_BLOCKED ] ?? 0 );
						},
					],
				],
			],

			':following' => [
				'label' => 'Following',
				'type' => \Voxel\T_OBJECT,
				'properties' => [
					'accepted' => [
						'label' => 'Follow count',
						'description' => 'Number of users/posts this user is following',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->user->get_follow_stats();
							return absint( $stats['following'][ \Voxel\FOLLOW_ACCEPTED ] ?? 0 );
						},
					],
					'requested' => [
						'label' => 'Follow requested count',
						'description' => 'Number of users/posts this user has requested to follow',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->user->get_follow_stats();
							return absint( $stats['following'][ \Voxel\FOLLOW_REQUESTED ] ?? 0 );
						},
					],
					'blocked' => [
						'label' => 'Block count',
						'description' => 'Number of users/posts this user has been blocked by',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->user->get_follow_stats();
							return absint( $stats['following'][ \Voxel\FOLLOW_BLOCKED ] ?? 0 );
						},
					],
				],
			],
		];
	}

	protected function methods(): array {
		return [
			'meta' => Methods\User_Meta::class,
		];
	}
}
