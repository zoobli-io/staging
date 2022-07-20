<?php

$voxel_post = \Voxel\Post::get( $post );
$post_type = $voxel_post->post_type;

if ( ! $post_type->is_managed_by_voxel() || $voxel_post->is_built_with_elementor() ) {
	$template_id = $post->ID;
	get_header();
	if ( \Voxel\is_preview_mode() && \Voxel\get_page_setting( 'voxel_hide_header', $template_id ) !== 'yes' ) {
		\Voxel\print_header();
	}

	the_content();

	if ( \Voxel\is_preview_mode() && \Voxel\get_page_setting( 'voxel_hide_footer', $template_id ) !== 'yes' ) {
		\Voxel\print_footer();
	}
	get_footer();
	return;
}

$template_id = $post_type->get_templates()['single'] ?? null;

if ( post_password_required( $template_id ) ) {
	return '';
}

if ( ! \Elementor\Plugin::$instance->db->is_built_with_elementor( $template_id ) ) {
	return '';
}

$frontend = \Elementor\Plugin::$instance->frontend;
add_action( 'wp_enqueue_scripts', [ $frontend, 'enqueue_styles' ] );
\Voxel\enqueue_template_css( $template_id );

get_header();

if ( \Voxel\get_page_setting( 'voxel_hide_header', $template_id ) !== 'yes' ) {
	\Voxel\print_header();
}

echo $frontend->get_builder_content_for_display( $template_id );

if ( \Voxel\get_page_setting( 'voxel_hide_footer', $template_id ) !== 'yes' ) {
	\Voxel\print_footer();
}

get_footer();
