<?php

namespace Voxel\Posts;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Post_Repository {

	private
		$post,
		$fields;

	public function __construct( \Voxel\Post $post ) {
		$this->post = $post;
	}

	public function get_fields() {
		if ( is_array( $this->fields ) ) {
			return $this->fields;
		}

		if ( ! $this->post->post_type ) {
			return $this->fields;
		}

		$this->fields = [];
		foreach ( $this->post->post_type->get_fields() as $field ) {
			$this->fields[ $field->get_key() ] = clone $field;
			$this->fields[ $field->get_key() ]->set_post( $this->post );
		}

		return $this->fields;
	}

	public function get_field( $field_key ) {
		$fields = $this->get_fields();
		return $fields[ $field_key ] ?? null;
	}

	public function get_review_stats() {
		$stats = (array) json_decode( get_post_meta( $this->post->get_id(), 'voxel:review_stats', true ), ARRAY_A );
		if ( ! isset( $stats['total'] ) ) {
			$stats = \Voxel\cache_post_review_stats( $this->post->get_id() );
		}

		return $stats;
	}

	public function get_follow_stats() {
		$stats = (array) json_decode( get_post_meta( $this->post->get_id(), 'voxel:follow_stats', true ), ARRAY_A );
		if ( ! isset( $stats['followed'] ) ) {
			$stats = \Voxel\cache_post_follow_stats( $this->post->get_id() );
		}

		return $stats;
	}
}
