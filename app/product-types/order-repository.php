<?php

namespace Voxel\Product_Types;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Order_Repository {

	public static function query( array $args ): array {
		global $wpdb;
		$args = array_merge( [
			'id' => null,
			'post_id' => null,
			'product_type' => null,
			'product_key' => null,
			'author_id' => null,
			'customer_id' => null,
			'party_id' => null,
			'status' => null,
			'object_id' => null,
			'session_id' => null,
			'search' => null,
			'offset' => null,
			'limit' => 10,
		], $args );

		$join_clauses = [];
		$where_clauses = [];
		$join_posts = false;
		$join_authors = false;
		$join_customers = false;

		if ( ! is_null( $args['id'] ) ) {
			$where_clauses[] = sprintf( 'orders.id = %d', absint( $args['id'] ) );
		}

		if ( ! is_null( $args['post_id'] ) ) {
			$where_clauses[] = sprintf( 'orders.post_id = %d', absint( $args['post_id'] ) );
		}

		if ( ! is_null( $args['customer_id'] ) ) {
			$where_clauses[] = sprintf( 'orders.customer_id = %d', absint( $args['customer_id'] ) );
		}

		if ( ! is_null( $args['author_id'] ) ) {
			$join_posts = true;
			$where_clauses[] = sprintf( 'posts.post_author = %d', absint( $args['author_id'] ) );
		}

		if ( ! is_null( $args['party_id'] ) ) {
			$join_posts = true;
			$where_clauses[] = sprintf(
				'( orders.customer_id = %d OR posts.post_author = %d )',
				absint( $args['party_id'] ),
				absint( $args['party_id'] )
			);
		}

		if ( ! is_null( $args['status'] ) ) {
			if ( is_array( $args['status'] ) ) {
				$where_clauses[] = sprintf( 'orders.status IN (%s)', join( ',', array_map( 'esc_sql', $args['status'] ) ) );
			} else {
				$where_clauses[] = sprintf( 'orders.status = \'%s\'', esc_sql( $args['status'] ) );
			}
		}

		if ( ! is_null( $args['object_id'] ) ) {
			$where_clauses[] = sprintf( 'orders.object_id = \'%s\'', esc_sql( $args['object_id'] ) );
		}

		if ( ! is_null( $args['session_id'] ) ) {
			$where_clauses[] = sprintf( 'orders.session_id = \'%s\'', esc_sql( $args['session_id'] ) );
		}

		if ( ! is_null( $args['product_key'] ) ) {
			$where_clauses[] = sprintf( 'orders.product_key = \'%s\'', esc_sql( $args['product_key'] ) );
		}

		if ( ! is_null( $args['product_type'] ) ) {
			$where_clauses[] = sprintf( 'orders.product_type = \'%s\'', esc_sql( $args['product_type'] ) );
		}

		if ( ! is_null( $args['search'] ) ) {
			$join_posts = true;
			$join_authors = true;
			$join_customers = true;
			$like = '%'.$wpdb->esc_like( $args['search'] ).'%';

			$where_clauses[] = $wpdb->prepare( <<<SQL
				( posts.post_title LIKE %s
					OR authors.user_email = %s OR authors.display_name LIKE %s
					OR customers.user_email = %s OR customers.display_name LIKE %s )
			SQL, $like, $args['search'], $like, $args['search'], $like );
		}

		$where_clauses[] = sprintf( 'orders.testmode IS %s', \Voxel\Stripe::is_test_mode() ? 'true' : 'false' );

		if ( $join_posts ) {
			$join_clauses[] = "LEFT JOIN {$wpdb->posts} AS posts ON orders.post_id = posts.ID";

			if ( $join_authors ) {
				$join_clauses[] = "LEFT JOIN {$wpdb->users} AS authors ON posts.post_author = authors.ID";
			}
		}

		if ( $join_customers ) {
			$join_clauses[] = "LEFT JOIN {$wpdb->users} AS customers ON orders.customer_id = customers.ID";
		}


		// generate sql string
		$joins = join( " \n ", $join_clauses );
		$wheres = '';
		if ( ! empty( $where_clauses ) ) {
			$wheres = sprintf( 'WHERE %s', join( ' AND ', $where_clauses ) );
		}

		$limit = '';
		if ( ! is_null( $args['limit'] ) ) {
			$limit = sprintf( 'LIMIT %d', absint( $args['limit'] ) );
		}

		$offset = '';
		if ( ! is_null( $args['offset'] ) ) {
			$offset = sprintf( 'OFFSET %d', absint( $args['offset'] ) );
		}

		$sql = $wpdb->remove_placeholder_escape( "
			SELECT orders.* FROM {$wpdb->prefix}voxel_orders AS orders
			{$joins}
			{$wheres}
			ORDER BY orders.created_at DESC
			{$limit} {$offset}
		" );

		// dump_sql( $sql );die;
		$results = $wpdb->get_results( $sql, ARRAY_A );
		if ( ! is_array( $results ) ) {
			return [];
		}

		return array_map( '\Voxel\Order::get', $results );
	}

	public static function create( array $data ): \Voxel\Order {
		global $wpdb;
		$data = array_merge( [
			'id' => null,
			'post_id' => null,
			'product_type' => null,
			'product_key' => null,
			'customer_id' => null,
			'details' => null,
			'status' => null,
			'session_id' => null,
			'mode' => null,
			'object_id' => null,
			'object_details' => null,
			'created_at' => \Voxel\utc()->format( 'Y-m-d H:i:s' ),
		], $data );

		$required_data = $data;
		unset( $required_data['id'] );

		$sql = static::_generate_insert_query( $data );
		$wpdb->query( $sql );
		$data['id'] = $wpdb->insert_id;

		return new \Voxel\Order( $data );
	}

	public static function _generate_insert_query( array $data ) {
		global $wpdb;

		$escaped_data = [];
		foreach ( ['id', 'post_id', 'customer_id'] as $column_name ) {
			if ( isset( $data[ $column_name ] ) ) {
				$escaped_data[ $column_name ] = absint( $data[ $column_name ] );
			}
		}

		if ( isset( $data['details'] ) && is_array( $data['details'] ) ) {
			$data['details'] = wp_json_encode( $data['details'] );
		}

		if ( isset( $data['object_details'] ) ) {
			if ( $data['object_details'] instanceof \Stripe\PaymentIntent ) {
				$data['object_details'] = wp_json_encode( \Voxel\Order::get_intent_details( $data['object_details'] ) );
			}

			if ( $data['object_details'] instanceof \Stripe\Subscription ) {
				$data['object_details'] = wp_json_encode( \Voxel\Order::get_subscription_details( $data['object_details'] ) );
			}
		}

		foreach ( ['product_type', 'product_key', 'details', 'status', 'session_id', 'mode', 'object_id', 'object_details', 'created_at'] as $column_name ) {
			if ( isset( $data[ $column_name ] ) ) {
				$escaped_data[ $column_name ] = sprintf( '\'%s\'', esc_sql( $data[ $column_name ] ) );
			}
		}

		$escaped_data['testmode'] = \Voxel\Stripe::is_test_mode() ? 'true' : 'false';

		$columns = join( ', ', array_map( function( $column_name ) {
			return sprintf( '`%s`', esc_sql( $column_name ) );
		}, array_keys( $escaped_data ) ) );

		$values = join( ', ', $escaped_data );

		$on_duplicate = join( ', ', array_map( function( $column_name ) {
			return sprintf( '`%s`=VALUES(`%s`)', $column_name, $column_name );
		}, array_keys( $escaped_data ) ) );

		$sql = "INSERT INTO {$wpdb->prefix}voxel_orders ($columns) VALUES ($values)
					ON DUPLICATE KEY UPDATE $on_duplicate";

		return $sql;
	}
}
