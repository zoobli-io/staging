<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

require_once locate_template( 'app/utils/constants.php' );
require_once locate_template( 'app/utils/app-utils.php' );
require_once locate_template( 'app/utils/post-utils.php' );
require_once locate_template( 'app/utils/template-utils.php' );
require_once locate_template( 'app/utils/term-utils.php' );
require_once locate_template( 'app/utils/user-utils.php' );
require_once locate_template( 'app/utils/security-utils.php' );
require_once locate_template( 'app/utils/recurring-date-utils.php' );
require_once locate_template( 'app/utils/timeline-utils.php' );
require_once locate_template( 'app/utils/demo-import-utils.php' );
require_once locate_template( 'app/utils/dev-utils.php' );

function render( $string ) {
	return \Voxel\Dynamic_Tags\Dynamic_Tags::render( $string );
}

function classname_to_filename( $classname, $with_namespace = false ) {
	$parts = explode( '\\', $classname );
	return strtolower( str_replace( '_', '-', $with_namespace ? $classname : array_pop( $parts ) ) );
}

function filename_to_classname( $filename ) {
	return str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $filename ) ) );
}

function get_assets_version() {
	static $version;
	if ( ! is_null( $version ) ) {
		return $version;
	}

	$version = \Voxel\is_dev_mode() ? rand(1, 1e4) : wp_get_theme( get_template() )->get('Version');
	return $version;
}

function is_elementor_active() {
	return class_exists( '\Elementor\Plugin' );
}

function is_edit_mode() {
	return \Voxel\is_elementor_active() && \Elementor\Plugin::$instance->editor->is_edit_mode();
}

function is_preview_mode() {
	return \Voxel\is_elementor_active() && \Elementor\Plugin::$instance->preview->is_preview_mode();
}

function is_elementor_ajax() {
	return ! empty( $_REQUEST['_nonce'] ) && wp_verify_nonce( $_REQUEST['_nonce'], 'elementor_ajax' );
}

function is_elementor_preview() {
	return isset( $_GET['elementor-preview'] );
}

function is_rendering_css() {
	return !! ( $GLOBALS['vx_rendering_css'] ?? null );
}

function is_using_mariadb() {
	global $wpdb;
	$db_version = $wpdb->get_results( "SHOW VARIABLES WHERE `Variable_name` = 'version_comment'", OBJECT_K );
	if ( ! is_array( $db_version ) || empty( $db_version['version_comment']->Value ?? null ) ) {
		return false;
	}

	return str_contains( strtolower( $db_version['version_comment']->Value ), 'mariadb' );
}

function set_rendering_css( bool $is_rendering ) {
	$GLOBALS['vx_rendering_css'] = $is_rendering;
}

function get_image( $image ) {
	return trailingslashit( get_template_directory_uri() ).'assets/images/'.$image;
}

/**
 * Helper; Return "uploads/" full directory path.
 *
 * @since 1.0
 */
function uploads_dir( $path = '' ) {
	return trailingslashit( wp_upload_dir()['basedir'] ).$path;
}

/**
 * Delete given directory.
 *
 * @since 2.2.3
 */
function delete_directory( $target ) {
	if ( is_dir( $target ) ) {
		$files = glob( $target . '*', GLOB_MARK );
		foreach( $files as $file ) {
			delete_directory( $file );
		}

		@rmdir( $target );
	} elseif ( is_file( $target ) ) {
		@unlink( $target );
	}
}

function parse_icon_string( $string ) {
	$library = substr( $string, 0, strpos( $string, ':') );
	$icon = substr( $string, strpos( $string, ':') + 1 );

	if ( $library === 'svg' && is_numeric( $icon ) ) {
		$icon = [
			'id' => absint( $icon ),
			'url' => wp_get_attachment_url( $icon ),
		];
	}

	return [
		'value' => $icon,
		'library' => $library,
	];
}

function get_icon_markup( $icon ) {
	if ( ! \Voxel\is_elementor_active() ) {
		return '';
	}

	if ( ! is_array( $icon ) ) {
		$icon = \Voxel\parse_icon_string( $icon );
	}

	\Elementor\Plugin::$instance->frontend->enqueue_font( $icon['library'] );

	ob_start();
	\Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
	return ob_get_clean();
}

function render_icon( $icon ) {
	echo \Voxel\get_icon_markup( $icon );
}

