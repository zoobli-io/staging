<?php

namespace Voxel\Post_Types\Order_By;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Number_Field_Order extends Base_Search_Order {

	protected $props = [
		'type' => 'number-field',
		'source' => '',
		'order' => 'DESC',
	];

	public function get_label(): string {
		return __( 'Number field', 'voxel' );
	}

	public function get_models(): array {
		return [
			'source' => $this->get_source_model( 'number' ),
			'order' => $this->get_order_model(),
		];
	}

	public function setup( \Voxel\Post_Types\Index_Table $table ): void {
		if ( $this->_get_source_filter() ) {
			return;
		}

		$field = $this->post_type->get_field( $this->props['source'] );
		if ( $field && $field->get_type() === 'number' ) {
			$table->add_column( sprintf(
				'`%s` %s NOT NULL DEFAULT 0',
				esc_sql( $this->_get_column_key() ),
				$field->_get_column_type()
			) );

			$table->add_key( sprintf(
				'KEY(`%s`)',
				esc_sql( $this->_get_column_key() )
			) );
		}
	}

	public function index( \Voxel\Post $post ): array {
		if ( $this->_get_source_filter() ) {
			return [];
		}

		$field = $post->get_field( $this->props['source'] );
		if ( ! $field || empty( $field->get_value() ) ) {
			$value = 0;
		} else {
			$value = $field->_prepare_value( $field->get_value() );
		}

		return [
			$this->_get_column_key() => $value,
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args, array $clause_args ): void {
		$query->orderby( sprintf(
			'`%s` %s',
			$this->_get_column_key(),
			$this->props['order'] === 'ASC' ? 'ASC' : 'DESC'
		) );
	}

	private function _get_source_filter() {
		// check if a filter with this source already exists
		$filter = array_filter( $this->post_type->get_filters(), function( $filter ) {
			return $filter->get_prop('source') === $this->props['source'];
		} );

		return ! empty( $filter ) ? array_pop( $filter ) : null;
	}

	private function _get_column_key() {
		$filter = $this->_get_source_filter();
		if ( $filter ) {
			return $filter->db_key();
		}

		return sprintf( 'numsort_%s', $this->props['source'] );
	}
}
