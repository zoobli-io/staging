<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Templates_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'admin_menu', '@add_menu_page' );
		$this->filter( 'display_post_states', '@display_template_labels', 100, 2 );
	}

	protected function add_menu_page() {
		add_menu_page(
			__( 'Templates', 'voxel' ),
			__( 'Templates', 'voxel' ),
			'manage_options',
			'voxel-templates',
			function() {
				$this->create_missing_templates();
				require locate_template( 'templates/backend/templates.php' );
			},
			\Voxel\get_image('post-types/ic_tmpl.png'),
			'0.278'
		);
	}

	protected function create_missing_templates() {
		$templates = \Voxel\get( 'templates' );

		// header
		if ( ! \Voxel\template_exists( $templates['header'] ?? '' ) ) {
			$template_id = \Voxel\create_template( 'site template: header' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['header'] = $template_id;
			}
		}

		// footer
		if ( ! \Voxel\template_exists( $templates['footer'] ?? '' ) ) {
			$template_id = \Voxel\create_template( 'site template: footer' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['footer'] = $template_id;
			}
		}

		// orders
		if ( ! \Voxel\page_exists( $templates['orders'] ?? '' ) ) {
			$template_id = \Voxel\create_page( _x( 'Orders', 'orders page title', 'voxel' ) );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['orders'] = $template_id;
			}
		}

		// stripe connect account
		if ( ! \Voxel\page_exists( $templates['stripe_account'] ?? '' ) ) {
			$template_id = \Voxel\create_page( _x( 'Become a Seller', 'stripe account page title', 'voxel' ) );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['stripe_account'] = $template_id;
			}
		}

		// login and register
		if ( ! \Voxel\page_exists( $templates['auth'] ?? '' ) ) {
			$template_id = \Voxel\create_page( _x( 'Login / Register', 'login/register page title', 'voxel' ), 'auth' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['auth'] = $template_id;
			}
		}

		// pricing plans
		if ( ! \Voxel\page_exists( $templates['pricing'] ?? '' ) ) {
			$template_id = \Voxel\create_page( _x( 'Pricing', 'pricing page title', 'voxel' ), 'pricing' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['pricing'] = $template_id;
			}
		}

		// current plan
		if ( ! \Voxel\page_exists( $templates['current_plan'] ?? '' ) ) {
			$template_id = \Voxel\create_page( _x( 'Current plan', 'current plan page title', 'voxel' ), 'current-plan' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['current_plan'] = $template_id;
			}
		}

		// timeline
		if ( ! \Voxel\page_exists( $templates['timeline'] ?? '' ) ) {
			$template_id = \Voxel\create_page( _x( 'Timeline', 'timeline page title', 'voxel' ), 'timeline' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['timeline'] = $template_id;
			}
		}

		// privacy policy
		$templates['privacy_policy'] ??= (int) get_option( 'wp_page_for_privacy_policy' );
		if ( ! \Voxel\page_exists( $templates['privacy_policy'] ) ) {
			$template_id = \Voxel\create_page( _x( 'Privacy Policy', 'privacy policy page title', 'voxel' ), 'privacy-policy' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['privacy_policy'] = $template_id;
			}
		}

		// terms and conditions
		if ( ! \Voxel\page_exists( $templates['terms'] ?? '' ) ) {
			$template_id = \Voxel\create_page( _x( 'Terms & Conditions', 'terms and conditions page title', 'voxel' ), 'terms' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['terms'] = $template_id;
			}
		}

		// 404
		if ( ! \Voxel\template_exists( $templates['404'] ?? '' ) ) {
			$template_id = \Voxel\create_template( 'site template: 404 Page Not Found' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['404'] = $template_id;
			}
		}

		// restricted
		if ( ! \Voxel\template_exists( $templates['restricted'] ?? '' ) ) {
			$template_id = \Voxel\create_template( 'site template: Restricted Content' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['restricted'] = $template_id;
			}
		}

		// popups style kit
		if ( ! \Voxel\template_exists( $templates['kit_popups'] ?? '' ) ) {
			$template_id = \Voxel\create_template( 'style kit: popups' );
			if ( ! is_wp_error( $template_id ) ) {
				$templates['kit_popups'] = $template_id;
			}
		}

		// cleanup
		$allowed_templates = [
			'header',
			'footer',
			'orders',
			'auth',
			'pricing',
			'current_plan',
			'privacy_policy',
			'terms',
			'stripe_account',
			'timeline',
			'404',
			'restricted',
			'kit_popups',
		];

		foreach ( $templates as $template => $id ) {
			if ( ! in_array( $template, $allowed_templates ) ) {
				unset( $templates[ $template ] );
			}
		}

		// save
		\Voxel\set( 'templates', $templates );
	}

	protected function display_template_labels( $states, $post ) {
		if ( $post->post_type !== 'page' ) {
			return $states;
		}

		$labels = [
			'auth' => _x( 'Auth Page', 'templates', 'voxel' ),
			'pricing' => _x( 'Pricing Plans Page', 'templates', 'voxel' ),
			'current_plan' => _x( 'Current Plan Page', 'templates', 'voxel' ),
			'orders' => _x( 'Orders Page', 'templates', 'voxel' ),
			'terms' => _x( 'Terms & Conditions', 'templates', 'voxel' ),
			'stripe_account' => _x( 'Seller Dashboard', 'templates', 'voxel' ),
		];

		$templates = \Voxel\get( 'templates', [] );
		$template = array_search( absint( $post->ID ), $templates, true );
		if ( $template && isset( $labels[ $template ] ) ) {
			$states[ 'vx:'.$template ] = $labels[ $template ];
		}

		return $states;
	}
}
