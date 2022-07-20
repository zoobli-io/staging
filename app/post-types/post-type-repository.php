<?php

namespace Voxel\Post_Types;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Post_Type_Repository {

	private
		$post_type,
		$fields,
		$filters,
		$search_orders,
		$templates,
		$config;

	public function __construct( \Voxel\Post_Type $post_type ) {
		$this->post_type = $post_type;
		$post_types = \Voxel\get( 'post_types', [] );
		$this->config = $post_types[ $this->post_type->get_key() ] ?? [];
	}

	/**
	 * Memoized method to retrieve, validate field data,
	 * and convert them to their respective classes.
	 *
	 * @since 1.0
	 */
	public function get_fields() {
		if ( is_array( $this->fields ) ) {
			return $this->fields;
		}

		$this->fields = [];

		$config = $this->config['fields'] ?? [];
		$field_types = \Voxel\config('post_types.field_types');

		if ( ! isset( $config[0] ) || ! isset( $config[0]['type'] ) || $config[0]['type'] !== 'ui-step' ) {
			array_unshift( $config, \Voxel\Post_Types\Fields\Ui_Step_Field::preset( [
				'key' => 'step-general',
				'label' => 'General',
			] ) );
		}

		$current_step = $config[0]['key'];
		foreach ( $config as $field_data ) {
			if ( ! is_array( $field_data ) || empty( $field_data['type'] ) || empty( $field_data['key'] ) ) {
				continue;
			}

			if ( isset( $field_types[ $field_data['type'] ] ) ) {
				$field = new $field_types[ $field_data['type'] ]( $field_data );
				$field->set_post_type( $this->post_type );

				$this->fields[ $field->get_key() ] = $field;

				if ( $field->get_type() === 'ui-step' ) {
					$current_step = $field->get_key();
				} else {
					$field->set_step( $current_step );
				}
			}
		}

		// if there are no fields added for this post type yet, use title field automatically
		if ( count( $this->fields ) <= 1 && ! isset( $this->fields['title'] ) ) {
			$this->fields['title'] = new $field_types['title'];
		}

		return $this->fields;
	}

	public function get_field( $field_key ) {
		$fields = $this->get_fields();
		return $fields[ $field_key ] ?? null;
	}

	/**
	 * Memoized method to retrieve, validate filter data,
	 * and convert them to their respective classes.
	 *
	 * @since 1.0
	 */
	public function get_filters() {
		if ( is_array( $this->filters ) ) {
			return $this->filters;
		}

		$this->filters = [];

		$search = $this->config['search'] ?? [];
		$config = $search['filters'] ?? [];
		$filter_types = \Voxel\config('post_types.filter_types');

		foreach ( $config as $filter_data ) {
			if ( ! is_array( $filter_data ) || empty( $filter_data['type'] ) || empty( $filter_data['key'] ) ) {
				continue;
			}

			if ( isset( $filter_types[ $filter_data['type'] ] ) ) {
				$filter = new $filter_types[ $filter_data['type'] ]( $filter_data );
				$filter->set_post_type( $this->post_type );

				$this->filters[ $filter->get_key() ] = $filter;
			}
		}

		return $this->filters;
	}

	public function get_filter( $filter_key ) {
		$filters = $this->get_filters();
		return $filters[ $filter_key ] ?? null;
	}

	/**
	 * Memoized method to retrieve, validate search order data,
	 * and convert them to their respective classes.
	 *
	 * @since 1.0
	 */
	public function get_search_orders() {
		if ( is_array( $this->search_orders ) ) {
			return $this->search_orders;
		}

		$this->search_orders = [];

		$search = $this->config['search'] ?? [];
		$config = array_values( $search['order'] ?? [] );

		foreach ( $config as $i => $order_data ) {
			if ( ! is_array( $order_data ) || empty( $order_data['key'] ) || empty( $order_data['clauses'] ) ) {
				continue;
			}

			$group = new \Voxel\Post_Types\Order_By\Order_By_Group( $order_data, $this->post_type );
			$this->search_orders[ $order_data['key'] ] = $group;
		}

		return $this->search_orders;
	}

	public function get_search_order( $search_order_key ) {
		$search_orders = $this->get_search_orders();
		return $search_orders[ $search_order_key ] ?? null;
	}

	/**
	 * Retrieve and validate post type templates.
	 *
	 * @since 1.0
	 */
	public function get_templates( $create_if_not_exists = false ) {
		$templates = [
			'single' => null,
			'card' => null,
			'archive' => null,
			'form' => null,
		];

		foreach ( (array) ( $this->config['templates'] ?? [] ) as $location => $template_id ) {
			if ( array_key_exists( $location, $templates ) && is_numeric( $template_id )  ) {
				$templates[ $location ] = absint( $template_id );
			}
		}

		if ( $create_if_not_exists ) {
			foreach ( $templates as $location => $template_id ) {
				if ( $location === 'form' ) {
					if ( \Voxel\page_exists( absint( $template_id ) ) ) {
						continue;
					}

					$title = sprintf( 'Create %s', $this->post_type->get_singular_name() );
					$new_template_id = \Voxel\create_page(
						$title,
						sprintf( 'create-%s', $this->post_type->get_key() )
					);

					if ( ! is_wp_error( $new_template_id ) ) {
						$templates[ $location ] = absint( $new_template_id );
					}
				} else {
					if ( \Voxel\template_exists( absint( $template_id ) ) ) {
						continue;
					}

					$title = sprintf( 'post type: %s | template: %s', $this->post_type->get_key(), $location );
					$new_template_id = \Voxel\create_template( $title );
					if ( ! is_wp_error( $new_template_id ) ) {
						$templates[ $location ] = absint( $new_template_id );
					}
				}
			}

			$this->set_config( [
				'templates' => $templates,
			] );
		}

		return $templates;
	}

