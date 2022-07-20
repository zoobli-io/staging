<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Post_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'add_meta_boxes', '@display_fields' );
		$this->on( 'save_post', '@save_post', 1, 2 );
		$this->on( 'add_meta_boxes', '@add_verification_metabox', 70 );
		$this->on( 'voxel/admin/save_post', '@save_verification_status' );
		$this->on( 'voxel/post-type-archive', '@print_archive_template' );
	}

	protected function display_fields() {
		$post = \Voxel\Post::get( get_post() );
		if ( ! ( $post && $post->is_managed_by_voxel() ) ) {
			return;
		}

		add_meta_box(
			'voxel_post_fields',
			_x( 'Fields', 'Post fields metabox title', 'voxel' ),
			function() use ( $post ) { ?>

				<a href="<?= esc_url( $post->get_edit_link() ) ?>" class="ts-button edit-frontend" style="margin: 10px;">
					Edit in frontend form <img src="<?php echo esc_url( \Voxel\get_image('post-types/logo.svg') ) ?>">
				</a>
				
				<!-- <table class="ts-dump-fields" cellpadding="5" style="width: 100%;">
					<?php foreach ( $post->get_fields() as $field ):
						$value = $field->get_value() ?>
						<tr>
							<?php if ( $field->is_ui() ): ?>
								<td colspan="2" style="font-size: 18px; background: #fafafa;">
									<strong><?= $field->get_label() ?></strong>
								</td>
							<?php else: ?>
								<td style="vertical-align: top;"><strong><?= $field->get_label() ?></strong></td>
								<td>
									<?php if ( is_string( $value ) || is_numeric( $value ) ): ?>
										<?= $value ?>
									<?php else: ?>
										<?= wp_json_encode( $value ) ?>
									<?php endif ?>
								</td>
							<?php endif ?>
						</tr>
					<?php endforeach ?>
				</table> -->

				

				<style type="text/css">
					.ts-dump-fields { width: 100%; border-collapse: collapse; }
					.ts-dump-fields, .ts-dump-fields tr, .ts-dump-fields td {
						border: 1px solid #eee;
					}
				</style>
			<?php },
			$post->post_type->get_key(),
			'normal',
			'high'
		);
	}

	protected function print_archive_template( $post_type ) {
		if ( ! $post_type->is_managed_by_voxel() ) {
			return;
		}

		$template_id = $post_type->get_templates()['archive'] ?? null;

		if ( post_password_required( $template_id ) ) {
			return;
		}

		if ( ! \Elementor\Plugin::$instance->db->is_built_with_elementor( $template_id ) ) {
			return;
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
	}

	protected function save_post( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['vx_admin_save_post_nonce'] ?? '', 'vx_admin_save_post_nonce' )  ) {
			return;
		}

		if ( is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		do_action( 'voxel/admin/save_post', \Voxel\Post::get( $post ) );
	}

	protected function add_verification_metabox() {
		$post = \Voxel\Post::get( get_post() );
		if ( ! ( $post && $post->is_managed_by_voxel() ) ) {
			return;
		}

		add_meta_box(
			'vx_verification',
			_x( 'Verification Status', 'Post verification status metabox title', 'voxel' ),
			function() use ( $post ) {
				wp_nonce_field( 'vx_admin_save_post_nonce', 'vx_admin_save_post_nonce' );
				?>
				<select name="vx_verification_status" style="width: 100%; margin-top: 5px;">
					<option value="verified" <?php selected( $post->is_verified() ) ?>>Verified</option>
					<option value="unverified" <?php selected( ! $post->is_verified() ) ?>>Unverified</option>
				</select>
			<?php },
			null,
			'side',
		);
	}

	protected function save_verification_status( $post ) {
		$current_status = $post->is_verified();
		$new_status = ( $_POST['vx_verification_status'] ?? null ) === 'verified';
		if ( $current_status !== $new_status ) {
			$post->set_verified( $new_status );
		}
	}
}
