<?php

namespace Voxel\Post_Types\Field_Conditions;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Number_Lte extends Base_Condition {
	use Traits\Single_Value_Model;

	public function get_type(): string {
		return 'number:lte';
	}

	public function get_label(): string {
		return _x( 'Less than or equal to', 'field conditions', 'voxel' );
	}
}
