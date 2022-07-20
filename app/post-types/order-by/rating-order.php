<?php

namespace Voxel\Post_Types\Order_By;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Rating_Order extends Base_Search_Order {

	protected $props = [
		'type' => 'rating',
		'order' => 'DESC',
	];

	public function get_label(): string {
		return __( 'Beoordeling', 'voxel' );
	}

	public function get_models(): array {
		return [
			'order' => $this->get_order_model(),
		];
	}
}
