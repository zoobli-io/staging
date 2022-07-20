<?php

namespace Voxel\Object_Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Base_Validation_Helpers {

	protected function validate_is_empty( $value ) {
		// required field check, handling 0, '0', and 0.0 as special cases
		if ( $this->is_required() && $this->is_empty( $value ) ) {
			throw new \Exception( sprintf(
				_x( '%s is required', 'field validation', 'voxel' ),
				$this->get_label()
			) );
		}
	}

	protected function validate_minlength( $value, $strip_tags = false ) {
		if ( $strip_tags ) {
			$value = wp_strip_all_tags( $value );
		}

		if ( is_numeric( $this->get_prop('minlength') ) && mb_strlen( $value ) < $this->get_prop('minlength') ) {
			// translators: %1$s is the field label; %2%s is the minimum characters allowed.
			throw new \Exception( sprintf(
				_x( '%1$s can\'t be shorter than %2$s characters.', 'field validation', 'voxel' ),
				$this->get_label(),
				absint( $this->get_prop('minlength') )
			) );
		}
	}

	protected function validate_maxlength( $value, $strip_tags = false ) {
		if ( $strip_tags ) {
			$value = wp_strip_all_tags( $value );
		}

		if ( is_numeric( $this->get_prop('maxlength') ) && mb_strlen( $value ) > $this->get_prop('maxlength') ) {
			// translators: %1$s is the field label; %2%s is the maximum characters allowed.
			throw new \Exception( sprintf(
				_x( '%1$s can\'t be longer than %2$s characters.', 'field validation', 'voxel' ),
				$this->get_label(),
				absint( $this->get_prop('maxlength') )
			) );
		}
	}

	protected function validate_is_numeric( $value ) {
		if ( ! is_numeric( $value ) ) {
			throw new \Exception( sprintf(
				_x( '%s is not a valid number', 'field validation', 'voxel' ),
				$this->get_label()
			) );
		}
	}

	protected function validate_email( $value ) {
		if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			// translators: Placeholder %s is the label for the required field.
			throw new \Exception( sprintf(
				_x( '%s must be a valid email address.', 'field validation', 'voxel' ),
				$this->get_label()
			) );
		}
	}
}
