<?php

namespace Voxel\Dynamic_Tags\Modifiers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Date_Format extends \Voxel\Dynamic_Tags\Base_Modifier {

	public function get_key(): string {
		return 'date_format';
	}

	public function get_label(): string {
		return _x( 'Date Format', 'modifiers', 'voxel' );
	}

	public function accepts(): string {
		return \Voxel\T_DATE;
	}

	public function get_arguments(): array {
		return [
			'format' => [
				'type' => \Voxel\Form_Models\Text_Model::class,
				'label' => 'Date Format',
				'description' => _x( 'Leave empty to use the format set in site options', 'modifiers', 'voxel' ),
			],
		];
	}

	public function apply( $value, $args, $group ) {
		if ( isset( $args[0] ) ) {
			$value = date_i18n( $args[0], $value );
		}

		return $value;
	}

}