	/**
	 * Save post type configuration to database.
	 *
	 * @since 1.0
	 */
	public function set_config( $new_config ) {
		$post_types = \Voxel\get( 'post_types', [] );

		if ( isset( $new_config['settings'] ) ) {
			$this->config['settings'] = $new_config['settings'];
		}

		if ( isset( $new_config['fields'] ) ) {
			$this->config['fields'] = $new_config['fields'];
		}

		if ( isset( $new_config['search'] ) ) {
			$this->config['search'] = $new_config['search'];
		}

		if ( isset( $new_config['templates'] ) ) {
			$this->config['templates'] = $new_config['templates'];
		}

		$post_types[ $this->post_type->get_key() ] = $this->config;

        // cleanup post_types array
        foreach ( $post_types as $post_type_key => $post_type_settings ) {
        	if ( ! is_string( $post_type_key ) || empty( $post_type_key ) || empty( $post_type_settings ) ) {
        		unset( $post_types[ $post_type_key ] );
        	}
        }

		\Voxel\set( 'post_types', $post_types );
	}

	public function remove() {
		$post_types = \Voxel\get( 'post_types', [] );
		unset( $post_types[ $this->post_type->get_key() ] );
		\Voxel\set( 'post_types', $post_types );
	}

	public function get_editor_config() {
		$settings = $this->get_settings();

		$search = $this->get_search();

		return [
			'settings' => [
				'key' => $this->post_type->get_key(),
				'singular' => $this->post_type->get_singular_name(),
				'plural' => $this->post_type->get_plural_name(),
				'icon' => $settings['icon'] ?? '',
				'timeline' => [
					'enabled' => $settings['timeline']['enabled'] ?? true,
					'wall' => $settings['timeline']['wall'] ?? 'public', // public|followers_only|disabled
					'reviews' => $settings['timeline']['reviews'] ?? 'public', // public|followers_only|disabled
					'visibility' => $settings['timeline']['visibility'] ?? 'public', // public|logged_in|followers_only|private
					'wall_visibility' => $settings['timeline']['wall_visibility'] ?? 'public', // public|logged_in|followers_only|private
					'review_visibility' => $settings['timeline']['review_visibility'] ?? 'public', // public|logged_in|followers_only|private
				],
				'submissions' => [
					'enabled' => $settings['submissions']['enabled'] ?? true,
					'status' => $settings['submissions']['status'] ?? 'publish', // publish|pending
					'update_status' => $settings['submissions']['update_status'] ?? 'publish', // publish|pending|pending_merge|disabled
				],
				'map' => [
					// 'field' => $settings['map']['field'] ?? 'location', // @todo
					'marker_type' => $settings['map']['marker_type'] ?? 'icon',
					'marker_icon' => $settings['map']['marker_icon'] ?? 'la-solid:las la-map-marker',
					'marker_image' => $settings['map']['marker_image'] ?? 'logo',
					'marker_text' => $settings['map']['marker_text'] ?? '',
				],
				'permalinks' => [
					'custom' => $settings['permalinks']['custom'] ?? false,
					'slug' => $settings['permalinks']['slug'] ?? $this->post_type->get_key(),
				],
			],

			'fields' => array_map( function( $field ) {
				return $field->get_props();
			}, array_values( $this->post_type->get_fields() ) ),
			'search' => [
				'filters' => array_map( function( $filter ) {
					return $filter->get_props();
				}, array_values( $this->post_type->get_filters() ) ),
				'order' => array_map( function( $search_order ) {
					return $search_order->get_props();
				}, array_values( $this->post_type->get_search_orders() ) ),
			],
			'templates' => $this->post_type->get_templates(),
		];
	}

	public function get_settings() {
		return $this->config['settings'] ?? [];
	}

	public function get_setting( $setting_key, $default = null ) {
		$settings = $this->get_settings();
		$keys = explode( '.', $setting_key );
		foreach ( $keys as $key ) {
			if ( ! isset( $settings[ $key ] ) ) {
				return $default;
			}

			$settings = $settings[ $key ];
		}

		return $settings;
	}

	public function get_search() {
		return $this->config['search'] ?? [];
	}

	public function is_managed_by_voxel() {
		return ! empty( $this->config );
	}

	/* Static methods */

	public static function get_all() {
		return array_filter( array_map(
			'\Voxel\Post_Type::get',
			get_post_types( [], 'objects' )
		) );
	}

	public static function get_voxel_types() {
		return array_filter( static::get_all(), function( $type ) {
			return $type->is_managed_by_voxel();
		} );
	}

	public static function get_other_types() {
		return array_filter( static::get_all(), function( $type ) {
			return ! $type->is_managed_by_voxel();
		} );
	}

}
