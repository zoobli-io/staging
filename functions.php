<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

function is_debug_mode() {
	return defined('WP_DEBUG') && WP_DEBUG;
}

function is_dev_mode() {
	return defined('VOXEL_DEV_MODE') && VOXEL_DEV_MODE;
}

function is_running_tests() {
	return defined('VOXEL_RUNNING_TESTS') && VOXEL_RUNNING_TESTS;
}

spl_autoload_register( function( $classname ) {
	$parts = explode( '\\', $classname );
	if ( $parts[0] !== 'Voxel' ) {
		return;
	}

	$parts[0] = 'App';
	$path_parts = array_map( function( $part ) {
		return strtolower( str_replace( '_', '-', $part ) );
	}, $parts );

	$path = join( DIRECTORY_SEPARATOR, $path_parts ) . '.php';
	if ( locate_template( $path ) ) {
		require_once locate_template( $path );
	}
} );

require_once locate_template('app/utils/utils.php');

foreach ( \Voxel\config('controllers') as $controller ) {
	new $controller;
}


// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );
