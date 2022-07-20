<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Assets_Controller extends Base_Controller {

	/**
	 * List of script handles to defer.
	 *
	 * @since 1.0
	 */
	protected $deferred_scripts = [
		'vue' => true,
		'sortable' => true,
		'vue-draggable' => true,
		'google-recaptcha' => true,
		'google-maps' => true,
		'pikaday' => true,
		'nouislider' => true,
		'vx:commons.js' => true,
		'vx:search-form.js' => true,
		'vx:create-post.js' => true,
		'vx:timeline.js' => true,
		'vx:google-maps.js' => true,
		'vx:auth.js' => true,
		'vx:orders.js' => true,
		'vx:product-form.js' => true,
	];

	/**
	 * Defer non-critical CSS.
	 *
	 * @link https://web.dev/defer-non-critical-css/
	 * @since 1.0
	 */
	protected $deferred_styles = [
		'wp-block-library' => true,
		'vx:popup-kit.css' => true,
	];

	protected function hooks() {
		$this->on( 'wp_enqueue_scripts', '@register_scripts' );
		$this->on( 'admin_enqueue_scripts', '@register_scripts' );
		$this->on( 'elementor/editor/before_enqueue_scripts', '@register_scripts' );
		$this->on( 'voxel/before_render_search_results', '@register_scripts' );
		$this->on( 'admin_footer', '@enable_dtags_in_backend' );

		$this->on( 'admin_enqueue_scripts', '@enqueue_backend_scripts' );
		$this->on( 'elementor/editor/after_enqueue_scripts', '@enqueue_elementor_scripts' );
		$this->on( 'elementor/frontend/before_render', '@enqueue_common_scripts_in_preview' );
		$this->on( 'wp_enqueue_scripts', '@enqueue_frontend_scripts' );
		$this->on( 'wp_footer', '@enqueue_frontend_low_priority_scripts' );

		$this->on( 'wp_default_scripts', '@remove_jquery_migrate' );

		$this->filter( 'script_loader_tag', '@defer_scripts', 10, 2 );
		$this->filter( 'style_loader_tag', '@defer_styles', 10, 4 );

		$this->on( 'wp_head', '@print_head_content' );
		$this->on( 'admin_head', '@print_head_content' );
		$this->on( 'customize_controls_enqueue_scripts', '@print_head_content' );
		$this->on( 'elementor/editor/before_enqueue_scripts', '@print_head_content' );

		$this->on( 'wp_footer', '@print_alert_template' );
		$this->on( 'admin_footer', '@print_alert_template' );

		if ( apply_filters( 'voxel/disable-wp-emoji', true ) !== false ) {
			$this->on( 'init', '@disable_wp_emoji' );
		}

		add_action( 'get_header', function() {
			remove_action( 'wp_head', '_admin_bar_bump_cb' );
		} );
	}

	protected function register_scripts() {
		$assets = trailingslashit( get_template_directory_uri() ).'assets/';
		$dist = trailingslashit( $assets ).'dist/';
		$vendor = trailingslashit( $assets ).'vendor/';
		$version = \Voxel\get_assets_version();

		// styles
		foreach ( \Voxel\config('assets.styles') as $style ) {
			wp_register_style( sprintf( 'vx:%s', $style ), $dist.$style, [], $version );
		}

		// scripts
		foreach ( \Voxel\config('assets.scripts') as $script ) {
			if ( is_array( $script ) ) {
				wp_register_script( sprintf( 'vx:%s', $script['src'] ), $dist.$script['src'], $script['deps'] ?? [], $version, true );
			} else {
				wp_register_script( sprintf( 'vx:%s', $script ), $dist.$script, [], $version, true );
			}
		}

		// vendor styles
		$suffix = \Voxel\is_dev_mode() ? '' : '.prod';
		wp_register_style( 'nouislider', $vendor.'nouislider/nouislider'.$suffix.'.css', [], '14.6.3' );
		wp_register_style( 'pikaday', $vendor.'pikaday/pikaday'.$suffix.'.css', [], '1.8.2' );

		// vendor scripts
		wp_register_script( 'vue', $vendor.'vue/vue'.$suffix.'.js', [], '3.2.37', true );
		wp_register_script( 'sortable', $vendor.'sortable/sortable'.$suffix.'.js', [], '1.10.2', true );
		wp_register_script( 'vue-draggable', $vendor.'vue-draggable/vue-draggable'.$suffix.'.js', [], '4.0.1', true );
		wp_register_script( 'nouislider', $vendor.'nouislider/nouislider'.$suffix.'.js', ['jquery'], '14.6.3', true );
		wp_register_script( 'pikaday', $vendor.'pikaday/pikaday'.$suffix.'.js', ['jquery'], '1.8.2', true );
		wp_register_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js?render='.\Voxel\get('settings.recaptcha.key'), [], false, true );
		wp_register_script( 'google-maps', sprintf( 'https://maps.googleapis.com/maps/api/js?%s', http_build_query( [
			'key' => \Voxel\get( 'settings.maps.google_maps.api_key' ),
			'libraries' => 'places',
			'v' => 3,
			'callback' => 'Voxel.Maps.GoogleMaps',
		] ) ), ['vx:commons.js'], false, true );
	}

	protected function enqueue_elementor_scripts() {
		// scripts
		wp_enqueue_script( 'vue' );
		wp_enqueue_script( 'sortable' );
		wp_enqueue_script( 'vue-draggable' );
		wp_enqueue_script( 'vx:backend.js' );
		wp_enqueue_script( 'vx:elementor.js' );
		wp_enqueue_script( 'vx:commons.js' );

		printf(
			'<script type="text/javascript">var Voxel_Elementor_Config = %s;</script>',
			wp_json_encode( (object) apply_filters( 'voxel/js/elementor-editor-config', [
				'header_id' => \Voxel\get( 'templates.header' ),
				'footer_id' => \Voxel\get( 'templates.footer' ),
			] ) )
		);

		// styles
		wp_enqueue_style( 'vx:backend.css' );
		wp_enqueue_style( 'vx:elementor.css' );
		$this->enqueue_elementor_dark_mode();

		require locate_template( 'templates/dynamic-tags/dynamic-tags.php' );
	}

	protected function enable_dtags_in_backend() {
		wp_enqueue_style( 'vx:elementor.css' );
		require locate_template( 'templates/dynamic-tags/dynamic-tags.php' );
	}

	protected function enqueue_common_scripts_in_preview() {
		if ( ! \Voxel\is_preview_mode() ) {
			return;
		}

		wp_enqueue_script( 'vue' );
		wp_enqueue_script( 'vx:commons.js' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'pikaday' );
		wp_enqueue_script( 'nouislider' );
		wp_enqueue_style( 'pikaday' );
		wp_enqueue_style( 'nouislider' );
		wp_enqueue_style( 'vx:commons.css' );

		if ( ! class_exists( '_WP_Editors', false ) ) {
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		}
		wp_deregister_style( 'editor-buttons' );
		\_WP_Editors::enqueue_default_editor();
	}

	private function enqueue_elementor_dark_mode() {
		$assets = trailingslashit( get_template_directory_uri() ).'assets/';
		$dist = trailingslashit( $assets ).'dist/';
		$ui_theme = \Elementor\Core\Settings\Manager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );
		if ( 'light' !== $ui_theme ) {
			$ui_theme_media_queries = 'all';

			if ( 'auto' === $ui_theme ) {
				$ui_theme_media_queries = '(prefers-color-scheme: dark)';
			}

			wp_enqueue_style(
				'voxel-elementor-dark-mode',
				$dist.'elementor-dark-mode.css',
				[],
				\Voxel\get_assets_version(),
				$ui_theme_media_queries
			);
		}
	}

	protected function enqueue_backend_scripts() {
		wp_enqueue_script( 'vue' );
		wp_enqueue_script( 'vx:backend.js' );
		wp_enqueue_style( 'vx:backend.css' );
		wp_enqueue_media(); // @todo: only load when necessary
	}

	protected function enqueue_frontend_scripts() {
		wp_enqueue_script( 'jquery-core', '', [], false, true );
		wp_enqueue_script( 'vue' );
		wp_enqueue_script( 'vx:commons.js' );
		wp_enqueue_style( 'vx:commons.css' );
	}

	protected function enqueue_frontend_low_priority_scripts() {
		wp_enqueue_style( 'vx:popup-kit.css' );
		if ( $popup_kit = \Voxel\get( 'templates.kit_popups', null ) ) {
			$this->deferred_styles[ 'elementor-post-'.$popup_kit ] = true;
			\Voxel\enqueue_template_css( $popup_kit );
		}
	}

	protected function print_head_content() {
		$config = [
			'ajax_url' => add_query_arg( 'vx', 1, home_url( '/' ) ),
			'is_logged_in' => (bool) is_user_logged_in(),
			'login_url' => \Voxel\get_auth_url(),
			'register_url' => add_query_arg( 'register', '', \Voxel\get_auth_url() ),
			'l10n' => [
				'ajaxError' => _x( 'There was a problem. Please try again.', 'error on ajax action', 'voxel' ),
			],
		];

		printf( '<script type="text/javascript">var Voxel_Config = %s;</script>', wp_json_encode( (object) $config ) );
	}

	protected function disable_wp_emoji() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', function( $plugins ) {
			return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
		} );
		add_filter( 'wp_resource_hints', function( $urls, $relation_type ) {
			if ( $relation_type === 'dns-prefetch' ) {
				$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/12.0.0-1/svg/' );
				$urls = array_diff( $urls, [ $emoji_svg_url ] );
			}
			return $urls;
		}, 10, 2 );
	}

	protected function remove_jquery_migrate( $scripts ) {
		$scripts->registered['jquery']->deps = array_diff(
			$scripts->registered['jquery']->deps,
			[ 'jquery-migrate' ]
		);
	}

	protected function print_alert_template() {
		echo <<<HTML
			<div id="vx-alert"></div>
			<script type="text/html" id="vx-alert-tpl">
				<div class="ts-notice ts-notice-{type} flexify">
					<i class="las la-info-circle"></i>
					<p>{message}</p>
					<a href="#">Close</a>
				</div>
			</script>
		HTML;
	}

	protected function defer_scripts( $tag, $handle ) {
		if ( isset( $this->deferred_scripts[ $handle ] ) ) {
			return str_replace( '<script ', '<script defer ', $tag );
		}

		return $tag;
	}

	protected function defer_styles( $tag, $handle, $href, $media ) {
		if ( isset( $this->deferred_styles[ $handle ] ) ) {
			return str_replace( "rel='stylesheet'", "rel='preload stylesheet' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $tag );
		}

		return $tag;
	}
}
