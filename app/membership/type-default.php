<?php

namespace Voxel\Membership;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Type_Default extends Base_Type {

	protected $type = 'default';

	public function is_active() {
		return true;
	}

}
