<?php

namespace Voxel\Product_Types;

if ( ! defined('ABSPATH') ) {
	exit;
}

trait Order_Singleton_Trait {

	/**
	 * Store product type instances.
	 *
	 * @since 1.0
	 */
	private static $instances = [];

	/**
	 * Get an order based on its id.
	 *
	 * @since 1.0
	 */
	public static function get( $id ) {
		if ( is_array( $id ) ) {
			$data = $id;
			$id = $data['id'];
			if ( ! array_key_exists( $id, self::$instances ) ) {
				self::$instances[ $id ] = new \Voxel\Order( $data );
			}
		} elseif ( is_numeric( $id ) ) {
			if ( ! array_key_exists( $id, self::$instances ) ) {
				$results = self::query( [ 'id' => $id, 'limit' => 1 ] );
				self::$instances[ $id ] = isset( $results[0] ) ? $results[0] : null;
			}
		}

		return self::$instances[ $id ];
	}

	public static function query( array $args ): array {
		return \Voxel\Product_Types\Order_Repository::query( $args );
	}

	public static function find( array $args ) {
		$args['limit'] = 1;
		$args['offset'] = null;
		$results = static::query( $args );
		return array_shift( $results );
	}

	public static function create( array $data ): \Voxel\Order {
		return \Voxel\Product_Types\Order_Repository::create( $data );
	}
}