function get_weekdays() {
	return [
		'mon' => _x( 'Monday', 'weekdays', 'voxel' ),
		'tue' => _x( 'Tuesday', 'weekdays', 'voxel' ),
		'wed' => _x( 'Wednesday', 'weekdays', 'voxel' ),
		'thu' => _x( 'Thursday', 'weekdays', 'voxel' ),
		'fri' => _x( 'Friday', 'weekdays', 'voxel' ),
		'sat' => _x( 'Saturday', 'weekdays', 'voxel' ),
		'sun' => _x( 'Sunday', 'weekdays', 'voxel' ),
	];
}

function get_weekday_indexes() {
	return [
		'mon' => 0,
		'tue' => 1,
		'wed' => 2,
		'thu' => 3,
		'fri' => 4,
		'sat' => 5,
		'sun' => 6,
	];
}

/**
 * Return all registered image sizes.
 *
 * @since 1.0
 */
function get_image_sizes() {
	global $_wp_additional_image_sizes;
	$sizes = [];

	foreach ( [ 'thumbnail', 'medium', 'medium_large', 'large' ] as $size ) {
		$sizes[ $size ] = [
			'width'  => intval( get_option( "{$size}_size_w" ) ),
			'height' => intval( get_option( "{$size}_size_h" ) ),
			'crop'   => get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false,
		];
	}

	if ( ! empty( $_wp_additional_image_sizes ) ) {
		$sizes = array_merge( $sizes, $_wp_additional_image_sizes );
	}

	return $sizes;
}

function get_image_sizes_with_labels() {
	$sizes = [];
	foreach ( \Voxel\get_image_sizes() as $key => $size ) {
		$label = ucwords( str_replace( '_', ' ', $key ) );
		$sizes[ $key ] = sprintf( '%s (%sx%s)', $label, $size['width'], $size['height'] ?: '(auto)' );
	}

	$sizes['full'] = 'Full size';

	return $sizes;
}

/**
 * Check whether the current request is nearing on using maximum
 * execution time and memory.
 *
 * @since 1.0
 */
function nearing_resource_limits(): bool {
	// check if less than 5 seconds of execution time are left
	$max_execution_time = absint( ini_get('max_execution_time') );
	$time_limit = $max_execution_time === 0 ? 60 : min( 60, ( $max_execution_time - 5 ) );
	$time_nearing_limit = ( $time_limit - ( microtime(true) - WP_START_TIMESTAMP ) ) < 0;

	// check if more than 85% of memory has been used (75% if QueryMonitor is active)
	$max_memory_usage = class_exists( '\QueryMonitor' ) ? 0.75 : 0.85;
	$memory_limit = absint( wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) * $max_memory_usage );
	$memory_nearing_limit = ( $memory_limit - memory_get_usage() ) < 0;

	return $time_nearing_limit || $memory_nearing_limit;
}

function utc() {
	static $datetime;
	if ( is_null( $datetime ) ) {
		$datetime = new \DateTimeImmutable( 'now', new \DateTimeZone( 'UTC' ) );
	}

	return $datetime;
}

function epoch() {
	static $datetime;
	if ( is_null( $datetime ) ) {
		$datetime = new \DateTimeImmutable( '1970-01-01', new \DateTimeZone( 'UTC' ) );
	}

	return $datetime;
}

function currency_format( $price, $currency, $amount_is_in_cents = true ) {
	static $formatter;
	if ( is_null( $formatter ) ) {
		$formatter = new \NumberFormatter( get_locale(), \NumberFormatter::CURRENCY );
	}

	// convert amount from cents to main currency, unless it's a zero decimal currency
	if ( $amount_is_in_cents && ! \Voxel\Stripe\Currencies::is_zero_decimal( $currency ) ) {
		$price /= 100;
	}

	if ( intval( $price ) == $price ) {
	  $formatter->setAttribute( \NumberFormatter::MIN_FRACTION_DIGITS, 0 );
	}

	return $formatter->formatCurrency( $price, $currency );
}

