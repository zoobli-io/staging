<?php

namespace Voxel\Dynamic_Tags;

if ( ! defined('ABSPATH') ) {
	exit;
}

abstract class Base_Group {

	protected static $properties;
	protected static $methods;

	public $post;
	public $post_type;
	public $template_id;

	public function __construct() {
		$this->post = \Voxel\get_current_post() ?? \Voxel\Post::dummy();
		$this->post_type = \Voxel\get_current_post_type() ?? \Voxel\Post_Type::get('post');

		if ( \Voxel\is_elementor_ajax() ) {
			$this->template_id = absint( $_REQUEST['editor_post_id'] ?? '' );
			$this->editor_init();
		} elseif ( \Voxel\is_edit_mode() ) {
			$this->template_id = absint( $_REQUEST['post'] ?? '' );
			$this->editor_init();
		} else {
			$this->frontend_init();
		}
	}

	abstract public function get_title(): string;

	abstract public function get_key(): string;

	abstract protected function properties(): array;

	protected function methods(): array {
		return [];
	}

	protected function editor_init(): void {
		//
	}

	protected function frontend_init(): void {
		//
	}

	public function get_properties() {
		return $this->properties();
	}

	public function get_methods() {
		if ( ! isset( static::$methods[ $this->get_key() ] ) ) {
			static::$methods[ $this->get_key() ] = [];
			foreach ( $this->methods() as $key => $cls ) {
				static::$methods[ $this->get_key() ][ $key ] = new $cls;
			}
		}

		return static::$methods[ $this->get_key() ];
	}

	public function get_property( $path ) {
		$properties = $this->get_properties();

		$keys = explode( '.', $path );
		$key = array_shift( $keys );

		$parent = null;
		$property = $properties[ $key ] ?? null;
		if ( $property === null ) {
			return null;
		}

		$property['_key'] = $key;

		foreach ( $keys as $key ) {
			if ( ! isset( $property['properties'][ $key ] ) ) {
				return null;
			}

			$parent = $property;
			$property = $property['properties'][ $key ];
			$property['_key'] = $key;
		}

		/*if ( $property['type'] === \Voxel\T_OBJECT ) {
			if ( isset( $property['properties'][':default'] ) ) {
				$property = $property['properties'][':default'];
				$property['_key'] = ':default';
			} else {
				return null;
			}
		}*/

		$loop_index = 0;
		if ( $parent !== null && ! empty( $parent['loopable'] ) ) {
			$parent_path = substr( $path, 0, -( strlen( $property['_key'] ) + 1 ) );
			$loop_id = sprintf( '@%s(%s)', $this->get_key(), $parent_path );
			if ( \Voxel\Dynamic_Tags\Loop::is_running( $loop_id ) ) {
				$loop_index = \Voxel\Dynamic_Tags\Loop::get_index( $loop_id );
			}
		}

		$property['_loop_index'] = $loop_index;
		return $property;
	}

	public function set_post_type( \Voxel\Post_Type $post_type ) {
		$this->post_type = $post_type;
	}
}
