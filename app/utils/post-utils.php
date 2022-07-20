<?php

namespace Voxel;

if ( ! defined('ABSPATH') ) {
	exit;
}

function get_current_post( $force_get = false ) {
	static $current_post;
	if ( ! is_null( $current_post ) && $force_get === false ) {
		return $current_post;
	}

	global $post;
	if ( $post instanceof \WP_Post ) {
		$current_post = \Voxel\Post::get( $post );
	} else {
		$queried_object = get_queried_object();
		if ( $queried_object instanceof \WP_Post ) {
			$current_post = \Voxel\Post::get( $queried_object );
		}
	}

	return $current_post;
}

function set_current_post( \Voxel\Post $the_post ) {
	global $post;
	$post = $the_post->get_wp_post_object();
	setup_postdata( $post );
	\Voxel\get_current_post( true );
}

function get_current_post_type() {
	$post = \Voxel\get_current_post();
	return $post ? $post->post_type : null;
}

function get_current_term( $force_get = false ) {
	if ( ! is_null( $GLOBALS['vx_current_term'] ?? null ) && $force_get === false ) {
		return $GLOBALS['vx_current_term'];
	}

	$GLOBALS['vx_current_term'] = \Voxel\Term::get( get_queried_object() );
	return $GLOBALS['vx_current_term'];
}

function set_current_term( \Voxel\Term $term ) {
	$GLOBALS['vx_current_term'] = $term;
}

function get_search_results( $request, $options = [] ) {
	$options = array_merge( [
		'limit' => 10,
		'render' => true,
		'ids' => null,
	], $options );

	$max_limit = apply_filters( 'voxel/get_search_results/max_limit', 50 );
	$limit = min( $options['limit'], $max_limit );

	$results = [
		'ids' => [],
		'render' => null,
		'has_next' => false,
		'has_prev' => false,
	];

	$post_type = \Voxel\Post_Type::get( sanitize_text_field( $request['type'] ?? '' ) );
	if ( ! $post_type ) {
		return;
	}

	$template_id = $post_type->get_templates()['card'];
	if ( ! \Voxel\template_exists( $template_id ) ) {
		return;
	}

	if ( is_array( $options['ids'] ) ) {
		$results['ids'] = $options['ids'];
	} else {
		$args = [];
		foreach ( $post_type->get_filters() as $filter ) {
			if ( isset( $request[ $filter->get_key() ] ) ) {
				$args[ $filter->get_key() ] = $request[ $filter->get_key() ];
			}
		}

		$args['limit'] = absint( $limit );
		$page = absint( $request['pg'] ?? 1 );
		if ( $page > 1 ) {
			$args['offset'] = ( $args['limit'] * ( $page - 1 ) );
		}

		$args['limit'] += 1;

		$_start = microtime( true );
		$post_ids = $post_type->query( $args );
		$_query_time = microtime( true ) - $_start;

		$results['has_prev'] = $page > 1;
		if ( count( $post_ids ) === $args['limit'] ) {
			$results['has_next'] = true;
			array_pop( $post_ids );
		}

		$results['ids'] = $post_ids;

		do_action( 'qm/info', sprintf( 'Query time: %sms', round( $_query_time * 1000, 1 ) ) );
		do_action( 'qm/info', trim( $post_type->get_index_query()->get_sql( $args ) ) );
	}

	if ( $options['render'] ) {
		do_action( 'qm/start', 'render_search_results' );

		do_action( 'voxel/before_render_search_results' );

		_prime_post_caches( $results['ids'] );
		ob_start();
		$current_request_post = \Voxel\get_current_post();

		$has_results = false;
		foreach ( $results['ids'] as $post_id ) {
			$post = \Voxel\Post::get( $post_id );
			if ( ! $post ) {
				continue;
			}

			$has_results = true;
			\Voxel\set_current_post( $post );

			echo '<div class="ts-preview" '._post_get_position_attr( $post ).' '._post_get_marker_attr( $post ).'>';
			\Voxel\print_template( $template_id );
			echo '</div>';

			do_action( 'qm/lap', 'render_search_results' );
		}

		if ( ! $has_results ) {
			require locate_template( 'templates/widgets/post-feed/no-results.php' );
		}

		// reset current post
		if ( $current_request_post ) {
			\Voxel\set_current_post( $current_request_post );
		}
		if ( \Voxel\is_dev_mode() ) { ?>
			<script type="text/javascript">
				console.log('Query time: %c' + <?= round( ( $_query_time ?? 0 ) * 1000, 1 ) ?> + 'ms', 'color: #81c784;');
			</script>
		<?php }

		$results['render'] = ob_get_clean();

		do_action( 'qm/stop', 'render_search_results' );
	}

	return $results;
}

