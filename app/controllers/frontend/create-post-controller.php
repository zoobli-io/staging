<?php

namespace Voxel\Controllers\Frontend;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Create_Post_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_create_post', '@handle' );
	}

	protected function handle() {
		try {
			$user = \Voxel\current_user();
			$post_type = \Voxel\Post_Type::get( $_GET['post_type'] ?? null );
			if ( ! $post_type ) {
				throw new \Exception( _x( 'Invalid request', 'create post', 'voxel' ) );
			}

			if ( empty( $_POST['postdata'] ) ) {
				throw new \Exception( _x( 'Invalid request', 'create post', 'voxel' ) );
			}

			$post = null;
			$is_editing = false;

			if ( $post_type->get_key() === 'profile' ) {
				$post = $user->get_or_create_profile();
				if ( ! $post ) {
					throw new \Exception( _x( 'Could not update profile.', 'create post', 'voxel' ) );
				}
			}

			if ( ! empty( $_GET['post_id'] ) ) {
				$post = \Voxel\Post::get( $_GET['post_id'] );

				if ( $post_type->get_setting( 'submissions.update_status' ) === 'disabled' ) {
					throw new \Exception( _x( 'Edits not allowed.', 'create post', 'voxel' ) );
				}

				if ( ! ( $post && \Voxel\Post::current_user_can_edit( $_GET['post_id'] ) ) ) {
					throw new \Exception( _x( 'Permission check failed.', 'create post', 'voxel' ) );
				}

				if ( ! ( $post && $post->post_type->get_key() === $post_type->get_key() ) ) {
					throw new \Exception( _x( 'Invalid request.', 'create post', 'voxel' ) );
				}

				$is_editing = true;
			}

			if ( ! $is_editing ) {
				if ( ! $user->can_create_post( $post_type->get_key() ) ) {
					throw new \Exception( _x( 'You do not have permission to create new posts.', 'create post', 'voxel' ) );
				}
			}

			// submissions/edits allowed check
			if ( $is_editing ) {
				if ( $post_type->get_setting( 'submissions.update_status' ) === 'disabled' ) {
					throw new \Exception( _x( 'Edits not allowed.', 'create post', 'voxel' ) );
				}
			} else {
				if ( ! $post_type->get_setting( 'submissions.enabled' ) ) {
					throw new \Exception( _x( 'Submissions not allowed.', 'create post', 'voxel' ) );
				}
			}

			$postdata = json_decode( stripslashes( $_POST['postdata'] ), true );
			// dd($postdata);

			$fields = $post_type->get_fields();
			$sanitized = [];
			$errors = [];

			/** step 1 **/
			// loop through fields
			  // sanitize field values
			  // store sanitized values
			foreach ( $fields as $field ) {
				if ( ! isset( $postdata[ $field->get_key() ] ) ) {
					$sanitized[ $field->get_key() ] = null;
				} else {
					$sanitized[ $field->get_key() ] = $field->sanitize( $postdata[ $field->get_key() ] );
				}
			}

			/** @todo step 2 **/
			// loop through fields
			  // run conditional logic and remove fields that don't pass conditions

			/** @todo step 3 **/
			// loop through remaining fields
			  // run is_required check
			  // run validations on sanitized value
			  // log errors
			foreach ( $fields as $field ) {
				try {
					$value = $sanitized[ $field->get_key() ];
					$field->check_validity( $value );
				} catch ( \Exception $e ) {
					$errors[] = $e->getMessage();
				}
			}

			/** @todo step 4 **/
			// if there are errors, send them back to the create post widget
			// otherwise, create new post from sanitized and validated values
			if ( ! empty( $errors ) ) {
				return wp_send_json( [
					'success' => false,
					'errors' => $errors,
				] );
			}

			// determine post status
			if ( $is_editing ) {
				$post_status = $post_type->get_setting( 'submissions.update_status' ) === 'pending' ? 'pending' : 'publish';
				$post_author_id = $post->get_author_id();
			} else {
				$post_status = $post_type->get_setting( 'submissions.status' ) === 'pending' ? 'pending' : 'publish';
				$post_author_id = $user->get_id();
			}

			$data = [
				'post_type' => $post_type->get_key(),
				'post_title' => $sanitized['title'] ?? '',
				'post_name' => sanitize_title( $sanitized['title'] ?? '' ),
				'post_content' => $sanitized['description'] ?? '',
				'post_status' => $post_status,
				'post_author' => $post_author_id,
			];

			if ( $post ) {
				$data['ID'] = $post->get_id();
			}

			$post_id = wp_insert_post( $data );
			if ( is_wp_error( $post_id ) ) {
				throw new \Exception( _x( 'Could not save post.', 'create post', 'voxel' ) );
			}

			$post = \Voxel\Post::get( $post_id );

			foreach ( $fields as $field ) {
				$field->set_post( $post );
				$field->update( $sanitized[ $field->get_key() ] );
			}

			if ( $data['post_status'] === 'publish' ) {
				if ( $post->post_type->index_table->exists() ) {
					$post->index();
				}
			}

			// success message
			if ( $is_editing ) {
				$update_status = $post_type->get_setting( 'submissions.update_status' );
				if ( $update_status === 'pending' ) {
					$message = 'Your changes have been submitted for review.';
				} elseif ( $update_status === 'pending_merge' ) {
					$message = 'Your changes have been submitted and will be applied once approved.';
				} else {
					$message = 'Your changes have been applied.';
				}
			} else {
				if ( $post_type->get_setting( 'submissions.status' ) === 'pending' ) {
					$message = 'Your post has been submitted for review.';
				} else {
					$message = 'Your post has been published.';
				}
			}

			$view_link = $post->post_type->get_key() === 'profile' ? $user->get_link() : $post->get_link();

			return wp_send_json( [
				'success' => true,
				'edit_link' => $post->get_edit_link(),
				'view_link' => $view_link,
				'message' => $message,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

}
