<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Settings_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'admin_menu', '@add_menu_page' );
		$this->on( 'admin_post_voxel_save_general_settings', '@save_settings' );
	}

	protected function add_menu_page() {
		add_menu_page(
			__( 'Settings', 'voxel' ),
			__( 'Settings', 'voxel' ),
			'manage_options',
			'voxel-settings',
			function() {
				$config = array_replace_recursive( [
					'recaptcha' => [
						'enabled' => false,
						'key' => null,
						'secret' => null,
					],
					'stripe' => [
						'test_mode' => true,
						'key' => null,
						'secret' => null,
						'test_key' => null,
						'test_secret' => null,
						'webhook_secret' => null,

						'configuration_id' => null,
						'portal' => [
							'invoice_history' => true,
							'customer_update' => [
								'enabled' => true,
								'allowed_updates' => [ 'email', 'address', 'phone' ],
							],
						],

						'currency' => 'USD',
					],
					'membership' => [
						'enabled' => true,
						'after_registration' => 'welcome_step', // welcome_step|redirect_back
						'plans_enabled' => true,
						'trial' => [
							'enabled' => false,
							'period_days' => 0,
						],
						'update' => [
							'proration_behavior' => 'always_invoice', // create_prorations|none|always_invoice
						],
						'cancel' => [
							'behavior' => 'at_period_end', // at_period_end|immediately
						],
					],
					'auth' => [
						'google' => [
							'enabled' => false,
							'client_id' => null,
							'client_secret' => null,
						],
					],
					'maps' => [
						'google_maps' => [
							'api_key' => null,
						],
					],
					'timeline' => [
						'posts' => [
							'editable' => true,
							'maxlength' => 5000,
							'images' => [
								'enabled' => true,
								'max_count' => 3,
								'max_size' => 2000,
							],
							'rate_limit' => [
								'time_between' => 20,
								'hourly_limit' => 20,
								'daily_limit' => 100,
							],
						],
						'replies' => [
							'max_nest_level' => null,
							'editable' => true,
							'maxlength' => 2000,
							'rate_limit' => [
								'time_between' => 5,
								'hourly_limit' => 100,
								'daily_limit' => 1000,
							],
						],
					],
				], \Voxel\get( 'settings', [] ) );

				$config['tab'] = $_GET['tab'] ?? 'stripe';

				require locate_template( 'templates/backend/general-settings.php' );
			},
			\Voxel\get_image('post-types/ic_pay.png'),
			'0.237'
		);
	}

	protected function save_settings() {
		check_admin_referer( 'voxel_save_general_settings' );
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		if ( empty( $_POST['config'] ) ) {
			die;
		}

		$config = json_decode( stripslashes( $_POST['config'] ), true );
		$original_values = \Voxel\get( 'settings', [] );

		$recaptcha = $config['recaptcha'] ?? [];
		$stripe = $config['stripe'] ?? [];
		$portal = $stripe['portal'] ?? [];
		$auth = $config['auth'] ?? [];
		$google = $auth['google'] ?? [];
		$membership = $config['membership'] ?? [];
		$maps = $config['maps'] ?? [];
		$timeline = $config['timeline'] ?? [];

		// sort allowed_updates so checking for changed settings works properly
		$allowed_customer_updates = (array) ( $portal['customer_update']['allowed_updates'] ?? [] );
		sort( $allowed_customer_updates );

		\Voxel\set( 'settings', [
			'recaptcha' => [
				'enabled' => !! $recaptcha['enabled'],
				'key' => sanitize_text_field( $recaptcha['key'] ?? null ),
				'secret' => sanitize_text_field( $recaptcha['secret'] ?? null ),
			],
			'stripe' => [
				'test_mode' => !! $stripe['test_mode'],
				'key' => sanitize_text_field( $stripe['key'] ?? null ),
				'secret' => sanitize_text_field( $stripe['secret'] ?? null ),
				'test_key' => sanitize_text_field( $stripe['test_key'] ?? null ),
				'test_secret' => sanitize_text_field( $stripe['test_secret'] ?? null ),
				'webhook_secret' => sanitize_text_field( $stripe['webhook_secret'] ?? null ),

				'configuration_id' => $original_values['stripe']['configuration_id'] ?? null,
				'portal' => [
					'invoice_history' => $portal['invoice_history'] ?? true,
					'customer_update' => [
						'enabled' => $portal['customer_update']['enabled'] ?? true,
						'allowed_updates' => $allowed_customer_updates,
					],
				],

				'currency' => sanitize_text_field( $stripe['currency'] ?? 'USD' ),
			],

			'membership' => [
				'enabled' => $membership['enabled'] ?? true,
				'after_registration' => \Voxel\from_list( $membership['after_registration'], [ 'welcome_step', 'redirect_back' ], 'welcome_step' ),
				'plans_enabled' => $membership['plans_enabled'] ?? true,
				'trial' => [
					'enabled' => $membership['trial']['enabled'] ?? false,
					'period_days' => $membership['trial']['period_days'] ?? 0,
				],
				'update' => [
					'proration_behavior' => $membership['update']['proration_behavior'] ?? 'always_invoice',
				],
				'cancel' => [
					'behavior' => $membership['cancel']['behavior'] ?? 'at_period_end',
				],
			],

			'auth' => [
				'google' => [
					'enabled' => !! $google['enabled'],
					'client_id' => sanitize_text_field( $google['client_id'] ?? null ),
					'client_secret' => sanitize_text_field( $google['client_secret'] ?? null ),
				],
			],

			'maps' => [
				'google_maps' => [
					'api_key' => $maps['google_maps']['api_key'] ?? null,
				],
			],

			'timeline' => [
				'posts' => [
					'editable' => $timeline['posts']['editable'] ?? true,
					'maxlength' => $timeline['posts']['maxlength'] ?? 5000,
					'images' => [
						'enabled' => $timeline['posts']['images']['enabled'] ?? true,
						'max_count' => $timeline['posts']['images']['max_count'] ?? 3,
						'max_size' => $timeline['posts']['images']['max_size'] ?? 2000,
					],
					'rate_limit' => [
						'time_between' => $timeline['posts']['rate_limit']['time_between'] ?? 20,
						'hourly_limit' => $timeline['posts']['rate_limit']['hourly_limit'] ?? 20,
						'daily_limit' => $timeline['posts']['rate_limit']['daily_limit'] ?? 100,
					],
				],
				'replies' => [
					'editable' => $timeline['replies']['editable'] ?? true,
					'max_nest_level' => $timeline['replies']['max_nest_level'] ?? null,
					'maxlength' => $timeline['replies']['maxlength'] ?? 2000,
					'rate_limit' => [
						'time_between' => $timeline['replies']['rate_limit']['time_between'] ?? 5,
						'hourly_limit' => $timeline['replies']['rate_limit']['hourly_limit'] ?? 100,
						'daily_limit' => $timeline['replies']['rate_limit']['daily_limit'] ?? 1000,
					],
				],
			],
		] );

		// if customer portal settings have changed, update configuration (or create new if it doesn't exist)
		if ( empty( \Voxel\get( 'settings.stripe.configuration_id' ) ) ) {
			$this->create_customer_portal();
		} elseif ( ( $original_values['stripe']['portal'] ?? [] ) !== \Voxel\get( 'settings.stripe.portal', [] ) ) {
			$this->update_customer_portal();
		}

		wp_safe_redirect( add_query_arg( 'tab', $config['tab'] ?? null, admin_url( 'admin.php?page=voxel-settings' ) ) );
		die;
	}

	protected function create_customer_portal() {
		try {
			$stripe = \Voxel\Stripe::getClient();
			$configuration = $stripe->billingPortal->configurations->create( $this->_get_portal_config() );
			\Voxel\set( 'settings.stripe.configuration_id', $configuration->id );
		} catch ( \Exception $e ) {
			\Voxel\log( $e );
		}
	}

	protected function update_customer_portal() {
		try {
			$stripe = \Voxel\Stripe::getClient();
			$configuration_id = \Voxel\get( 'settings.stripe.configuration_id' );
			$stripe->billingPortal->configurations->update( $configuration_id, $this->_get_portal_config() );
		} catch ( \Exception $e ) {
			\Voxel\log( $e );
		}
	}

	protected function _get_portal_config() {
		$portal = \Voxel\get( 'settings.stripe.portal', [] );
		return [
			'business_profile' => [
				'headline' => get_bloginfo( 'name' ),
				'privacy_policy_url' => get_permalink( \Voxel\get( 'templates.privacy_policy' ) ) ?: home_url('/'),
				'terms_of_service_url' => get_permalink( \Voxel\get( 'templates.terms' ) ) ?: home_url('/'),
			],
			'features' => [
				'payment_method_update' => [ 'enabled' => true ],
				'customer_update' => [
					'allowed_updates' => $portal['customer_update']['allowed_updates'] ?? [ 'email', 'address', 'phone' ],
					'enabled' => $portal['customer_update']['enabled'] ?? true,
				],
				'invoice_history' => [ 'enabled' => $portal['invoice_history'] ?? true ],
			],
		];
	}
}
