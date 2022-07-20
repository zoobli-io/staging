<?php

namespace Voxel\Post_Types\Order_By;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Nearby_Order extends Base_Search_Order {

	protected $props = [
		'type' => 'nearby',
		'source' => '',
	];

	public function get_label(): string {
		return __( 'Dichtbij', 'voxel' );
	}

	public function get_models(): array {
		return [
			'source' => function() { ?>
				<div class="ts-form-group ts-col-1-1">
					<label>Location filter:</label>
					<select v-model="clause.source">
						<option v-for="filter in $root.getFiltersByType('location')" :value="filter.key">
							{{ filter.label }}
						</option>
					</select>
				</div>
			<?php }
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args, array $clause_args ): void {
		$lat = $clause_args[0] ?? null;
		$filter = $this->post_type->get_filter( $this->props['source'] );
		if ( $filter->get_type() === 'location' ) {
			$filter->orderby_distance( $query, $clause_args );
		}
	}
}
