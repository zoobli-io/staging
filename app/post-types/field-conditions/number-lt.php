<?php

namespace Voxel\Post_Types\Field_Conditions;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Number_Lt extends Base_Condition {
	use Traits\Single_Value_Model;

	public function get_type(): string {
		return 'number:lt';
	}

	public function get_label(): string {
		return _x( 'Less than', 'field conditions', 'voxel' );
	}
}
