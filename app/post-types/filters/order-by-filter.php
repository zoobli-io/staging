<?php

namespace Voxel\Post_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Order_By_Filter extends Base_Filter {

	protected $props = [
		'type' => 'order-by',
		'label' => 'Sorteer op',
		'key' => 'sort', // 'order' clashes with WP permalinks
		'singular' => true,
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'description' => $this->get_description_model(),
			'key' => $this->get_key_model(),
			'icon' => $this->get_icon_model(),
		];
	}

	public function setup( \Voxel\Post_Types\Index_Table $table ): void {
		foreach ( $this->post_type->get_search_orders() as $search_order ) {
			foreach ( $search_order->get_clauses() as $clause ) {
				$clause->setup( $table );
			}
		}
	}

	public function index( \Voxel\Post $post ): array {
		$columns = [];
		foreach ( $this->post_type->get_search_orders() as $search_order ) {
			foreach ( $search_order->get_clauses() as $clause ) {
				$columns = array_merge( $columns, $clause->index( $post ) );
			}
		}

		return $columns;
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		$value = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $value === null ) {
			return;
		}

		$search_order = $this->post_type->get_search_order( $value['key'] );
		if ( $search_order ) {
			foreach ( $search_order->get_clauses() as $clause ) {
				$clause->query( $query, $args, $value['args'] );
			}
		}
	}

	public function frontend_props() {
		return [
			'choices' => $this->_get_selected_choices(),
			'display_as' => ( $this->elementor_config['display_as'] ?? null ) === 'buttons' ? 'buttons' : 'popup',
		];
	}

	protected function _get_choices() {
		if ( array_key_exists( 'choices', $this->cache ) ) {
			return $this->cache['choices'];
		}

		$choices = [];
		foreach ( $this->post_type->get_search_orders() as $search_order ) {
			$choices[ $search_order->get_key() ] = [
				'key' => $search_order->get_key(),
				'label' => $search_order->get_label(),
				'icon' => \Voxel\get_icon_markup( $search_order->get_icon() ),
				'requires_location' => $search_order->requires_user_location(),
			];
		}

		$this->cache['choices'] = $choices;
		return $this->cache['choices'];
	}

	protected function _get_selected_choices() {
		$all_choices = $this->_get_choices();
		$choices = [];
		$selected = ( $this->elementor_config['choices'] ?? null );
		if ( is_array( $selected ) && ! empty( $selected ) ) {
			foreach ( $selected as $choice_key ) {
				if ( isset( $all_choices[ $choice_key ] ) ) {
					$choices[ $choice_key ] = $all_choices[ $choice_key ];
				}
			}
		}

		return ! empty( $choices ) ? $choices : $all_choices;
	}

	public function parse_value( $value ) {
		preg_match( '/(?P<key>[^\(]*)(\((?P<args>.*)\))?/i', $value, $matches );
		if ( ! isset( $matches['key'] ) ) {
			return null;
		}

		$args = [];
		if ( isset( $matches['args'] ) ) {
			$args = explode( ',', $matches['args'] );
			$args = array_map( 'sanitize_text_field', $args );
		}

		return [
			'key' => sanitize_text_field( $matches['key'] ),
			'args' => $args,
		];
	}

	public function get_elementor_controls(): array {
		$choices = [];
		foreach ( $this->_get_choices() as $choice ) {
			$choices[ $choice['key'] ] = $choice['label'];
		}

		return [
			'value' => [
				'label' => _x( 'Standaard waarde', 'orderby filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $choices,
			],
			'display_as' => [
				'label' => _x( 'Weergeven als', 'orderby filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'popup' => _x( 'Popup', 'orderby filter', 'voxel' ),
					'buttons' => _x( 'Buttons', 'orderby filter', 'voxel' ),
				],
				'conditional' => false,
			],
			'choices' => [
				'label' => _x( 'Sorteer opties', 'orderby filter', 'voxel' ),
				'description' => _x( 'Laat leeg om alle beschikbare opties weer te geven', 'orderby filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $choices,
				'conditional' => false,
			],
		];
	}
}
