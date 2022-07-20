<?php

namespace Voxel\Post_Types\Field_Conditions;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Date_Gt extends Base_Condition {
	use Traits\Single_Value_Model;

	public function get_type(): string {
		return 'date:gt';
	}

	public function get_label(): string {
		return _x( 'Greater than', 'field conditions', 'voxel' );
	}
}
