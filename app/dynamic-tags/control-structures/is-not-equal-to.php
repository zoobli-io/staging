<?php

namespace Voxel\Dynamic_Tags\Control_Structures;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Is_Not_Equal_To extends Base_Control_Structure {

	public function get_key(): string {
		return 'is_not_qual_to';
	}

	public function get_label(): string {
		return _x( 'Is not equal to', 'modifiers', 'voxel' );
	}

	public function get_arguments(): array {
		return [
			'value' => [
				'type' => \Voxel\Form_Models\Text_Model::class,
				'label' => 'Value',
			],
		];
	}

	public function passes( $last_condition, $value, $args, $group ): bool {
		return (string) $value !== (string) ( $args[0] ?? '' );
	}
}
