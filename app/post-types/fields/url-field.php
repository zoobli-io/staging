<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Url_Field extends Base_Post_Field {

	protected $supported_conditions = ['text'];

	protected $props = [
		'type' => 'url',
		'label' => 'URL',
		'placeholder' => '',
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'key' => $this->get_key_model(),
			'placeholder' => $this->get_placeholder_model(),
			'description' => $this->get_description_model(),
			'required' => $this->get_required_model(),
		];
	}

	public function sanitize( $value ) {
		return sanitize_url( $value );
	}

	public function validate( $value ): void {
		if ( preg_match( '@^(https?|ftp)://[^\s/$.?#].[^\s]*$@iS', $value ) !== 1 ) {
			// translators: Placeholder %s is the label for the required field.
			throw new \Exception( sprintf(
				_x( '%s must be a valid url address.', 'Add listing form', 'my-listing' ),
				$this->get_label()
			) );
		}
	}

	public function update( $value ): void {
		if ( $this->is_empty( $value ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			update_post_meta( $this->post->get_id(), $this->get_key(), $value );
		}
	}

	public function get_value_from_post() {
		return get_post_meta( $this->post->get_id(), $this->get_key(), true );
	}

	protected function frontend_props() {
		return [
			'placeholder' => $this->props['placeholder'],
		];
	}

	public function exports() {
		return [
			'label' => $this->get_label(),
			'type' => \Voxel\T_URL,
			'callback' => function() {
				return $this->get_value();
			},
		];
	}
}
