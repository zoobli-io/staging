<?php

namespace Voxel\Controllers\Elementor;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Elementor_Controller extends \Voxel\Controllers\Base_Controller {

	protected function authorize() {
		return class_exists( '\Elementor\Plugin' );
	}

	protected function hooks() {
		$this->on( 'elementor/widgets/register', '@register_widgets' );
		$this->on( 'elementor/controls/controls_registered', '@register_custom_controls', 1000 );
		$this->on( 'admin_footer', '@load_backend_icon_picker', 100 );
		$this->on( 'elementor/document/after_save', '@save_voxel_config', 100, 2 );
		$this->on( 'elementor/elements/categories_registered', '@register_widget_categories' );
		$this->on( 'admin_footer', '@enqueue_line_awesome_in_backend' );
		$this->on( 'elementor/editor/after_enqueue_scripts', '@enqueue_line_awesome_in_backend' );
		$this->on( 'elementor/init', '@add_custom_tabs' );

		$this->on( 'elementor/editor/init', '@set_current_post_in_editor' );
		$this->on( 'elementor/ajax/register_actions', '@set_current_post_in_editor' );

		$this->on( 'elementor/editor/init', '@set_current_term_in_editor' );
		$this->on( 'elementor/ajax/register_actions', '@set_current_term_in_editor' );

		$this->on( 'wp_head', '@print_dynamic_styles' );

		$this->filter( 'elementor/widget/print_template', '@handle_tags_in_editor' );
		$this->filter( 'elementor/icons_manager/additional_tabs', '@register_custom_icons' );
		$this->filter( 'elementor/editor/localize_settings', '@editor_config' );
		$this->filter( 'parse_query', '@hide_voxel_templates_from_library' );
		$this->filter( 'wp_get_attachment_image_src', '@fix_editor_image_preview', 100, 4 );

		// css should be rendered for all possible visibility states
		$this->on( 'elementor/element/before_parse_css', function() { \Voxel\set_rendering_css(true); } );
		$this->on( 'elementor/element/parse_css', function() { \Voxel\set_rendering_css(false); } );
	}

	protected function register_widgets() {
		$manager = \Elementor\Plugin::instance()->widgets_manager;
		foreach ( \Voxel\config('widgets') as $widget ) {
			$manager->register( new $widget );
		}
	}

	/**
	 * Allows for rendering dynamic tags in Elementor editor while the user is editing.
	 *
	 * @since 1.0
	 */
	protected function handle_tags_in_editor( $template ) {
		if ( empty( $template ) ) {
			return $template;
		}

		return '<# var settings = voxel_handle_tags(settings) #>'.$template;
	}

	protected function register_custom_controls( $controls_manager ) {
		$controls_manager->register( new \Voxel\Custom_Controls\Repeater_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Media_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Gallery_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Icons_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Select2_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Url_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Relation_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Text_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Textarea_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Number_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Wysiwyg_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Color_Control );
		$controls_manager->register( new \Voxel\Custom_Controls\Visibility_Control );
	}

	protected function load_backend_icon_picker() {
		if ( class_exists( '\Elementor\Plugin' ) ) {
			$config = \Elementor\Icons_Manager::get_icon_manager_tabs();
			require locate_template( 'templates/backend/icon-picker.php' );
		}
	}

