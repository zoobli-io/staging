<?php

namespace Voxel\Post_Types\Filters\Traits;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

trait Numeric_Filter_Helpers {

	public function _get_max_int_size() {
		$max = max(
			absint( $this->props['range_start'] ),
			absint( $this->props['range_end'] )
		);

		return ceil( $max * $this->_get_value_multiplier() );
	}

	public function _get_value_multiplier() {
		$step = (float) abs( $this->props['step_size'] );
		$precision = strlen( substr( strrchr( $step, '.' ), 1 ) );

		return pow( 10, $precision );
	}

	public function _get_column_type() {
		$max = $this->_get_max_int_size();

		if ( $max < ((2**7) - 1) ) {
			return 'TINYINT';
		} elseif ( $max < ((2**15) - 1) ) {
			return 'SMALLINT';
		} elseif ( $max < ((2**23) - 1) ) {
			return 'MEDIUMINT';
		} elseif ( $max < ((2**31) - 1) ) {
			return 'INT';
		} else {
			return 'BIGINT';
		}
	}

	public function _prepare_value( $value ) {
		return intval( round( $value * $this->_get_value_multiplier(), 0 ) );
	}
}
