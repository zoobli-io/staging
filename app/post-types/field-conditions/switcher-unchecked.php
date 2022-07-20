<?php

namespace Voxel\Post_Types\Field_Conditions;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Switcher_Unchecked extends Base_Condition {

	public function get_type(): string {
		return 'switcher:unchecked';
	}

	public function get_label(): string {
		return _x( 'Is unchecked', 'field conditions', 'voxel' );
	}
}
