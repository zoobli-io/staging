<?php

namespace Voxel\Post_Types\Field_Conditions;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Number_Equals extends Base_Condition {
	use Traits\Single_Value_Model;

	public function get_type(): string {
		return 'number:equals';
	}

	public function get_label(): string {
		return _x( 'Equals', 'field conditions', 'voxel' );
	}
}
