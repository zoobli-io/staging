<?php

namespace Voxel\Post_Types\Filters;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Location_Filter extends Base_Filter {

	protected $props = [
		'type' => 'location',
		'label' => 'Location',
		'source' => 'location',
	];

	public function get_models(): array {
		return [
			'label' => $this->get_label_model(),
			'description' => $this->get_description_model(),
			'key' => $this->get_key_model(),
			'icon' => $this->get_icon_model(),
			'source' => $this->get_source_model( 'location' ),
		];
	}

	public function setup( \Voxel\Post_Types\Index_Table $table ): void {
		// mariadb doesn't support srid attribute, while mysql requires it to use index
		$srid = ! \Voxel\is_using_mariadb() ? 'SRID 4326' : '';
		$table->add_column( sprintf( '`%s` POINT NOT NULL %s', esc_sql( $this->db_key() ), $srid ) );
		$table->add_key( sprintf( 'SPATIAL KEY(`%s`)', esc_sql( $this->db_key() ) ) );
	}

	public function index( \Voxel\Post $post ): array {
		$field = $post->get_field( $this->props['source'] );
		if ( ! ( $field && $field->get_type() === 'location' ) ) {
			$lat = 0;
			$lng = 0;
		} else {
			$value = $field->get_value();
			$lat = $value['latitude'] ?? 0;
			$lng = $value['longitude'] ?? 0;
		}

		return [
			$this->db_key() => sprintf( 'ST_PointFromText( \'POINT(%s %s)\', 4326 )', $lat, $lng ),
		];
	}

	public function query( \Voxel\Post_Types\Index_Query $query, array $args ): void {
		$value = $this->parse_value( $args[ $this->get_key() ] ?? null );
		if ( $value === null ) {
			return;
		}

		// handle idl meridian
		if ( $value['nelng'] < $value['swlng'] ) {
			$polygon = sprintf(
				'MULTIPOLYGON(((%s %s,%s %s,%s %s,%s %s,%s %s)),((%s %s,%s %s,%s %s,%s %s,%s %s)))',
				// first polygon
				$value['swlat'], $value['swlng'],
				$value['swlat'], 180,
				$value['nelat'], 180,
				$value['nelat'], $value['swlng'],
				$value['swlat'], $value['swlng'],

				// second polygon
				$value['swlat'], -180,
				$value['swlat'], $value['nelng'],
				$value['nelat'], $value['nelng'],
				$value['nelat'], -180,
				$value['swlat'], -180,
			);
		} else {
			$polygon = sprintf(
				'POLYGON((%s %s,%s %s,%s %s,%s %s,%s %s))',
				$value['swlat'], $value['swlng'],
				$value['swlat'], $value['nelng'],
				$value['nelat'], $value['nelng'],
				$value['nelat'], $value['swlng'],
				$value['swlat'], $value['swlng'],
			);
		}

		$query->where( sprintf(
			'ST_Contains( ST_GeomFromText( \'%s\', 4326 ), `%s` )',
			esc_sql( $polygon ),
			esc_sql( $this->db_key() )
		) );
	}

	public function orderby_distance( \Voxel\Post_Types\Index_Query $query, array $coordinates ): void {
		$lat = $coordinates[0];
		$lng = $coordinates[1];
		if ( $lat === null || $lng === null ) {
			return;
		}

		$lat = floatval( $lat );
		$lng = floatval( $lng );
		if ( $lat > 90 || $lat < -90 || $lng > 180 || $lng < -180 ) {
			return;
		}

		$orderby_key = $this->db_key().'_distance';

		$query->select( sprintf(
			'ST_DISTANCE( ST_GeomFromText( \'%s\', 4326 ), `%s` ) AS `%s`',
			sprintf( 'POINT(%d %d)', $lat, $lng ),
			esc_sql( $this->db_key() ),
			esc_sql( $orderby_key )
		) );

		$query->orderby( sprintf( '`%s` ASC', esc_sql( $orderby_key ) ) );
	}

	public function parse_value( $value ) {
		preg_match( '/(?P<address>.*);(?P<swlat>.*),(?P<swlng>.*)\.\.(?P<nelat>.*),(?P<nelng>.*)/i', $value, $matches );

		if ( ! isset( $matches['address'], $matches['swlat'], $matches['swlng'], $matches['nelat'], $matches['nelng'] ) ) {
			return null;
		}

		$address = $matches['address'] ?? '';
		$swlat = floatval( $matches['swlat'] );
		$swlng = floatval( $matches['swlng'] );
		$nelat = floatval( $matches['nelat'] );
		$nelng = floatval( $matches['nelng'] );

		if ( ( $swlat > 90 || $swlat < -90 ) || ( $nelat > 90 || $nelat < -90 ) ) {
			return null;
		}

		if ( ( $swlng > 180 || $swlng < -180 ) || ( $nelng > 180 || $nelng < -180 ) ) {
			return null;
		}

		return [
			'address' => $address,
			'swlat' => $swlat,
			'swlng' => $swlng,
			'nelat' => $nelat,
			'nelng' => $nelng,
		];
	}

	public function frontend_props() {
		wp_enqueue_script( 'vx:google-maps.js' );
		wp_enqueue_script( 'google-maps' );

		$value = $this->parse_value( $this->get_value() );
		return [
			'value' => [
				'address' => $value ? $value['address'] : null,
				'swlat' => $value ? $value['swlat'] : null,
				'swlng' => $value ? $value['swlng'] : null,
				'nelat' => $value ? $value['nelat'] : null,
				'nelng' => $value ? $value['nelng'] : null,
			],
		];
	}

	public function get_elementor_controls(): array {
		return [
			'box' => [
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => <<<HTML
					<h3 class="elementor-control-title"><strong>Default search area</strong></h3>
					<p class="elementor-control-field-description">
						Enter coordinates for the southwest and northeast points of the default area to be searched.
					</p>
				HTML,
			],
			'southwest' => [
				'label' => _x( 'Southwest ', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			],
			'swlat' => [
				'label' => _x( 'Latitude', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -90,
				'max' => 90,
				'classes' => 'ts-half-width',
			],
			'swlng' => [
				'label' => _x( 'Longitude', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -180,
				'max' => 180,
				'classes' => 'ts-half-width',
			],
			'northeast' => [
				'label' => _x( 'Northeast', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::HEADING,
			],
			'nelat' => [
				'label' => _x( 'Latitude', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -90,
				'max' => 90,
				'classes' => 'ts-half-width',
			],
			'nelng' => [
				'label' => _x( 'Longitude', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -180,
				'max' => 180,
				'classes' => 'ts-half-width',
			],
			'address' => [
				'label' => _x( 'Default address', 'date filter', 'voxel' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			],
		];
	}

	public function get_default_value_from_elementor( $controls ) {
		$address = $controls['address'] ?? null;
		$swlat = $controls['swlat'] ?? null;
		$swlng = $controls['swlng'] ?? null;
		$nelat = $controls['nelat'] ?? null;
		$nelng = $controls['nelng'] ?? null;
		if ( ! ( $address && $swlat && $swlng && $nelat && $nelng ) ) {
			return null;
		}

		return sprintf( '%s;%s,%s..%s,%s', $address, $swlat, $swlng, $nelat, $nelng );
	}
}
