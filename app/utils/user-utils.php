<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

function current_user() {
	return \Voxel\User::get( get_current_user_id() );
}

function get_follow_status( $user_id, $follower_id ) {
	global $wpdb;
	$status = $wpdb->get_var( $wpdb->prepare(
		"SELECT `status` FROM {$wpdb->prefix}voxel_followers_user
			WHERE `user_id` = %d AND `follower_id` = %d",
		$user_id,
		$follower_id
	) );

	if ( is_null( $status ) ) {
		return null;
	}

	$status = intval( $status );
	if ( ! in_array( $status, [ -1, 0, 1 ], true ) ) {
		return null;
	}

	return $status;
}

function set_follow_status( $user_id, $follower_id, $status ) {
	global $wpdb;
	if ( $status === \Voxel\FOLLOW_NONE ) {
		$wpdb->query( $wpdb->prepare(
			"DELETE FROM {$wpdb->prefix}voxel_followers_user WHERE `user_id` = %d AND `follower_id` = %d",
			$user_id,
			$follower_id
		) );
	} else {
		$status = intval( $status );
		if ( ! in_array( $status, [ -1, 0, 1 ], true ) ) {
			return null;
		}

		$wpdb->query( $wpdb->prepare(
			"INSERT INTO {$wpdb->prefix}voxel_followers_user (`user_id`, `follower_id`, `status`) VALUES (%d, %d, %d)",
			$user_id,
			$follower_id,
			$status
		) );
	}

	\Voxel\cache_user_follow_stats( $user_id );
	\Voxel\cache_user_follow_stats( $follower_id );
}

function get_post_follow_status( $post_id, $follower_id ) {
	global $wpdb;
	$status = $wpdb->get_var( $wpdb->prepare(
		"SELECT `status` FROM {$wpdb->prefix}voxel_followers_post
			WHERE `post_id` = %d AND `follower_id` = %d",
		$post_id,
		$follower_id
	) );

	if ( is_null( $status ) ) {
		return null;
	}

	$status = intval( $status );
	if ( ! in_array( $status, [ -1, 0, 1 ], true ) ) {
		return null;
	}

	return $status;
}

function set_post_follow_status( $post_id, $follower_id, $status ) {
	global $wpdb;
	if ( $status === \Voxel\FOLLOW_NONE ) {
		$wpdb->query( $wpdb->prepare(
			"DELETE FROM {$wpdb->prefix}voxel_followers_post WHERE `post_id` = %d AND `follower_id` = %d",
			$post_id,
			$follower_id
		) );
	} else {
		$status = intval( $status );
		if ( ! in_array( $status, [ -1, 0, 1 ], true ) ) {
			return null;
		}

		$wpdb->query( $wpdb->prepare(
			"INSERT INTO {$wpdb->prefix}voxel_followers_post (`post_id`, `follower_id`, `status`) VALUES (%d, %d, %d)",
			$post_id,
			$follower_id,
			$status
		) );
	}

	\Voxel\cache_post_follow_stats( $post_id );
	\Voxel\cache_user_follow_stats( $follower_id );
}

function cache_user_follow_stats( $user_id ) {
	global $wpdb;

	$stats = [
		'following' => [],
		'followed' => [],
	];

	// following
	$following = $wpdb->get_results( $wpdb->prepare( <<<SQL
		SELECT `status`, COUNT(user_id) AS `count`
		FROM {$wpdb->prefix}voxel_followers_user
		WHERE follower_id = %d
		GROUP BY `status`
	SQL, $user_id ) );

	foreach ( $following as $status ) {
		$stats['following'][ (int) $status->status ] = absint( $status->count );
	}

	// followed_by
	$followed = $wpdb->get_results( $wpdb->prepare( <<<SQL
		SELECT `status`, COUNT(user_id) AS `count`
		FROM {$wpdb->prefix}voxel_followers_user
		WHERE user_id = %d
		GROUP BY `status`
	SQL, $user_id ) );

	foreach ( $followed as $status ) {
		$stats['followed'][ (int) $status->status ] = absint( $status->count );
	}

	update_user_meta( $user_id, 'voxel:follow_stats', wp_json_encode( $stats ) );
	return $stats;
}

function cache_user_post_stats( $user_id ) {
	global $wpdb;

	$stats = [];

	$user_id = absint( $user_id );
	$post_types = [];
	foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
		$post_types[] = $wpdb->prepare( '%s', $post_type->get_key() );
	}

	if ( empty( $post_types ) ) {
		update_user_meta( $user_id, 'voxel:post_stats', wp_json_encode( $stats ) );
		return $stats;
	}

	$post_types = join( ',', $post_types );
	$results = $wpdb->get_results( <<<SQL
		SELECT COUNT(ID) AS total, post_type, post_status FROM {$wpdb->posts}
		WHERE post_author = {$user_id}
			AND post_type IN ({$post_types})
			AND post_status IN ('publish','pending')
		GROUP BY post_type, post_status
		ORDER BY post_type
	SQL );

	foreach ( $results as $result ) {
		if ( ! isset( $stats[ $result->post_type ] ) ) {
			$stats[ $result->post_type ] = [];
		}

		$stats[ $result->post_type ][ $result->post_status ] = absint( $result->total );
	}

	update_user_meta( $user_id, 'voxel:post_stats', wp_json_encode( $stats ) );
	return $stats;
}

function get_user_by_id_or_email( $id_or_email ) {
	if ( is_numeric( $id_or_email ) ) {
		$user = get_user_by( 'id', absint( $id_or_email ) );
	} elseif ( $id_or_email instanceof \WP_User ) {
		$user = $id_or_email;
	} elseif ( $id_or_email instanceof \WP_Post ) {
		$user = get_user_by( 'id', (int) $id_or_email->post_author );
	} elseif ( $id_or_email instanceof \WP_Comment && ! empty( $id_or_email->user_id ) ) {
		$user = get_user_by( 'id', (int) $id_or_email->user_id );
	} elseif ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
	} else {
		$user = null;
	}

	return \Voxel\User::get( $user );
}