function _post_get_position_attr( $post ) {
	$location = $post->get_field('location');
	$loc = $location ? $location->get_value() : [];
	$position = ( $loc['latitude'] ?? null && $loc['longitude'] ?? null ) ? $loc['latitude'].','.$loc['longitude'] : null;
	return $position ? sprintf( 'data-position="%s"', esc_attr( $position ) ) : '';
}

function _post_get_marker_attr( $post ) {
	return sprintf( 'data-marker="%s"', esc_attr( _post_get_marker( $post ) ) );
}

function _post_get_marker( $post ) {
	$marker_type = $post->post_type->get_setting( 'map.marker_type' );

	$icon_markup = \Voxel\get_icon_markup( $post->post_type->get_setting( 'map.marker_icon' ) );
	$default_marker = '<div class="map-marker marker-type-icon">'.$icon_markup.'</div>';

	if ( $marker_type === 'text' ) {
		$text = esc_html( \Voxel\render( $post->post_type->get_setting( 'map.marker_text' ) ) );
		return '<div class="map-marker marker-type-text">'.$text.'</div>';
	} elseif ( $marker_type === 'image' ) {
		$field = $post->get_field( $post->post_type->get_setting( 'map.marker_image' ) );
		if ( ! ( $field && $field->get_type() === 'image' ) ) {
			return $default_marker;
		}

		$image_ids = $field->get_value();
		$image_id = array_shift( $image_ids );
		$url = esc_attr( wp_get_attachment_image_url( $image_id, 'thumbnail' ) );
		$alt = esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) );
		if ( empty( $url ) ) {
			return $default_marker;
		}

		return '<div class="map-marker marker-type-image"><img src="'.$url.'" alt="'.$alt.'"></div>';
	} else {
		return $default_marker;
	}
}

function cache_post_review_stats( $post_id ) {
	global $wpdb;

	$stats = [
		'total' => 0,
		'average' => null,
		'by_score' => [],
	];

	$results = $wpdb->get_row( $wpdb->prepare( <<<SQL
		SELECT AVG(review_score) AS average, COUNT(review_score) AS total
		FROM {$wpdb->prefix}voxel_timeline
		WHERE post_id = %d AND review_score IS NOT NULL
	SQL, $post_id ) );

	if ( ! ( is_numeric( $results->average ) && is_numeric( $results->total ) && $results->total > 0 ) ) {
		update_post_meta( $post_id, 'voxel:review_stats', wp_json_encode( $stats ) );
		return $stats;
	}

	$stats['total'] = absint( $results->total );
	$stats['average'] = \Voxel\clamp( $results->average, -2, 2 );

	$by_score = $wpdb->get_results( $wpdb->prepare( <<<SQL
		SELECT ROUND(review_score) AS score, COUNT(review_score) AS total
		FROM {$wpdb->prefix}voxel_timeline
		WHERE post_id = %d AND review_score BETWEEN -2 AND 2
		GROUP BY ROUND(review_score)
	SQL, $post_id ) );

	foreach ( $by_score as $score ) {
		if ( is_numeric( $score->score ) && is_numeric( $score->total ) && $score->total > 0 ) {
			$stats['by_score'][ (int) $score->score ] = absint( $score->total );
		}
	}

	update_post_meta( $post_id, 'voxel:review_stats', wp_json_encode( $stats ) );
	return $stats;
}

function cache_post_follow_stats( $post_id ) {
	global $wpdb;

	$stats = [
		'followed' => [],
	];

	// followed_by
	$followed = $wpdb->get_results( $wpdb->prepare( <<<SQL
		SELECT `status`, COUNT(post_id) AS `count`
		FROM {$wpdb->prefix}voxel_followers_post
		WHERE post_id = %d
		GROUP BY `status`
	SQL, $post_id ) );

	foreach ( $followed as $status ) {
		$stats['followed'][ (int) $status->status ] = absint( $status->count );
	}

	update_post_meta( $post_id, 'voxel:follow_stats', wp_json_encode( $stats ) );
	return $stats;
}
