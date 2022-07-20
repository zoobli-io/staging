<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

class User {

	private $wp_user;
	private $membership;
	private $account_details;

	private static $instances = [];
	public static function get( $user ) {
		if ( is_numeric( $user ) ) {
			$user = get_userdata( $user );
		}

		if ( ! $user instanceof \WP_User ) {
			return null;
		}

		if ( ! array_key_exists( $user->ID, self::$instances ) ) {
			self::$instances[ $user->ID ] = new self( $user );
		}

		return self::$instances[ $user->ID ];
	}

	public static function get_by_account_id( $account_id ) {
		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_stripe_account_id' : 'voxel:stripe_account_id';
		$results = get_users( [
			'meta_key' => $meta_key,
			'meta_value' => $account_id,
			'number' => 1,
			'fields' => 'ID',
		] );

		return \Voxel\User::get( array_shift( $results ) );
	}

	public static function get_by_customer_id( $customer_id ) {
		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_stripe_customer_id' : 'voxel:stripe_customer_id';
		$results = get_users( [
			'meta_key' => $meta_key,
			'meta_value' => $customer_id,
			'number' => 1,
			'fields' => 'ID',
		] );

		return \Voxel\User::get( array_shift( $results ) );
	}

	public static function get_by_profile_id( $profile_id ) {
		$results = get_users( [
			'meta_key' => 'voxel:profile_id',
			'meta_value' => $profile_id,
			'number' => 1,
			'fields' => 'ID',
		] );

		return \Voxel\User::get( array_shift( $results ) );
	}

	private function __construct( \WP_User $user ) {
		$this->wp_user = $user;
	}

	public function get_id() {
		return $this->wp_user->ID;
	}

	public function get_link() {
		return get_author_posts_url( $this->get_id() );
	}

	public function get_display_name() {
		$display_name = $this->wp_user->display_name;
		return ! empty( $display_name ) ? $display_name : $this->get_username();
	}

	public function get_email() {
		return $this->wp_user->user_email;
	}

	public function get_username() {
		return $this->wp_user->user_login;
	}

	public function get_first_name() {
		return $this->wp_user->first_name;
	}

	public function get_last_name() {
		return $this->wp_user->last_name;
	}

	public function get_roles() {
		return $this->wp_user->roles;
	}

	public function has_role( $role ) {
		return in_array( $role, $this->get_roles(), true );
	}

	public function get_avatar_id() {
		$avatar_id = get_user_meta( $this->get_id(), 'voxel:avatar', true );
		if ( $avatar_id ) {
			return $avatar_id;
		}

		$field = \Voxel\Post_Type::get('profile')->get_field('voxel:avatar');
		$default = $field ? $field->get_prop('default') : null;
		if ( $default ) {
			return $default;
		}

		return null;
	}

	public function get_avatar_markup() {
		return get_avatar( $this->get_id(), 96, '', '', [
			'class' => 'ts-status-avatar',
		] );
	}

	public function is_confirmed() {
		return ! get_user_meta( $this->get_id(), 'voxel:confirmation', true );
	}

	public function send_confirmation_code() {
		$code = \Voxel\random_string(5);
		$subject = 'Account confirmation';
		$message = sprintf( 'Your confirmation code is %s', $code );

		wp_mail( $this->get_email(), $subject, $message, [
			'Content-type: text/html; charset: '.get_bloginfo( 'charset' ),
		] );

		// give user 30 minutes to enter correct code
		update_user_meta( $this->get_id(), 'voxel:confirmation', wp_json_encode( [
			'code' => password_hash( $code, PASSWORD_DEFAULT ),
			'expires' => time() + ( 30 * MINUTE_IN_SECONDS ),
		] ) );
	}

