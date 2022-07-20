<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Post {
	use \Voxel\Posts\Post_Singleton_Trait;

	private $fields;

	private $wp_post;

	public $post_type;

	public $repository;

	private function __construct( \WP_Post $post ) {
		$this->wp_post = $post;
		$this->post_type = \Voxel\Post_Type::get( $post->post_type );
		$this->repository = new \Voxel\Posts\Post_Repository( $this );
	}

	public function get_id(): int {
		return (int) $this->wp_post->ID;
	}

	public function get_title() {
		return $this->wp_post->post_title;
	}

	public function get_content() {
		return $this->wp_post->post_content;
	}

	public function get_excerpt() {
		return $this->wp_post->post_excerpt;
	}

	public function get_date() {
		return $this->wp_post->post_date_gmt;
	}

	public function get_modified_date() {
		return $this->wp_post->post_date_gmt;
	}

	public function get_slug() {
		return $this->wp_post->post_name;
	}

	public function get_status() {
		return $this->wp_post->post_status;
	}

	public function get_link() {
		return get_permalink( $this->wp_post );
	}

	public function get_edit_link() {
		return add_query_arg(
			'post_id',
			$this->get_id(),
			get_permalink( $this->post_type->get_templates()['form'] )
		);
	}

	public function is_managed_by_voxel(): bool {
		return $this->post_type->is_managed_by_voxel();
	}

	public function is_built_with_elementor(): bool {
		return !! get_post_meta( $this->get_id(), '_elementor_edit_mode', true );
	}

	public function get_author_id() {
		return absint( $this->wp_post->post_author );
	}

	public function get_author() {
		return \Voxel\User::get( $this->get_author_id() );
	}

	public function get_fields() {
		return $this->repository->get_fields();
	}

	public function get_field( $field_key ) {
		return $this->repository->get_field( $field_key );
	}

	public function get_logo_id() {
		$field = $this->get_field('logo');
		if ( ! $field ) {
			return null;
		}

		$value = $field->get_value();
		if ( ! empty( $value[0] ) ) {
			return $value[0];
		}

		$default = $field->get_prop('default');
		if ( $default ) {
			return $default;
		}

		return null;
	}

	public function get_logo_markup() {
		return wp_get_attachment_image( $this->get_logo_id(), 'thumbnail', false, [
			'class' => 'ts-status-avatar',
		] );
	}

	public function get_timezone() {
		$field = $this->get_field( 'timezone' );
		if ( $field && $field->get_type() === 'timezone' ) {
			return $field->get_timezone();
		}

		return wp_timezone();
	}

	public function is_editable_by_current_user(): bool {
		return (
			current_user_can( 'edit_others_posts', $this->get_id() ) ||
			absint( $this->get_author_id() ) === absint( get_current_user_id() )
		);
	}

	public function index( $filters = null ) {
		$this->post_type->get_index_table()->index( [ $this->get_id() ], $filters );
	}

	public static function current_user_can_edit( $post_id ): bool {
		if ( ! ( $post = self::get( $post_id ) ) ) {
			return false;
		}

		return $post->is_editable_by_current_user();
	}

	public function is_verified(): bool {
		return !! get_post_meta( $this->get_id(), 'voxel:verified', true );
	}

	public function set_verified( bool $status ): void {
		if ( $status ) {
			update_post_meta( $this->get_id(), 'voxel:verified', 1 );
		} else {
			delete_post_meta( $this->get_id(), 'voxel:verified' );
		}
	}

	public static function dummy( array $args = [] ) {
		return static::get( new \WP_Post( (object) array_merge( [
			'ID' => 0,
			'post_type' => 'post',
		], $args ) ) );
	}

	public function get_wp_post_object() {
		return $this->wp_post;
	}
}
