<?php

namespace Voxel\Post_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Recurring_Date_Filter extends Base_Filter {
	use Traits\Date_Filter_Helpers;

	protected $props = [
		'type' => 'recurring-date',
		'label' => 'Terugkerende datum',
		'source' => 'recurring-date',
		'input_mode' => 'date-range',
		'match_ongoing' => true,
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'description' => $this->get_description_model(),
			'key' => $this->get_key_model(),
			'icon' => $this->get_icon_model(),
			'source' => $this->get_source_model( 'recurring-date' ),
			'input_mode' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'label' => 'Input mode',
				'width' => '1/1',
				'choices' => [
					'date-range' => 'Date range',
					'single-date' => 'Single date',
				],
			],
			'match_ongoing' => [
				'type' => \Voxel\Form_Models\Switcher_Model::class,
				'label' => 'Afstemmen met de bestaande datums',
				'description' => 'Stel in of datums die al zijn begonnen maar nog niet zijn geëindigd, met elkaar moeten matchen.',
			]
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		$value = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $value === null ) {
			return;
		}

		global $wpdb;

		$range_start = esc_sql( $value['start'] );
		$range_end = esc_sql( $value['end'] );
		$join_key = esc_sql( $this->db_key() );
		$post_type_key = esc_sql( $this->post_type->get_key() );
		$field_key = esc_sql( $this->props['source'] );
		$where_clause = \Voxel\Utils\Recurring_Date\get_where_clause( $value['start'], $value['end'], $this->props['input_mode'], $this->props['match_ongoing'] );

		$query->join( "
			INNER JOIN (
				SELECT post_id FROM {$wpdb->prefix}voxel_events
				WHERE `post_type` = '{$post_type_key}' AND `field_key` = '{$field_key}' AND ( {$where_clause} )
			) AS `{$join_key}` ON `{$query->table->get_escaped_name()}`.post_id = `{$join_key}`.post_id
		" );

		$query->groupby( "`{$query->table->get_escaped_name()}`.post_id" );
	}
}