	public function verify_confirmation_code( $code ) {
		$confirmation = json_decode( get_user_meta( $this->get_id(), 'voxel:confirmation', true ), ARRAY_A );
		if ( ! is_array( $confirmation ) || empty( $confirmation['code'] ) || empty( $confirmation['expires'] ) ) {
			throw new \Exception( _x( 'Invalid request.', 'confirm account', 'voxel' ) );
		}

		if ( $confirmation['expires'] < time() ) {
			throw new \Exception( _x( 'Please try again.', 'confirm account', 'voxel' ) );
		}

		if ( ! password_verify( $code, $confirmation['code'] ) ) {
			throw new \Exception( _x( 'Code is not correct.', 'confirm account', 'voxel' ) );
		}
	}

	public function send_recovery_code() {
		$code = \Voxel\random_string(10);
		$subject = 'Account recovery';
		$message = sprintf( 'Your recovery code is %s', $code );

		wp_mail( $this->get_email(), $subject, $message, [
			'Content-type: text/html; charset: '.get_bloginfo( 'charset' ),
		] );

		// give user 2 minutes to enter correct code
		update_user_meta( $this->get_id(), 'voxel:recovery', wp_json_encode( [
			'code' => password_hash( $code, PASSWORD_DEFAULT ),
			'expires' => time() + ( 2 * MINUTE_IN_SECONDS ),
		] ) );
	}

	public function verify_recovery_code( $code ) {
		$recovery = json_decode( get_user_meta( $this->get_id(), 'voxel:recovery', true ), ARRAY_A );
		if ( ! is_array( $recovery ) || empty( $recovery['code'] ) || empty( $recovery['expires'] ) ) {
			throw new \Exception( _x( 'Invalid request.', 'recover account', 'voxel' ) );
		}

		if ( $recovery['expires'] < time() ) {
			throw new \Exception( _x( 'Recovery session has expired.', 'recover account', 'voxel' ) );
		}

		if ( ! password_verify( $code, $recovery['code'] ) ) {
			throw new \Exception( _x( 'Code is not correct.', 'recover account', 'voxel' ) );
		}
	}

	public function send_email_update_code( $email ) {
		$code = \Voxel\random_string(5);
		$subject = 'Update email address';
		$message = sprintf( 'Your confirmation code is %s', $code );

		wp_mail( $email, $subject, $message, [
			'Content-type: text/html; charset: '.get_bloginfo( 'charset' ),
		] );

		// give user 2 minutes to enter correct code
		update_user_meta( $this->get_id(), 'voxel:email_update', wp_json_encode( [
			'code' => password_hash( $code, PASSWORD_DEFAULT ),
			'expires' => time() + ( 5 * MINUTE_IN_SECONDS ),
			'email' => $email,
		] ) );
	}

	public function verify_email_update_code( $code ) {
		$update = json_decode( get_user_meta( $this->get_id(), 'voxel:email_update', true ), ARRAY_A );
		if ( ! is_array( $update ) || empty( $update['code'] ) || empty( $update['expires'] ) ) {
			throw new \Exception( _x( 'Invalid request.', 'update email', 'voxel' ) );
		}

		if ( $update['expires'] < time() ) {
			throw new \Exception( _x( 'Code has expired.', 'update email', 'voxel' ) );
		}

		if ( ! password_verify( $code, $update['code'] ) ) {
			throw new \Exception( _x( 'Code is not correct.', 'update email', 'voxel' ) );
		}

		return $update['email'] ?? null;
	}


	public function get_stripe_account_id() {
		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_stripe_account_id' : 'voxel:stripe_account_id';
		return get_user_meta( $this->get_id(), $meta_key, true );
	}

	public function get_stripe_account() {
		$account_id = $this->get_stripe_account_id();
		if ( empty( $account_id ) ) {
			throw new \Exception( _x( 'Stripe account not set up for this user.', 'orders', 'voxel' ) );
		}

		$stripe = \Voxel\Stripe::getClient();
		return $stripe->accounts->retrieve( $account_id );
	}

