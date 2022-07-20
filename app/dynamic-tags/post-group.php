<?php

namespace Voxel\Dynamic_Tags;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Post_Group extends Base_Group {

	public function get_key(): string {
		return 'post';
	}

	public function get_title(): string {
		return _x( 'Post', 'groups', 'voxel' );
	}

	protected function properties(): array {
		$properties = [
			':id' => [
				'label' => 'ID',
				'type' => \Voxel\T_NUMBER,
				'callback' => function() {
					return $this->post->get_id();
				},
			],

			':title' => [
				'label' => 'Title',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return $this->post->get_title();
				},
			],

			':content' => [
				'label' => 'Content',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return apply_filters( 'the_content', $this->post->get_content() );
				},
			],

			':excerpt' => [
				'label' => 'Excerpt',
				'type' => \Voxel\T_STRING,
				'callback' => function() {
					return $this->post->get_excerpt();
				},
			],

			':date' => [
				'label' => 'Date',
				'type' => \Voxel\T_DATE,
				'callback' => function() {
					return $this->post->get_date();
				},
			],

			':logo' => [
				'label' => 'Logo',
				'type' => \Voxel\T_NUMBER,
				'callback' => function() {
					return $this->post->get_logo_id();
				},
			],

			':url' => [
				'label' => 'Permalink',
				'type' => \Voxel\T_URL,
				'callback' => function() {
					return $this->post->get_link();
				},
			],

			':reviews' => [
				'label' => 'Reviews',
				'type' => \Voxel\T_OBJECT,
				'properties' => [
					'total' => [
						'label' => 'Total count',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->post->repository->get_review_stats();
							return absint( $stats['total'] );
						},
					],
					'average' => [
						'label' => 'Average rating',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->post->repository->get_review_stats();
							if ( $stats['average'] === null ) {
								return '';
							}

							// convert scale from -2..2 to 0..5
							return round( ( $stats['average'] + 3 ), 2 );
						},
					],
					'percentage' => [
						'label' => 'Percentage',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->post->repository->get_review_stats();
							if ( $stats['average'] === null ) {
								return '';
							}

							$average = \Voxel\clamp( $stats['average'] + 2, 0, 4 );
							return round( ( $average / 4 ) * 100 );
						},
					],
				],
			],

			':followers' => [
				'label' => 'Followers',
				'type' => \Voxel\T_OBJECT,
				'properties' => [
					'accepted' => [
						'label' => 'Follow count',
						'description' => 'Number of users that are following this post',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->post->repository->get_follow_stats();
							return absint( $stats['followed'][ \Voxel\FOLLOW_ACCEPTED ] ?? 0 );
						},
					],
					'requested' => [
						'label' => 'Follow requested count',
						'description' => 'Number of users that have requested to follow this post',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->post->repository->get_follow_stats();
							return absint( $stats['followed'][ \Voxel\FOLLOW_REQUESTED ] ?? 0 );
						},
					],
					'blocked' => [
						'label' => 'Block count',
						'description' => 'Number of users that have been blocked by this post',
						'type' => \Voxel\T_NUMBER,
						'callback' => function() {
							$stats = $this->post->repository->get_follow_stats();
							return absint( $stats['followed'][ \Voxel\FOLLOW_BLOCKED ] ?? 0 );
						},
					],
				],
			],
		];

		if ( $this->post ) {
			foreach ( $this->post->get_fields() as $field ) {
				$exports = $field->exports();
				if ( $exports === null ) {
					continue;
				}

				$properties[ $field->get_key() ] = $exports;
			}
		}

		return $properties;
	}

	protected function methods(): array {
		return [
			'meta' => Methods\Post_Meta::class,
		];
	}
}
