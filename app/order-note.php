<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Order_Note {

	const COMMENT = 'comment';
	const CHECKOUT_CANCELED = 'customer.checkout_canceled';
	const PAYMENT_AUTHORIZED = 'customer.payment_authorized';
	const AUTHOR_APPROVED = 'author.approved';
	const AUTHOR_DECLINED = 'author.declined';
	const AUTHOR_REFUND_APPROVED = 'author.refund_approved';
	const AUTHOR_REFUND_DECLINED = 'author.refund_declined';
	const CUSTOMER_CANCELED = 'customer.canceled';
	const CUSTOMER_REFUND_REQUESTED = 'customer.refund_requested';
	const CUSTOMER_REFUND_REQUEST_CANCELED = 'customer.refund_request_canceled';

	private
		$id,
		$order_id,
		$type,
		$details,
		$created_at;

	private static $instances = [];

	public static function get( $id ) {
		if ( is_array( $id ) ) {
			$data = $id;
			$id = $data['id'];
			if ( ! array_key_exists( $id, self::$instances ) ) {
				self::$instances[ $id ] = new \Voxel\Order_Note( $data );
			}
		} elseif ( is_numeric( $id ) ) {
			if ( ! array_key_exists( $id, self::$instances ) ) {
				$results = self::query( [ 'id' => $id, 'limit' => 1 ] );
				self::$instances[ $id ] = isset( $results[0] ) ? $results[0] : null;
			}
		}

		return self::$instances[ $id ];
	}

	public static function create( array $data ): \Voxel\Order_Note {
		global $wpdb;
		$data = array_merge( [
			'order_id' => null,
			'type' => null,
			'details' => null,
			'created_at' => \Voxel\utc()->format( 'Y-m-d H:i:s' ),
		], $data );

		if ( is_null( $data['order_id'] ) || is_null( $data['type'] ) ) {
			throw new \Exception( _x( 'Couldn\'t create note: missing data.', 'orders', 'voxel' ) );
		}

		$escaped_data = [];
		$escaped_data[ 'order_id' ] = absint( $data['order_id'] );
		$escaped_data[ 'type' ] = sprintf( '\'%s\'', esc_sql( $data[ 'type' ] ) );
		$escaped_data[ 'created_at' ] = sprintf( '\'%s\'', esc_sql( $data[ 'created_at' ] ) );

		if ( ! is_null( $data['details'] ) ) {
			$escaped_data[ 'details' ] = sprintf( '\'%s\'', esc_sql( $data[ 'details' ] ) );
		}

		$columns = join( ', ', array_map( function( $column_name ) {
			return sprintf( '`%s`', esc_sql( $column_name ) );
		}, array_keys( $escaped_data ) ) );

		$values = join( ', ', $escaped_data );

		$on_duplicate = join( ', ', array_map( function( $column_name ) {
			return sprintf( '`%s`=VALUES(`%s`)', $column_name, $column_name );
		}, array_keys( $escaped_data ) ) );

		$sql = "INSERT INTO {$wpdb->prefix}voxel_order_notes ($columns) VALUES ($values)
					ON DUPLICATE KEY UPDATE $on_duplicate";

		$wpdb->query( $sql );
		$data['id'] = $wpdb->insert_id;

		return new \Voxel\Order_Note( $data );
	}

	public static function query( array $args ): array {
		global $wpdb;
		$args = array_merge( [
			'id' => null,
			'order_id' => null,
			'type' => null,
			'offset' => null,
			'limit' => null,
		], $args );

		$where_clauses = [];
		$join_posts = false;

		if ( ! is_null( $args['id'] ) ) {
			$where_clauses[] = sprintf( 'notes.id = %d', absint( $args['id'] ) );
		}

		if ( ! is_null( $args['order_id'] ) ) {
			$where_clauses[] = sprintf( 'notes.order_id = %d', absint( $args['order_id'] ) );
		}

		if ( ! is_null( $args['type'] ) ) {
			$where_clauses[] = sprintf( 'notes.type = \'%s\'', esc_sql( $args['type'] ) );
		}

		// generate sql string
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

		$sql = "
			SELECT notes.* FROM {$wpdb->prefix}voxel_order_notes AS notes
			{$wheres} ORDER BY notes.created_at ASC {$limit} {$offset}
		";

		// dump_sql( $sql );die;
		$results = $wpdb->get_results( $sql, ARRAY_A );
		if ( ! is_array( $results ) ) {
			return [];
		}

		return array_map( '\Voxel\Order_Note::get', $results );
	}


	public function __construct( array $data ) {
		$this->id = absint( $data['id'] );
		$this->order_id = absint( $data['order_id'] );
		$this->type = $data['type'];
		$this->created_at = $data['created_at'];
		$this->details = json_decode( $data['details'], ARRAY_A );
	}

	public function prepare() {
		if ( $this->type === static::COMMENT ) {
			$details = (array) $this->details;
			$user = \Voxel\User::get( $details['user_id'] ?? null );
			$file_field = new \Voxel\Product_Types\Order_Comments\Comment_Files_Field;

			return [
				'type' => 'comment',
				'author' => [
					'name' => $user ? $user->get_display_name() : _x( '(deleted account)', 'deleted user account', 'voxel' ),
					'avatar' => $user ? $user->get_avatar_markup() : null,
					'link' => $user ? $user->get_link() : null,
				],
				'time' => $this->get_time_for_display(),
				'message' => $details['message'] ?? null,
				'files' => $file_field->prepare_for_display( $details['files'] ?? '' ),
			];
		} else {
			$messages = [
				static::CHECKOUT_CANCELED => _x( 'Checkout canceled by user.', 'orders', 'voxel' ),
				static::PAYMENT_AUTHORIZED => _x( 'Funds have been authorized and the order is awaiting approval by the vendor.', 'orders', 'voxel' ),
				static::AUTHOR_APPROVED => _x( 'Order has been approved by the vendor and funds have been transferred.', 'orders', 'voxel' ),
				static::AUTHOR_DECLINED => _x( 'Order has been declined by the vendor and assets have been refunded.', 'orders', 'voxel' ),
				static::AUTHOR_REFUND_APPROVED => _x( 'Refund request approved by vendor.', 'orders', 'voxel' ),
				static::AUTHOR_REFUND_DECLINED => _x( 'Refund request declined by vendor.', 'orders', 'voxel' ),
				static::CUSTOMER_CANCELED => _x( 'Order canceled by customer.', 'orders', 'voxel' ),
				static::CUSTOMER_REFUND_REQUESTED => _x( 'Refund requested submitted by customer.', 'orders', 'voxel' ),
				static::CUSTOMER_REFUND_REQUEST_CANCELED => _x( 'Customer canceled their refund request.', 'orders', 'voxel' ),
			];

			return [
				'type' => 'system',
				'icon' => \Voxel\get_icon_markup( 'la-solid:las la-robot' ),
				'time' => $this->get_time_for_display(),
				'message' => $messages[ $this->type ] ?? $this->type,
			];
		}
	}

	public function get_time_for_display() {
		$from = strtotime( $this->created_at ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		$to = current_time( 'timestamp' );
		$diff = (int) abs( $to - $from );
		if ( $diff < WEEK_IN_SECONDS ) {
			return sprintf( _x( '%s ago', 'order note created at', 'voxel' ), human_time_diff( $from, $to ) );
		}

		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $from );
	}

}
