<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Repeater_Field extends Base_Post_Field {

	protected $fields;

	protected $props = [
		'type' => 'repeater',
		'label' => 'Repeater',
		'min' => null,
		'max' => null,
		'fields' => [],
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'key' => $this->get_model( 'key' ),
			'description' => $this->get_description_model(),
			'required' => $this->get_required_model(),
			'min' => [
				'type' => Form_Models\Number_Model::class,
				'label' => 'Minimum repeater items',
				'width' => '1/2',
			],
			'max' => [
				'type' => Form_Models\Number_Model::class,
				'label' => 'Maximum repeater items',
				'width' => '1/2',
			],
		];
	}

	public function sanitize( $rows ) {
		if ( ! is_array( $rows ) ) {
			return [];
		}

		$sanitized = [];
		foreach ( (array) $rows as $row_index => $row ) {
			foreach ( $this->get_fields() as $field ) {
				if ( ! isset( $row[ $field->get_key() ] ) ) {
					$sanitized[ $row_index ][ $field->get_key() ] = null;
				} else {
					$sanitized[ $row_index ][ $field->get_key() ] = $field->sanitize( $row[ $field->get_key() ] );
				}
			}
		}

		return $sanitized;
	}

	public function validate( $rows ): void {
		foreach ( $rows as $row ) {
			foreach ( $this->get_fields() as $field ) {
				try {
					$field->check_validity( $row[ $field->get_key() ] );
				} catch ( \Exception $e ) {
					throw $e;
				}
			}
		}
	}

	public function update( $rows ): void {
		$rows = $this->_prepare_rows_for_storage( $rows );

		if ( empty( $rows ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			update_post_meta( $this->post->get_id(), $this->get_key(), wp_slash( wp_json_encode( $rows ) ) );
		}
	}

	public function update_value_in_repeater( $rows ) {
		return $this->_prepare_rows_for_storage( $rows );
	}

	protected function _prepare_rows_for_storage( $rows ) {
		foreach ( $rows as $row_index => $row ) {
			foreach ( $this->get_fields() as $field ) {
				$field->set_post( $this->post );

				if ( $row[ $field->get_key() ] === null ) {
					unset( $rows[ $row_index ][ $field->get_key() ] );
					continue;
				}

				$value = $field->update_value_in_repeater( $row[ $field->get_key() ] );
				if ( $value === null ) {
					unset( $rows[ $row_index ][ $field->get_key() ] );
					continue;
				}

				$rows[ $row_index ][ $field->get_key() ] = $value;
			}

			if ( empty( $row ) ) {
				unset( $rows[ $row_index ] );
			}
		}

		return $rows;
	}

	public function get_value_from_post() {
		return (array) json_decode( get_post_meta(
			$this->post->get_id(), $this->get_key(), true
		), ARRAY_A );
	}

	public function get_fields() {
		if ( is_array( $this->fields ) ) {
			return $this->fields;
		}

		$this->fields = [];

		$config = $this->props['fields'] ?? [];
		$field_types = \Voxel\config('post_types.field_types');

		foreach ( $config as $field_data ) {
			if ( ! is_array( $field_data ) || empty( $field_data['type'] ) || empty( $field_data['key'] ) ) {
				continue;
			}

			if ( isset( $field_types[ $field_data['type'] ] ) ) {
				$field = new $field_types[ $field_data['type'] ]( $field_data );
				$field->set_post_type( $this->post_type );
				$field->set_repeater( $this );
				$field->set_step( $this->get_step() );

				if ( $this->post ) {
					$field->set_post( $this->post );
				}

				$this->fields[ $field->get_key() ] = $field;
			}
		}

		return $this->fields;
	}

	protected function frontend_props(): array {
		$value = $this->get_value();
		$fields = $this->get_fields();
		$rows = [];
		foreach ( (array) $value as $repeater_index => $row ) {
			foreach ( $fields as $_field ) {
				$field = clone $_field;
				$field->set_repeater_index( $repeater_index );
				$rows[ $repeater_index ][ $field->get_key() ] = $field->get_frontend_config();
			}
		}

		$config = array_map( function( $field ) {
			$field = clone $field;
			$field->set_repeater_index(-1); // to be used as blueprint for new rows, value must be null
			return $field->get_frontend_config();
		}, $fields );

		return [
			'fields' => $config,
			'rows' => $rows,
		];
	}

	public function get_field_templates() {
		$templates = [];
		foreach ( $this->get_fields() as $field ) {
			if ( $template = locate_template( sprintf( 'templates/widgets/create-post/%s-field.php', $field->get_type() ) ) ) {
				$templates[] = $template;
			}

			if ( $field->get_type() === 'repeater' ) {
				$templates = array_merge( $templates, $field->get_field_templates() );
			}
		}

		return $templates;
	}

	public function exports() {
		$properties = array_filter( array_map( function( $field ) {
			return $field->exports();
		}, $this->get_fields() ) );

		return [
			'label' => $this->get_label(),
			'type' => \Voxel\T_OBJECT,
			'properties' => $properties,
			'loopable' => true,
			'loopcount' => function() {
				$value = $this->get_value();
				return $value === null ? 0 : count( $this->get_value() );
			},
		];
	}
}
