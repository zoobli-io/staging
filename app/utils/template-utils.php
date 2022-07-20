<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

function create_template( $title ) {
	$template_id = wp_insert_post( [
		'post_type' => 'elementor_library',
		'post_status' => 'publish',
		'post_title' => $title,
		'meta_input' => [
			'_elementor_edit_mode' => 'builder',
		],
	] );

	if ( ! is_wp_error( $template_id ) ) {
		if ( ! term_exists( 'voxel-template', 'elementor_library_category' ) ) {
			wp_insert_term( 'Voxel Template', 'elementor_library_category', [
				'slug' => 'voxel-template',
			] );
		}

		wp_set_object_terms( $template_id, 'voxel-template', 'elementor_library_category' );
	}

	return $template_id;
}

function template_exists( $template_id ) {
	return is_int( $template_id ) && get_post_type( $template_id ) === 'elementor_library' && get_post_status( $template_id ) !== 'trash';
}

function create_page( $title, $slug = '' ) {
	return wp_insert_post( [
		'post_type' => 'page',
		'post_status' => 'publish',
		'post_title' => $title,
		'post_name' => $slug,
		'meta_input' => [
			'_elementor_edit_mode' => 'builder',
		],
	] );
}

function page_exists( $page_id ) {
	return is_int( $page_id ) && get_post_type( $page_id ) === 'page' && get_post_status( $page_id ) !== 'trash';
}

function print_template( $template_id ) {
	if ( ! \Voxel\is_elementor_active() ) {
		return;
	}

	if ( ! \Voxel\is_preview_mode() ) {
		\Voxel\enqueue_template_css( $template_id );
		wp_print_styles( 'elementor-post-'.$template_id );
	}

	$frontend = \Elementor\Plugin::$instance->frontend;
	echo $frontend->get_builder_content_for_display( $template_id );
}

function print_template_css( $template_id ) {
	if ( ! \Voxel\is_elementor_active() ) {
		return;
	}

	static $printed = [];
	if ( isset( $printed[ $template_id ] ) ) {
		return;
	}

	$printed[ $template_id ] = true;
	$css_file = \Elementor\Core\Files\CSS\Post::create( $template_id );
	$css_file->print_css();

	// elementor automatically enqueues the CSS file even if the CSS has already been
	// printed inline; to get around it, we dequeue the template css file at a late hook
	add_action( 'wp_footer', function() use ( $template_id ) {
		wp_dequeue_style( sprintf( 'elementor-post-%d', $template_id ) );
	} );
}

function enqueue_template_css( $template_id ) {
	if ( ! \Voxel\is_elementor_active() ) {
		return;
	}

	$css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
	$css_file->enqueue();
}

function get_page_setting( $setting_key, $post_id = null ) {
	if ( ! \Voxel\is_elementor_active() ) {
		return;
	}

	$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
	$page_settings_model = $page_settings_manager->get_model( $post_id ?? get_the_ID() );
	return $page_settings_model->get_settings( $setting_key );
}

function get_template_link( $template, $fallback = null ) {
	if ( empty( $fallback ) ) {
		$fallback = home_url('/');
	}

	return get_permalink( \Voxel\get( 'templates.'.$template ) ) ?: $fallback;
}

function print_header() {
	if ( \Voxel\template_exists( \Voxel\get( 'templates.header' ) ) ) {
		\Voxel\print_template( \Voxel\get( 'templates.header' ) );
	}
}

function print_footer() {
	if ( \Voxel\template_exists( \Voxel\get( 'templates.footer' ) ) ) {
		\Voxel\print_template( \Voxel\get( 'templates.footer' ) );
	}
}

function get_custom_page_settings( $post_id ) {
	return (array) json_decode( get_post_meta( $post_id, '_voxel_page_settings', true ), ARRAY_A );
}

function get_related_widget( \Elementor\Widget_Base $widget, $document_id, $relation_key, $relation_side ) {
	$relations = \Voxel\get_custom_page_settings( $document_id )['relations'] ?? [];
	$relation_group = $relations[ $relation_key ] ?? [];
	$other_side = $relation_side === 'left' ? 'right' : 'left';
	$path_key = $other_side === 'right' ? 'rightPath' : 'leftPath';

	foreach ( $relation_group as $relation ) {
		if ( $relation[ $relation_side ] === $widget->get_id() ) {
			$data = \Elementor\Plugin::$instance->documents->get_current()->get_elements_data();
			$path = explode( '.', $relation[ $path_key ] ?? '' );

			while ( ! empty( $path ) ) {
				$index = array_shift( $path );
				if ( ! isset( $data[ $index ] ) ) {
					break;
				}

				if ( empty( $path ) && $data[ $index ]['elType'] === 'widget' ) {
					return $data[ $index ];
				}

				$data = $data[ $index ]['elements'];
			}
		}
	}

	return null;
}
