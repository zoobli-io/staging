<?php

namespace Voxel\Post_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Range_Filter extends Base_Filter {
	use Traits\Numeric_Filter_Helpers;

	protected $props = [
		'type' => 'range',
		'label' => 'Bereik',
		'handles' => 'single',
		'compare' => 'in_range',
		'source' => '',
		'step_size' => 1,
		'range_start' => 0,
		'range_end' => 1000,
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'description' => $this->get_description_model(),
			'key' => $this->get_key_model(),
			'icon' => $this->get_icon_model(),
			'source' => $this->get_source_model( 'number' ),
			'handles' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'label' => 'Handles',
				'width' => '1/1',
				'choices' => [
					'single' => 'Single handle',
					'double' => 'Double handles',
				],
			],
			'range_start' => [
				'type' => \Voxel\Form_Models\Number_Model::class,
				'label' => 'Bereik start',
				'width' => '1/3',
				'step' => 'any',
			],
			'range_end' => [
				'type' => \Voxel\Form_Models\Number_Model::class,
				'label' => 'Bereik einde',
				'width' => '1/3',
				'step' => 'any',
			],
			'step_size' => [
				'type' => \Voxel\Form_Models\Number_Model::class,
				'label' => 'Step size',
				'width' => '1/3',
				'min' => 0,
				'step' => 'any',
			],
			'compare' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'label' => 'Vergelijking',
				'width' => '1/1',
				'choices' => [
					'in_range' => 'Binnen geselecteerde bereik',
					'outside_range' => 'Buiten geselecteerde bereik',
				],
			],
		];
	}

	public function setup( \Voxel\Post_Types\Index_Table $table ): void {
		$datatype = $this->_get_column_type();
		$table->add_column( sprintf( '`%s` %s NOT NULL DEFAULT 0', esc_sql( $this->db_key() ), $datatype ) );
		$table->add_key( sprintf( 'KEY(`%s`)', esc_sql( $this->db_key() ) ) );
	}

	public function index( \Voxel\Post $post ): array {
		$field = $post->get_field( $this->props['source'] );
		if ( ! $field || empty( $field->get_value() ) ) {
			$value = 0;
		} else {
			$value = $this->_prepare_value( $field->get_value() );
		}

		return [
			$this->db_key() => $value,
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		if ( $this->props['handles'] === 'single' ) {
			$this->_query_single_handle( $query, $args );
		} else {
			$this->_query_double_handles( $query, $args );
		}
	}

	protected function _query_single_handle( \Voxel\Post_Types\Index_Query $query, array $args ) {
		$value = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $value === null ) {
			return;
		}

		$value = array_shift( $value );
		$value = $this->_prepare_value( $value );
		$operator = $this->props['compare'] === 'outside_range' ? '>=' : '<=';
		$query->where( sprintf(
			"`%s` {$operator} %d",
			esc_sql( $this->db_key() ),
			$value
		) );
	}

	protected function _query_double_handles( \Voxel\Post_Types\Index_Query $query, array $args ) {
		$value = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $value === null ) {
			return;
		}

		[ $start, $end ] = $value;

		$clauses = [];
		if ( ! empty( $start ) && is_numeric( $start ) ) {
			$operator = $this->props['compare'] === 'outside_range' ? '<=' : '>=';
			$clauses[] = sprintf(
				"`%s` {$operator} %d",
				esc_sql( $this->db_key() ),
				$this->_prepare_value( $start )
			);
		}

		if ( ! empty( $end ) && is_numeric( $end ) ) {
			if ( ! empty( $clauses ) ) {
				$clauses[] = $this->props['compare'] === 'outside_range' ? 'OR' : 'AND';
			}

			$operator = $this->props['compare'] === 'outside_range' ? '>=' : '<=';
			$clauses[] = sprintf(
				"`%s` {$operator} %d",
				esc_sql( $this->db_key() ),
				$this->_prepare_value( $end )
			);
		}

		if ( ! empty( $clauses ) ) {
			$query->where( sprintf(
				'( %s )',
				join( ' ', $clauses )
			) );
		}
	}

	public function frontend_props() {
		wp_enqueue_style( 'nouislider' );
		wp_enqueue_script( 'nouislider' );

		$value = $this->parse_value( $this->get_value() );
		return [
			'handles' => $this->props['handles'],
			'compare' => $this->props['compare'],
			'step_size' => (float) abs( $this->props['step_size'] ),
			'range_start' => (float) $this->props['range_start'],
			'range_end' => (float) $this->props['range_end'],
			'value' => $value !== null ? $value : [],
		];
	}

	public function parse_value( $value ) {
		if ( $this->props['handles'] === 'single' ) {
			return ( ! empty( $value ) && is_numeric( $value ) ) ? [ (float) $value ] : null;
		} else {
			if ( empty( $value ) || strpos( $value, '..' ) === false ) {
				return null;
			}

			$values = explode( '..', $value );
			$start = (float) $values[0];
			$end = (float) $values[1];

			return [ $start, $end ];
		}
	}

	public function get_elementor_controls(): array {
		if ( $this->props['handles'] === 'single' ) {
			return [
				'value' => [
					'label' => _x( 'Standaard waarde', 'range filter', 'voxel' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
				],
			];
		}

		return [
			'start' => [
				'label' => _x( 'Standaard start waarde', 'range filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'classes' => 'ts-half-width',
			],
			'end' => [
				'label' => _x( 'Standaard eind waarde', 'range filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'classes' => 'ts-half-width',
			],
		];
	}

	public function get_default_value_from_elementor( $controls ) {
		if ( $this->props['handles'] === 'single' ) {
			return $controls['value'] ?? null;
		}

		$start = $controls['start'] ?? null;
		$end = $controls['end'] ?? null;
		return ( $start && $end ) ? sprintf( '%s..%s', $start, $end ) : null;
	}
}
