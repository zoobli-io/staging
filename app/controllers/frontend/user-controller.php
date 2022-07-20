<?php

namespace Voxel\Controllers\Frontend;

if ( ! defined('ABSPATH') ) {
	exit;
}

class User_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'voxel_ajax_user.follow_user', '@follow_user' );
		$this->on( 'voxel_ajax_user.follow_post', '@follow_post' );
	}

	protected function follow_user() {
		try {
			$user_id = ! empty( $_GET['user_id'] ) ? absint( $_GET['user_id'] ) : null;
			$user = \Voxel\User::get( $user_id );
			if ( ! $user ) {
				throw new \Exception( _x( 'User not found.', 'timeline', 'voxel' ) );
			}

			$current_status = \Voxel\get_follow_status( $user->get_id(), get_current_user_id() );
			if ( $current_status === \Voxel\FOLLOW_ACCEPTED ) {
				\Voxel\set_follow_status( $user->get_id(), get_current_user_id(), \Voxel\FOLLOW_NONE );
			} else {
				\Voxel\set_follow_status( $user->get_id(), get_current_user_id(), \Voxel\FOLLOW_ACCEPTED );
			}

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

	protected function follow_post() {
		try {
			$post_id = ! empty( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : null;
			$post = \Voxel\Post::get( $post_id );
			if ( ! $post ) {
				throw new \Exception( _x( 'Post not found.', 'timeline', 'voxel' ) );
			}

			if ( $post->post_type->get_key() === 'profile' ) {
				$user = \Voxel\User::get_by_profile_id( $post->get_id() );
				if ( ! $user ) {
					throw new \Exception( _x( 'User not found.', 'timeline', 'voxel' ) );
				}

				$current_status = \Voxel\get_follow_status( $user->get_id(), get_current_user_id() );
				if ( $current_status === \Voxel\FOLLOW_ACCEPTED ) {
					\Voxel\set_follow_status( $user->get_id(), get_current_user_id(), \Voxel\FOLLOW_NONE );
				} else {
					\Voxel\set_follow_status( $user->get_id(), get_current_user_id(), \Voxel\FOLLOW_ACCEPTED );
				}
			} else {
				$current_status = \Voxel\get_post_follow_status( $post->get_id(), get_current_user_id() );
				if ( $current_status === \Voxel\FOLLOW_ACCEPTED ) {
					\Voxel\set_post_follow_status( $post->get_id(), get_current_user_id(), \Voxel\FOLLOW_NONE );
				} else {
					\Voxel\set_post_follow_status( $post->get_id(), get_current_user_id(), \Voxel\FOLLOW_ACCEPTED );
				}
			}

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
}