	protected function register_custom_icons( $packs ) {
		$base_url = trailingslashit( get_template_directory_uri() ).'assets/icons/line-awesome/';

		// @todo: minify line-awesome.css and line-awesome.js on production build
		$packs['la-regular'] = [
			'name' => 'la-regular',
			'label' => __( 'Line Awesome - Regular', 'voxel' ),
			'url' => $base_url.'line-awesome.css',
			'enqueue' => [],
			'prefix' => 'la-',
			'displayPrefix' => 'lar',
			'labelIcon' => 'fab fa-font-awesome-alt',
			'ver' => '1.3.0',
			'fetchJson' => $base_url.'line-awesome-regular.js',
			'native' => false,
		];

		$packs['la-solid'] = [
			'name' => 'la-solid',
			'label' => __( 'Line Awesome - Solid', 'voxel' ),
			'url' => $base_url.'line-awesome.css',
			'enqueue' => [],
			'prefix' => 'la-',
			'displayPrefix' => 'las',
			'labelIcon' => 'fab fa-font-awesome-alt',
			'ver' => '1.3.0',
			'fetchJson' => $base_url.'line-awesome-solid.js',
			'native' => false,
		];

		$packs['la-brands'] = [
			'name' => 'la-brands',
			'label' => __( 'Line Awesome - Brands', 'voxel' ),
			'url' => $base_url.'line-awesome.css',
			'enqueue' => [],
			'prefix' => 'la-',
			'displayPrefix' => 'lab',
			'labelIcon' => 'fab fa-font-awesome-alt',
			'ver' => '1.3.0',
			'fetchJson' => $base_url.'line-awesome-brands.js',
			'native' => false,
		];

		return $packs;
	}

	protected function editor_config( $config ) {
		$post_id = \Elementor\Plugin::$instance->editor->get_post_id();
		$settings = \Voxel\get_custom_page_settings( $post_id );

		$config['voxel'] = [
			'relations' => (object) ( $settings['relations'] ?? [] ),
		];

		return $config;
	}

	protected function save_voxel_config( $document, $data ) {
		$config = json_decode( stripslashes( $_REQUEST['voxel'] ?? '' ), ARRAY_A );
		$settings_to_save = [];

		if ( ! empty( $config['relations'] ) && is_array( $config['relations'] ) ) {
			$settings_to_save['relations'] = $config['relations'];
		}

		if ( ! empty( $settings_to_save ) ) {
			update_post_meta( $document->get_id(), '_voxel_page_settings', wp_json_encode( $settings_to_save ) );
		} else {
			delete_post_meta( $document->get_id(), '_voxel_page_settings' );
		}
	}

	protected function register_widget_categories( $elements_manager ) {
		$elements_manager->add_category( 'voxel', [
			'title' => __( 'Voxel 🎉', 'voxel' ),
			'icon' => 'eicon-plus',
		] );
	}

	protected function hide_voxel_templates_from_library( $query ) {
		global $typenow;
		if ( ! is_admin() || $typenow !== 'elementor_library' || ( $_GET['tabs_group'] ?? '' ) !== 'library' ) {
			return $query;
		}

		if ( isset( $_GET['elementor_library_type'] ) && $_GET['elementor_library_type'] !== 'page' ) {
			return $query;
		}

		if ( ! isset( $query->query_vars['tax_query'] ) ) {
			$query->query_vars['tax_query'] = [];
		}

		$query->query_vars['tax_query'][] = [
			'taxonomy' => 'elementor_library_category',
			'field' => 'slug',
			'terms' => 'voxel-template',
			'operator' => 'NOT IN',
		];

		return $query;
	}

	protected function enqueue_line_awesome_in_backend() {
		$base_url = trailingslashit( get_template_directory_uri() ).'assets/icons/line-awesome/';
		wp_enqueue_style( 'line-awesome', $base_url.'line-awesome.css', [], '1.3.0' );
	}

	protected function add_custom_tabs() {
		\Elementor\Controls_Manager::add_tab( 'tab_voxel', 'Voxel' );
	}

	protected function set_current_post_in_editor() {
		if ( \Voxel\is_elementor_ajax() ) {
			$template_id = absint( $_REQUEST['editor_post_id'] ?? '' );
			$current_post = $this->_get_current_post_in_editor( $template_id );
			\Voxel\set_current_post( $current_post );
		} elseif ( \Voxel\is_edit_mode() ) {
			$template_id = absint( $_REQUEST['post'] ?? '' );
			$current_post = $this->_get_current_post_in_editor( $template_id );
			\Voxel\set_current_post( $current_post );
		}
	}

