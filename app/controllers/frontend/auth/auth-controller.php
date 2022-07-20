<?php

namespace Voxel\Controllers\Frontend\Auth;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Auth_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_nopriv_auth.login', '@login' );
		$this->on( 'voxel_ajax_nopriv_auth.recover', '@recover' );
		$this->on( 'voxel_ajax_nopriv_auth.recover_confirm', '@recover_confirm' );
		$this->on( 'voxel_ajax_nopriv_auth.recover_set_password', '@recover_set_password' );
		$this->on( 'voxel_ajax_nopriv_auth.register', '@register' );
		$this->on( 'voxel_ajax_nopriv_auth.confirm_account', '@confirm_account' );
		$this->on( 'voxel_ajax_nopriv_auth.resend_confirmation_code', '@resend_confirmation_code' );
		$this->on( 'voxel_ajax_auth.logout', '@logout' );

		// logged-in only
		$this->on( 'voxel_ajax_auth.update_password', '@update_password' );
		$this->on( 'voxel_ajax_auth.update_email', '@update_email' );
	}

	protected function login() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_login' );
			}

			$credentials = [
				'user_login' => sanitize_text_field( $_POST['username'] ?? '' ),
				'user_password' => $_POST['password'] ?? '',
				'remember' => !! ( $_POST['remember'] ?? false ),
			];

			$wp_user = wp_authenticate( $credentials['user_login'], $credentials['user_password'] );
			if ( is_wp_error( $wp_user ) ) {
				throw new \Exception( wp_strip_all_tags( $wp_user->get_error_message() ) );
			}

			// show confirm_account step if not confirmed yet
			$user = \Voxel\User::get( $wp_user );
			if ( ! $user->is_confirmed() ) {
				$user->send_confirmation_code();

				return wp_send_json( [
					'success' => true,
					'confirmed' => false,
				] );
			}

			$wp_user = wp_signon( $credentials, is_ssl() );
			if ( is_wp_error( $wp_user ) ) {
				throw new \Exception( wp_strip_all_tags( $wp_user->get_error_message() ) );
			}

			// cleanup recovery session if it exists
			delete_user_meta( $wp_user->ID, 'voxel:recovery' );
			delete_user_meta( $wp_user->ID, 'voxel:email_update' );

			return wp_send_json( [
				'success' => true,
				'confirmed' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function recover() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_recover' );
			}

			$email = sanitize_text_field( $_POST['email'] ?? '' );

			$user = \Voxel\User::get( get_user_by( 'email', $email ) );
			if ( ! $user ) {
				throw new \Exception( _x( 'Account not found.', 'recover account', 'voxel' ) );
			}

			$user->send_recovery_code();

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function recover_confirm() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_recover_confirm' );
			}

			$email = sanitize_text_field( $_POST['email'] ?? '' );
			$code = sanitize_text_field( $_POST['code'] ?? '' );

			$user = \Voxel\User::get( get_user_by( 'email', $email ) );
			if ( ! $user ) {
				throw new \Exception( _x( 'Account not found.', 'recover account', 'voxel' ) );
			}

			$user->verify_recovery_code( $code );

			// give user 5 minutes to use code to set new password
			$recovery = json_decode( get_user_meta( $user->get_id(), 'voxel:recovery', true ), ARRAY_A );
			$recovery['expires'] = time() + ( 5 * MINUTE_IN_SECONDS );
			update_user_meta( $user->get_id(), 'voxel:recovery', wp_json_encode( $recovery ) );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function recover_set_password() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_recover_set_password' );
			}

			$email = sanitize_text_field( $_POST['email'] ?? '' );
			$code = sanitize_text_field( $_POST['code'] ?? '' );
			$password = (string) ( $_POST['password'] ?? '' );
			$confirm_password = $_POST['confirm_password'] ?? '';

			$user = \Voxel\User::get( get_user_by( 'email', $email ) );
			if ( ! $user ) {
				throw new \Exception( _x( 'Account not found.', 'recover account', 'voxel' ) );
			}

			$user->verify_recovery_code( $code );

			\Voxel\validate_password( $password );
			if ( ! is_string( $password ) || $password !== $confirm_password ) {
				throw new \Exception( _x( 'Passwords do not match.', 'recover account', 'voxel' ) );
			}

			// validation passed, update user pasword
			wp_set_password( $password, $user->get_id() );
			delete_user_meta( $user->get_id(), 'voxel:recovery' );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function register() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_register' );
			}

			$username = sanitize_user( wp_unslash( $_POST['username'] ?? '' ) );
			$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
			$password = (string) ( $_POST['password'] ?? '' );

			if ( ! \Voxel\get( 'settings.membership.enabled', true ) ) {
				throw new \Exception( _x( 'Invalid request.', 'register', 'voxel' ) );
			}

			// validate username
			if ( empty( $username ) ) {
				throw new \Exception( _x( 'Please enter a username.', 'register', 'voxel' ) );
			}

			if ( ! validate_username( $username ) ) {
				throw new \Exception( _x( 'Please enter a valid username.', 'register', 'voxel' ) );
			}

			if ( username_exists( $username ) ) {
				throw new \Exception( _x( 'This username is already registered. Please choose another one.', 'register', 'voxel' ) );
			}

			$illegal_user_logins = (array) apply_filters( 'illegal_user_logins', [] );
			if ( in_array( strtolower( $username ), array_map( 'strtolower', $illegal_user_logins ), true ) ) {
				throw new \Exception( _x( 'This username is not allowed.', 'register', 'voxel' ) );
			}

			// validate email
			if ( empty( $email ) ) {
				throw new \Exception( _x( 'Please enter your email address.', 'register', 'voxel' ) );
			}

			if ( ! is_email( $email ) ) {
				throw new \Exception( _x( 'Please enter a valid email address.', 'register', 'voxel' ) );
			}

			if ( email_exists( $email ) ) {
				throw new \Exception( _x( 'This email is already registered.', 'register', 'voxel' ) );
			}

			// validate password
			\Voxel\validate_password( $password );

			// create user
			$user_id = wp_insert_user( [
				'user_login' => wp_slash( $username ),
				'user_email' => wp_slash( $email ),
				'user_pass' => $password,
				'role' => apply_filters( 'voxel/default-role', 'subscriber' ),
			] );

			if ( is_wp_error( $user_id ) ) {
				throw new \Exception( $user_id->get_error_message() );
			}

			$user = \Voxel\User::get( $user_id );
			$user->send_confirmation_code();

			do_action( 'voxel/user-registered', $user_id );

			return wp_send_json( [
				'success' => true,
				'confirmed' => false,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function confirm_account() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_confirm_account' );
			}

			$code = sanitize_text_field( $_POST['code'] ?? '' );

			$credentials = [
				'user_login' => sanitize_text_field( $_POST['username'] ?? '' ),
				'user_password' => $_POST['password'] ?? '',
				'remember' => !! ( $_POST['remember'] ?? false ),
			];

			$wp_user = wp_authenticate( $credentials['user_login'], $credentials['user_password'] );
			if ( is_wp_error( $wp_user ) ) {
				throw new \Exception( wp_strip_all_tags( $wp_user->get_error_message() ) );
			}

			$user = \Voxel\User::get( $wp_user );
			$user->verify_confirmation_code( $code );
			delete_user_meta( $wp_user->ID, 'voxel:confirmation' );

			$wp_user = wp_signon( $credentials, is_ssl() );
			if ( is_wp_error( $wp_user ) ) {
				throw new \Exception( wp_strip_all_tags( $wp_user->get_error_message() ) );
			}

			// cleanup recovery session if it exists
			delete_user_meta( $wp_user->ID, 'voxel:recovery' );

			// redirect to plans or welcome page
			if ( \Voxel\get( 'settings.membership.plans_enabled' ) ) {
				$plans_page = get_permalink( \Voxel\get( 'templates.pricing' ) ) ?: home_url('/');
				$redirect_to = add_query_arg( 'redirect_to', '{REDIRECT_URL}', $plans_page );
			} else {
				if ( \Voxel\get( 'settings.membership.after_registration' ) === 'welcome_step' ) {
					$redirect_to = add_query_arg( [
						'welcome' => '',
						'redirect_to' => '{REDIRECT_URL}',
					], get_permalink( \Voxel\get( 'templates.auth' ) ) ?: home_url('/') );
				} else {
					$redirect_to = '{REDIRECT_URL}';
				}
			}

			return wp_send_json( [
				'success' => true,
				'redirect_to' => $redirect_to,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function resend_confirmation_code() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_resend_confirmation_code' );
			}

			$username = sanitize_text_field( $_POST['username'] ?? '' );
			$user = \Voxel\User::get( get_user_by( 'login', $username ) );
			if ( ! $user ) {
				throw new \Exception( 'Account not found.' );
			}

			if ( $user->is_confirmed() ) {
				throw new \Exception( 'Account has been confirmed already.' );
			}

			$user->send_confirmation_code();

			return wp_send_json( [
				'success' => true,
				'message' => sprintf( 'Confirmation code sent to %s', $user->get_email() ),
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function logout() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth_logout' );
			wp_logout();
		} catch ( \Exception $e ) {}

		wp_safe_redirect( get_permalink( \Voxel\get( 'templates.auth' ) ) ?: home_url('/') );
		exit;
	}

	protected function update_password() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_update_password' );
			}

			$user = \Voxel\current_user();
			if ( ! $user ) {
				throw new \Exception( _x( 'Something went wrong.', 'account security', 'voxel' ) );
			}

			$current_pw = (string) ( $_POST['current'] ?? '' );

			$wp_user = wp_authenticate( $user->get_username(), $current_pw );
			if ( is_wp_error( $wp_user ) ) {
				throw new \Exception( _x( 'Your current password is not correct.', 'account security', 'voxel' ) );
			}

			$new_pw = (string) ( $_POST['new'] ?? '' );
			$confirm_new_pw = $_POST['confirm_new'] ?? '';

			\Voxel\validate_password( $new_pw );
			if ( ! is_string( $new_pw ) || $new_pw !== $confirm_new_pw ) {
				throw new \Exception( _x( 'Passwords do not match.', 'account security', 'voxel' ) );
			}

			// validation passed, update user pasword
			wp_set_password( $new_pw, $user->get_id() );
			wp_signon( [
				'user_login' => $user->get_username(),
				'user_password' => $new_pw,
			], is_ssl() );

			return wp_send_json( [
				'success' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function update_email() {
		try {
			\Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_auth' );
			if ( \Voxel\get('settings.recaptcha.enabled') ) {
				\Voxel\verify_recaptcha( $_REQUEST['_recaptcha'] ?? '', 'vx_update_email' );
			}

			$user = \Voxel\current_user();
			if ( ! $user ) {
				throw new \Exception( _x( 'Something went wrong.', 'account security', 'voxel' ) );
			}

			$state = sanitize_text_field( $_POST['state'] ?? null );

			if ( $state === 'send_code' ) {
				$email = sanitize_email( wp_unslash( $_POST['new'] ?? '' ) );
				if ( empty( $email ) || ! is_email( $email ) ) {
					throw new \Exception( _x( 'Provided email address is not valid.', 'account security', 'voxel' ) );
				}

				$user->send_email_update_code( $email );

				return wp_send_json( [
					'success' => true,
					'state' => 'verify_code',
				] );
			} else {
				$code = sanitize_text_field( $_POST['code'] ?? null );
				$verified_email = $user->verify_email_update_code( $code );
				if ( empty( $verified_email ) || ! is_email( $verified_email ) ) {
					throw new \Exception( _x( 'Provided email address is not valid.', 'account security', 'voxel' ) );
				}

				delete_user_meta( $user->get_id(), 'voxel:email_update' );
				wp_update_user( [
					'ID' => $user->get_id(),
					'user_email' => $verified_email,
				] );

				return wp_send_json( [
					'success' => true,
					'state' => 'confirmed',
				] );
			}
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}
}