	public function get_or_create_stripe_account() {
		try {
			$account = $this->get_stripe_account();
		} catch ( \Exception $e ) {
			$stripe = \Voxel\Stripe::getClient();
			$account = $stripe->accounts->create( [
				'type' => 'express',
				'email' => $this->get_email(),
				'capabilities' => [
					'card_payments' => ['requested' => true],
					'transfers' => ['requested' => true],
				],
			] );

			$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_stripe_account_id' : 'voxel:stripe_account_id';
			update_user_meta( $this->get_id(), $meta_key, $account->id );
			do_action( 'voxel/connect/account-updated', $account );
		}

		return $account;
	}

	public function get_stripe_account_details() {
		if ( ! is_null( $this->account_details ) ) {
			return $this->account_details;
		}

		$account_id = $this->get_stripe_account_id();
		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_stripe_account' : 'voxel:stripe_account';
		$details = (array) json_decode( get_user_meta( $this->get_id(), $meta_key, true ), ARRAY_A );

		$this->account_details = (object) [
			'exists' => ! empty( $account_id ),
			'id' => $account_id,
			'charges_enabled' => $details['charges_enabled'] ?? false,
			'details_submitted' => $details['details_submitted'] ?? false,
			'payouts_enabled' => $details['payouts_enabled'] ?? false,
		];

		return $this->account_details;
	}

	public function get_stripe_customer_id() {
		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_stripe_customer_id' : 'voxel:stripe_customer_id';
		return get_user_meta( $this->get_id(), $meta_key, true );
	}

	public function get_stripe_customer() {
		$customer_id = $this->get_stripe_customer_id();
		if ( empty( $customer_id ) ) {
			throw new \Exception( _x( 'Stripe customer account not set up for this user.', 'orders', 'voxel' ) );
		}

		$stripe = \Voxel\Stripe::getClient();
		return $stripe->customers->retrieve( $customer_id );
	}

	public function get_or_create_stripe_customer() {
		try {
			$customer = $this->get_stripe_customer();
		} catch ( \Exception $e ) {
			$stripe = \Voxel\Stripe::getClient();
			$customer = $stripe->customers->create( [
				'email' => $this->get_email(),
				'name' => $this->get_display_name(),
			] );

			$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_stripe_customer_id' : 'voxel:stripe_customer_id';
			update_user_meta( $this->get_id(), $meta_key, $customer->id );
		}

		return $customer;
	}

	public function get_membership( $refresh_cache = false ) {
		if ( $refresh_cache ) {
			$this->membership = null;
		}

		if ( ! is_null( $this->membership ) ) {
			return $this->membership;
		}

		$meta_key = \Voxel\Stripe::is_test_mode() ? 'voxel:test_plan' : 'voxel:plan';
		$details = (array) json_decode( get_user_meta( $this->get_id(), $meta_key, true ), ARRAY_A );
		$type = $details['type'] ?? 'default';

		if ( $type === 'subscription' ) {
			$this->membership = new \Voxel\Membership\Type_Subscription( $details );
		} else {
			$this->membership = new \Voxel\Membership\Type_Default( [] );
		}

		return $this->membership;
	}

	public function can_create_post( string $post_type_key ): bool {
		if ( current_user_can('administrator') || current_user_can('editor') ) {
			return true;
		}

		$post_type = \Voxel\Post_Type::get( $post_type_key );
		$membership = $this->get_membership();
		$plan = $membership->plan;
		if ( ! $plan ) {
			return false;
		}

		$config = $plan->get_config();
		$submissions = (array) ( $config['submissions'] ?? [] );

		if ( ! isset( $submissions[ $post_type->get_key() ] ) ) {
			return false;
		}

		$limit = absint( $submissions[ $post_type->get_key() ] );
		if ( $limit < 1 ) {
			return false;
		}

		global $wpdb;
		$limit_reached = !! $wpdb->get_var( $wpdb->prepare( <<<SQL
			SELECT COUNT(ID) >= %d
				FROM {$wpdb->posts}
			WHERE post_type = %s
				AND post_status IN ('publish','pending')
				AND post_author = %d
		SQL, $limit, $post_type->get_key(), $this->get_id() ) );

		if ( $limit_reached ) {
			return false;
		}

		return true;
	}

