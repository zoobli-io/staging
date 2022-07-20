<?php

namespace Voxel\Post_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Terms_Filter extends Base_Filter {

	protected $props = [
		'type' => 'terms',
		'label' => 'Terms',
		'source' => '',
		'orderby' => 'default',
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'description' => $this->get_description_model(),
			'key' => $this->get_key_model(),
			'icon' => $this->get_icon_model(),
			'source' => $this->get_source_model( 'taxonomy' ),
			'orderby' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'label' => 'Term order',
				'width' => '1/1',
				'choices' => [
					'default' => 'Default',
					'name' => 'Alphabetical',
				],
			],
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		global $wpdb;

		$term_slugs = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $term_slugs === null ) {
			return;
		}

		$_term_slugs = array_map( function( $term_slug ) {
			return '\''.esc_sql( sanitize_text_field( $term_slug ) ).'\'';
		}, $term_slugs );

		$_joined_terms = join( ',', $_term_slugs );
		$term_ids = $wpdb->get_col( "SELECT term_id FROM {$wpdb->terms} WHERE slug IN ({$_joined_terms})" );

		$term_ids = array_filter( array_map( 'absint', $term_ids ) );
		$ids = array_unique( $term_ids );

		if ( empty( $ids ) ) {
			return;
		}

		$join_key = esc_sql( $this->db_key() );
		$ids_string = join( ',', $ids );

		$query->join( "
			LEFT JOIN {$wpdb->term_relationships} AS `{$join_key}`
				ON ( `{$query->table->get_escaped_name()}`.post_id = `{$join_key}`.object_id )
		" );

		$query->where( "`{$join_key}`.term_taxonomy_id IN ({$ids_string})" );
	}

	protected function _get_taxonomy() {
		$field = $this->post_type->get_field( $this->props['source'] );
		return $field ? $field->get_prop('taxonomy') : '';
	}

	public function frontend_props() {
		$taxonomy = \Voxel\Taxonomy::get( $this->_get_taxonomy() );
		if ( ! $taxonomy ) {
			return [
				'terms' => [],
			];
		}

		$args = [
			'orderby' => $this->get_prop( 'orderby' ) === 'name' ? 'name' : 'default',
		];

		$transient_key = sprintf( 'filter:%s.%s', $this->post_type->get_key(), $this->get_key() );
		$t = get_transient( $transient_key );

		$terms = ( is_array( $t ) && isset( $t['terms'] ) ) ? $t['terms'] : [];
		$time = ( is_array( $t ) && isset( $t['time'] ) ) ? $t['time'] : 0;
		$hash = ( is_array( $t ) && isset( $t['hash'] ) ) ? $t['hash'] : false;
		$new_hash = md5( wp_json_encode( $args ) );

		if ( ! $t || ( $time < $taxonomy->get_version() ) || $hash !== $new_hash ) {
			$terms = \Voxel\get_terms( $this->_get_taxonomy(), $args );
			set_transient( $transient_key, [
				'terms' => $terms,
				'time' => time(),
				'hash' => $new_hash,
			], 14 * DAY_IN_SECONDS );
			// dump('from query');
		} else {
			// dump('from cache');
		}

		return [
			'terms' => $terms,
			'default_icon' => \Voxel\get_icon_markup( 'la-regular:lar la-bookmark' ),
			'selected' => $this->_get_selected_terms() ?: [],
		];
	}

	protected function _get_selected_terms() {
		if ( array_key_exists( 'selected_terms', $this->cache ) ) {
			return $this->cache['selected_terms'];
		}

		$value = $this->parse_value( $this->get_value() ) ?: [];
		if ( empty( $value ) ) {
			$this->cache['selected_terms'] = null;
			return $this->cache['selected_terms'];
		}

		$terms = \Voxel\get_terms( $this->_get_taxonomy(), [
			'orderby' => 'name',
			'slug__in' => $value,
		] );

		$selected = [];
		foreach ( $terms as $term ) {
			$selected[ $term['slug'] ] = $term;
		}

		$this->cache['selected_terms'] = ! empty( $selected ) ? $selected : null;
		return $this->cache['selected_terms'];
	}

	public function parse_value( $value ) {
		if ( ! is_string( $value ) || empty( $value ) ) {
			return null;
		}

		$value = sanitize_text_field( trim( $value ) );
		$terms = explode( ',', $value );
		$terms = array_filter( $terms );
		return ! empty( $terms ) ? $terms : null;
	}

	public function get_elementor_controls(): array {
		return [
			'value' => [
				'label' => _x( 'Default value', 'terms filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => 'Enter a comma-delimited list of terms to be selected by default',
			],
		];
	}
}
