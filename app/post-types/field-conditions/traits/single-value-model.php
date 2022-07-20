<?php

namespace Voxel\Post_Types\Field_Conditions\Traits;

if ( ! defined('ABSPATH') ) {
	exit;
}

trait Single_Value_Model {

	protected function props(): array {
		return [
			'value' => '',
		];
	}

	public function get_models(): array {
		return [
			'value' => [
				'type' => \Voxel\Form_Models\Text_Model::class,
				'label' => 'Value',
				'width' => '1/2',
			],
		];
	}
}