	public function get_profile_id() {
		return get_user_meta( $this->get_id(), 'voxel:profile_id', true );
	}

	public function get_profile() {
		return \Voxel\Post::get( $this->get_profile_id() );
	}

	public function get_or_create_profile() {
		$profile = $this->get_profile();
		if ( $profile ) {
			return $profile;
		}

		$profile_id = wp_insert_post( [
			'post_type' => 'profile',
			'post_author' => $this->get_id(),
			'post_status' => 'draft',
		] );

		if ( is_wp_error( $profile_id ) ) {
			return null;
		}

		update_user_meta( $this->get_id(), 'voxel:profile_id', $profile_id );
		return \Voxel\Post::get( $profile_id );
	}

	public function can_review_post( $post_id ): bool {
		$post = \Voxel\Post::get( $post_id );
		if ( ! $post ) {
			return false;
		}

		return $post->post_type->get_setting( 'timeline.reviews' ) === 'public' || (
			$post->post_type->get_setting( 'timeline.reviews' ) === 'followers_only'
			&& \Voxel\get_post_follow_status( $post->get_id(), get_current_user_id() ) === \Voxel\FOLLOW_ACCEPTED
		);
	}

	public function has_reviewed_post( $post_id ): bool {
		$existing_review = \Voxel\Timeline\Status::query( [
			'match' => 'reviews',
			'user_id' => $this->get_id(),
			'post_id' => $post_id,
			'limit' => 1,
		] );

		return ! empty( $existing_review );
	}

	public function can_post_to_wall( $post_id ): bool {
		$post = \Voxel\Post::get( $post_id );
		if ( ! $post ) {
			return false;
		}

		return $post->post_type->get_setting( 'timeline.wall' ) === 'public' || (
			$post->post_type->get_setting( 'timeline.wall' ) === 'followers_only'
			&& \Voxel\get_post_follow_status( $post->get_id(), get_current_user_id() ) === \Voxel\FOLLOW_ACCEPTED
		);
	}

	public function follows_post( $post_id ) {
		return \Voxel\get_post_follow_status( $post_id, $this->get_id() ) === \Voxel\FOLLOW_ACCEPTED;
	}

	public function is_verified(): bool {
		$profile = $this->get_profile();
		return $profile ? $profile->is_verified() : false;
	}

	public function get_follow_stats() {
		$stats = (array) json_decode( get_user_meta( $this->get_id(), 'voxel:follow_stats', true ), ARRAY_A );
		if ( ! isset( $stats['followed'] ) ) {
			$stats = \Voxel\cache_user_follow_stats( $this->get_id() );
		}

		return $stats;
	}

	public function get_post_stats() {
		$stats = json_decode( get_user_meta( $this->get_id(), 'voxel:post_stats', true ), ARRAY_A );
		if ( ! is_array( $stats ) ) {
			$stats = \Voxel\cache_user_post_stats( $this->get_id() );
		}

		return $stats;
	}

	public function get_wp_user_object() {
		return $this->wp_user;
	}

	public function has_reached_status_rate_limit(): bool {
		if ( current_user_can( 'administrator' ) ) {
			return false;
		}

		return \Voxel\Timeline\user_has_reached_status_rate_limit( $this->get_id() );
	}

	public function has_reached_reply_rate_limit(): bool {
		if ( current_user_can( 'administrator' ) ) {
			return false;
		}

		return \Voxel\Timeline\user_has_reached_reply_rate_limit( $this->get_id() );
	}

	public static function dummy() {
		return static::get( new \WP_User( (object) [ 'ID' => 0 ] ) );
	}
}
