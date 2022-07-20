<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Stripe {

	private static $liveClient, $testClient;

	public static function is_test_mode() {
		return ( !! \Voxel\get( 'settings.stripe.test_mode', true ) ) === true;
	}

	public static function getClient() {
		return static::is_test_mode()
			? static::getTestClient()
			: static:: getLiveClient();
	}

	public static function getLiveClient() {
		if ( is_null( static::$liveClient ) ) {
			require_once locate_template( 'app/stripe/library/init.php' );

			\Stripe\Stripe::setApiKey( \Voxel\get( 'settings.stripe.secret', '' ) );
			static::$liveClient = new \Stripe\StripeClient( \Voxel\get( 'settings.stripe.secret', '' ) );
		}

		return static::$liveClient;
	}

	public static function getTestClient() {
		if ( is_null( static::$testClient ) ) {
			require_once locate_template( 'app/stripe/library/init.php' );

			\Stripe\Stripe::setApiKey( \Voxel\get( 'settings.stripe.test_secret', '' ) );
			static::$testClient = new \Stripe\StripeClient( \Voxel\get( 'settings.stripe.test_secret', '' ) );
		}

		return static::$testClient;
	}

	public static function base_dashboard_url( $path = '' ) {
		$url = 'https://dashboard.stripe.com/';
		$path = ltrim( $path, "/\\" );
		return $url.$path;
	}

	public static function dashboard_url( $path = '' ) {
		$url = static::base_dashboard_url();
		if ( static::is_test_mode() ) {
			$url .= 'test/';
		}

		$path = ltrim( $path, "/\\" );
		return $url.$path;
	}
}
