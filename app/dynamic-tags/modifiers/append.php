<?php

namespace Voxel\Dynamic_Tags\Modifiers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Append extends \Voxel\Dynamic_Tags\Base_Modifier {

	public function get_label(): string {
		return _x( 'Append text', 'modifiers', 'voxel' );
	}

	public function get_key(): string {
		return 'append';
	}

	public function get_arguments(): array {
		return [
			'text' => [
				'type' => \Voxel\Form_Models\Text_Model::class,
				'label' => 'Text to append',
			],
		];
	}

	public function apply( $value, $args, $group ) {
		return $value.( $args[0] ?? '' );
	}
}