	protected function set_current_term_in_editor() {
		if ( \Voxel\is_elementor_ajax() ) {
			$template_id = absint( $_REQUEST['editor_post_id'] ?? '' );
			$current_term = $this->_get_current_term_in_editor( $template_id );
			\Voxel\set_current_term( $current_term );
		} elseif ( \Voxel\is_edit_mode() ) {
			$template_id = absint( $_REQUEST['post'] ?? '' );
			$current_term = $this->_get_current_term_in_editor( $template_id );
			\Voxel\set_current_term( $current_term );
		}
	}

	/**
	 * If we're editing a template for a voxel post type, try
	 * to find a post to use for previewing data.
	 *
	 * @since 1.0
	 */
	private function _get_current_post_in_editor( $template_id ) {
		$post_type = current( array_filter( \Voxel\Post_Type::get_all(), function( $post_type ) use ( $template_id ) {
			$templates = $post_type->get_templates();
			return in_array( $template_id, [ $templates['single'], $templates['card'] ] );
		} ) );

		if ( $post_type ) {
			$post = current( get_posts( [
				'number' => 1,
				'status' => 'publish',
				'post_type' => $post_type->get_key(),
			] ) );

			// if we're editing the preview card for a post type, pass that information to the
			// editor frontend so that we can adjust the editing layout
			if ( (int) $post_type->get_templates()['card'] === (int) $template_id ) {
				add_filter( 'voxel/js/elementor-editor-config', function( $config ) {
					$config['is_preview_card'] = true;
					return $config;
				} );
			}

			return \Voxel\Post::get( $post ) ?? \Voxel\Post::dummy( [ 'post_type' => $post_type->get_key() ] );
		} else {
			return \Voxel\Post::get( $template_id );
		}
	}

	private function _get_current_term_in_editor( $template_id ) {
		$taxonomy = current( array_filter( \Voxel\Taxonomy::get_all(), function( $taxonomy ) use ( $template_id ) {
			$templates = $taxonomy->get_templates();
			return in_array( $template_id, [ $templates['single'], $templates['card'] ] );
		} ) );

		if ( $taxonomy ) {
			$term = get_terms( [
				'taxonomy' => $taxonomy->get_key(),
				'number' => 1,
				'hide_empty' => false,
			] );

			if ( is_array( $term ) && ( $term[0] ?? null ) instanceof \WP_Term ) {
				return \Voxel\Term::get( $term[0] );
			}
		}

		return \Voxel\Term::dummy();
	}

	/**
	 * Fixes error: Elementor\Images_Manager retrieves previews in editor for
	 * all media controls. Those media controls that are using dynamic tags haven't
	 * been parsed yet and an invalid value is passed to `wp_get_attachment_image_src`.
	 *
	 * @since 1.0
	 */
	protected function fix_editor_image_preview( $image, $attachment_id, $size, $icon ) {
		if ( is_string( $attachment_id ) && strncmp( $attachment_id, '@tags()', 7 ) === 0 && \Voxel\is_elementor_ajax() ) {
			$attachment_id = \Voxel\render( $attachment_id );
			$src = wp_get_attachment_image_src( absint( $attachment_id ), $size, $icon );
			return $src ? $src : [''];
		}

		return $image;
	}

	protected function print_dynamic_styles() {
		$mobile_end = \Elementor\Plugin::$instance->breakpoints->get_breakpoints('mobile')->get_value();
		$tablet_start = $mobile_end + 1;
		$tablet_end = \Elementor\Plugin::$instance->breakpoints->get_breakpoints('tablet')->get_value();
		$desktop_start = $tablet_end + 1;
		if ( class_exists( '\Elementor\Plugin' ) ) {
			echo <<<HTML
			<style type="text/css">
				@media screen and (max-width: {$mobile_end}px) { .vx-hidden-mobile { display: none; } }
				@media screen and (min-width: {$tablet_start}px) and (max-width: {$tablet_end}px) { .vx-hidden-tablet { display: none; } }
				@media screen and (min-width: {$desktop_start}px) { .vx-hidden-desktop { display: none; } }
			</style>
			HTML;
		}
	}
}
