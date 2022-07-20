<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Membership_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'admin_menu', '@add_menu_page' );
		$this->on( 'admin_post_voxel_create_membership_plan', '@create_plan' );
	}

	protected function add_menu_page() {
		add_menu_page(
			__( 'Plans', 'voxel' ),
			__( 'Plans', 'voxel' ),
			'manage_options',
			'voxel-membership',
			function() {
				$action = sanitize_text_field( $_GET['action'] ?? 'manage-types' );

				if ( $action === 'create-plan' ) {
					require locate_template( 'templates/backend/membership/create-plan.php' );
				} else {
					$post_types = [];
					foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
						$post_types[ $post_type->get_key() ] = $post_type->get_label();
					}

					$default_plan = \Voxel\Membership\Plan::get_or_create_default_plan();

					$plans_config = array_values( array_map( function( $plan ) {
						return $plan->get_editor_config();
					}, \Voxel\Membership\Plan::all() ) );

					$config = [
						'plans' => $plans_config,
						'postTypes' => $post_types,
					];

					$add_plan_url = admin_url('admin.php?page=voxel-membership&action=create-plan');

					wp_enqueue_script( 'vx:membership-editor.js' );
					require locate_template( 'templates/backend/membership/view-plans.php' );
				}
			},
			\Voxel\get_image('post-types/ic_mbr.png'),
			'0.291'
		);
	}

	protected function create_plan() {
		check_admin_referer( 'voxel_manage_membership_plans' );
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		if ( empty( $_POST['membership_plan'] ) || ! is_array( $_POST['membership_plan'] ) ) {
			die;
		}

		$key = sanitize_key( $_POST['membership_plan']['key'] ?? '' );
		$label = sanitize_text_field( $_POST['membership_plan']['label'] ?? '' );
		$description = sanitize_textarea_field( $_POST['membership_plan']['description'] ?? '' );

		try {
			$plan = \Voxel\Membership\Plan::create( [
				'key' => $key,
				'label' => $label,
				'description' => $description,
			] );
		} catch ( \Exception $e ) {
			wp_die( $e->getMessage() );
		}

		wp_safe_redirect( admin_url( 'admin.php?page=voxel-membership' ) );
		exit;
	}
}
