<?php

namespace Voxel\Post_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Keywords_Filter extends Base_Filter {

	protected $supported_field_types = [
		'title',
		'description',
		'text',
		'texteditor',
		'location',
		'taxonomy',
		'profile-name',
	];

	protected $modes = [
		'natural' => 'IN NATURAL LANGUAGE MODE',
		'expansion' => 'WITH QUERY EXPANSION',
		'boolean' => 'IN BOOLEAN MODE',
	];

	protected $props = [
		'type' => 'keywords',
		'label' => 'Keywords',
		'mode' => 'natural',

		// by default search for matches in title and description
		'sources' => [
			'title',
			'description',
		],
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'description' => $this->get_description_model(),
			'key' => $this->get_key_model(),
			'icon' => $this->get_icon_model(),
			'sources' => function() { ?>
				<div class="ts-form-group ts-col-1-1 ts-checkbox">
					<label>Look for matches in:</label>
					<div class="ts-checkbox-container two-column min-scroll">
						<label v-for="field in $root.getFieldsByType( <?= esc_attr( wp_json_encode( $this->supported_field_types ) ) ?> )"
							class="container-checkbox">
							{{ field.label }}
							<input type="checkbox" :value="field.key" v-model="filter.sources">
							<span class="checkmark"></span>
						</label>
					</div>
				</div>
			<?php },
			'mode' => [
				'type' => \Voxel\Form_Models\Select_Model::class,
				'label' => 'Search mode',
				'width' => '1/1',
				'choices' => [
					'natural' => 'Natural language mode',
					'expansion' => 'Natural language mode with query expansion',
					'boolean' => 'Boolean mode',
				],
			],
		];
	}

	public function setup( \Voxel\Post_Types\Index_Table $table ): void {
		$table->add_column( sprintf( '`%s` TEXT NOT NULL', esc_sql( $this->db_key() ) ) );
		$table->add_key( sprintf( 'FULLTEXT(`%s`)', esc_sql( $this->db_key() ) ) );
	}

	public function index( \Voxel\Post $post ): array {
		$values = [];
		foreach ( $this->props['sources'] as $field_key ) {
			$field = $post->get_field( $field_key );
			if ( ! $field ) {
				continue;
			}

			if ( $field->get_type() === 'taxonomy' ) {
				$values[] = join( ', ', $field->get_value() );
			} elseif ( $field->get_type() === 'location' ) {
				$values[] = $field->get_value()['address'] ?? null;
			} else {
				$values[] = $field->get_value();
			}
		}

		$data = join( '; ', array_filter( $values ) );
		$data = wp_strip_all_tags( $data );

		return [
			$this->db_key() => sprintf( '\'%s\'', esc_sql( $data ) ),
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		$keywords = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $keywords === null ) {
			return;
		}

		$query->where( sprintf(
			'MATCH (`%s`) AGAINST (\'%s\' %s)',
			esc_sql( $this->db_key() ),
			esc_sql( $keywords ),
			$this->modes[ $this->props['mode'] ] ?? $this->modes['natural']
		) );
	}

	public function orderby_relevance( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		$keywords = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $keywords === null ) {
			return;
		}

		$orderby_key = $this->db_key().'_relevance';

		$query->select( sprintf(
			'MATCH (`%s`) AGAINST (\'%s\' %s) AS `%s`',
			esc_sql( $this->db_key() ),
			esc_sql( $keywords ),
			$this->modes[ $this->props['mode'] ] ?? $this->modes['natural'],
			esc_sql( $orderby_key )
		) );

		$query->orderby( sprintf( '`%s` DESC', esc_sql( $orderby_key ) ) );
	}

	public function parse_value( $value ) {
		return ( is_string( $value ) && ! empty( $value ) )
			? sanitize_text_field( $value )
			: null;
	}

	public function get_elementor_controls(): array {
		return [
			'value' => [
				'label' => _x( 'Default value', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			],
		];
	}
}