function interval_format( $interval, $interval_count ) {
	$count = absint( $interval_count );

	if ( $interval === 'month' && $count === 12 ) {
		$interval = 'year';
		$count = 1;
	}

	if ( $interval === 'day' ) {
		return $count === 1
			? _x( 'daily', 'price interval', 'voxel' )
			: sprintf( _x( 'every %s days', 'price interval', 'voxel' ), number_format_i18n( $count ) );
	} elseif ( $interval === 'week' ) {
		return $count === 1
			? _x( 'weekly', 'price interval', 'voxel' )
			: sprintf( _x( 'every %s weeks', 'price interval', 'voxel' ), number_format_i18n( $count ) );
	} elseif ( $interval === 'month' ) {
		return $count === 1
			? _x( 'monthly', 'price interval', 'voxel' )
			: sprintf( _x( 'every %s months', 'price interval', 'voxel' ), number_format_i18n( $count ) );
	} elseif ( $interval === 'year' ) {
		return $count === 1
			? _x( 'yearly', 'price interval', 'voxel' )
			: sprintf( _x( 'every %s years', 'price interval', 'voxel' ), number_format_i18n( $count ) );
	}
}

function random_string( int $length ) {
	$pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$max = strlen( $pool ) - 1;

	$token = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$random_key = random_int( 0, $max );
		$token .= $pool[ $random_key ];
	}

	return $token;
}

function get_google_auth_link() {
	return add_query_arg( [
		'response_type' => 'code',
		'client_id' => \Voxel\get( 'settings.auth.google.client_id' ),
		'redirect_uri' => rawurlencode( home_url('/?vx=1&action=auth.google.login') ),
		'scope' => 'openid email',
		'state' => sprintf( '%s..%s', wp_create_nonce( 'vx_auth_google' ), rawurlencode( \Voxel\get_redirect_url() ) ),
	], 'https://accounts.google.com/o/oauth2/v2/auth' );
}

function get_redirect_url() {
	if ( ! empty( $_REQUEST['redirect_to'] ) ) {
		return wp_validate_redirect( $_REQUEST['redirect_to'], home_url('/') );
	} elseif ( $referrer = wp_get_referer() ) {
		return $referrer;
	} else {
		return home_url('/');
	}
}

function get_auth_url() {
	return get_permalink( \Voxel\get( 'templates.auth' ) ) ?: home_url('/');
}

function get_logout_url() {
	return add_query_arg( [
		'vx' => 1,
		'action' => 'auth.logout',
		'_wpnonce' => wp_create_nonce( 'vx_auth_logout' ),
	], home_url( '/' ) );
}

function date_format( $timestamp ) {
	return date_i18n( get_option( 'date_format' ), $timestamp );
}

function time_format( $timestamp ) {
	return date_i18n( get_option( 'time_format' ), $timestamp );
}

function datetime_format( $timestamp ) {
	return date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), $timestamp );
}

function get_minute_of_week( $timestamp ) {
	$day_index = absint( date( 'w', $timestamp ) );
	$day_start = ( $day_index * 1440 ) + 1440;
	return $day_start + ( absint( date( 'H', $timestamp ) ) * 60 ) + absint( date( 'i', $timestamp ) );
}

/**
 * Merge overlapping integer ranges.
 *
 * @param Array<Array{0: int, 1: int}> $ranges
 */
function merge_ranges( array $ranges ): array {
	usort( $ranges, function( $a, $b ) {
		return $a[0] - $b[0];
	} );

	$n = 0;
	$len = count( $ranges );
	for ( $i = 1; $i < $len; ++$i ) {
		if ( $ranges[$i][0] > $ranges[$n][1] + 1 ) {
			$n = $i;
		} else {
			if ( $ranges[$n][1] < $ranges[$i][1] ) {
				$ranges[$n][1] = $ranges[$i][1];
			}

			unset( $ranges[$i] );
		}
	}

	return array_values($ranges);
}

function clamp( $number, $min, $max ) {
	return max( $min, min( $max, $number ) );
}

function from_list( $value, array $list, $default = null ) {
	return in_array( $value, $list, true ) ? $value : $default;
}

function evaluate_visibility_rules( $rules ): bool {
	$rule_list = \Voxel\config('dynamic_tags.visibility_rules');
	foreach ( $rules as $rule_group ) {
		$has_valid_rules = false;

		// all rules in a group must be true for the rule group to pass
		foreach ( $rule_group as $rule_config ) {
			if ( ! isset( $rule_list[ $rule_config['type'] ?? null ] ) ) {
				continue;
			}

			$has_valid_rules = true;
			$rule = new $rule_list[ $rule_config['type'] ]( $rule_config );
			if ( $rule->evaluate() === false ) {
				continue(2);
			}
		}

		// make sure group contains at least one valid rule
		if ( ! $has_valid_rules ) {
			continue;
		}

		// if a single rule group has passed conditions, no more evaluation is necessary
		return true;
	}

	return false;
}
