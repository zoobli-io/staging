<?php

namespace Voxel\Post_Types\Field_Conditions;

if ( ! defined('ABSPATH') ) {
	exit;
}

class File_Not_Empty extends Base_Condition {

	public function get_type(): string {
		return 'file:not_empty';
	}

	public function get_label(): string {
		return _x( 'Is not empty', 'field conditions', 'voxel' );
	}
}
