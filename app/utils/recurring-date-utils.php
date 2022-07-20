<?php

namespace Voxel\Utils\Recurring_Date;

if ( ! defined('ABSPATH') ) {
	exit;
}

function get_current_start_query( $range_start, $range_end ) {
	$range_start = esc_sql( $range_start );
	$range_end = esc_sql( $range_end );
	return <<<SQL
		CASE
			WHEN (`unit` = 'DAY') THEN (
				IF(
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( DAY, `start`, '{$range_start}' ) / `frequency` )
					) ) DAY ) BETWEEN '{$range_start}' AND '{$range_end}',
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( DAY, `start`, '{$range_start}' ) / `frequency` )
					) ) DAY ),
					DATE_ADD( `start`, INTERVAL ( `frequency` * CEIL(
						( TIMESTAMPDIFF( DAY, `start`, '{$range_start}' ) / `frequency` ) + 0.00001
					) ) DAY )
				)
			)
			WHEN (`unit` = 'MONTH') THEN (
				IF(
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( MONTH, `start`, '{$range_start}' ) / `frequency` )
					) ) MONTH ) BETWEEN '{$range_start}' AND '{$range_end}',
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( MONTH, `start`, '{$range_start}' ) / `frequency` )
					) ) MONTH ),
					DATE_ADD( `start`, INTERVAL ( `frequency` * CEIL(
						( TIMESTAMPDIFF( MONTH, `start`, '{$range_start}' ) / `frequency` ) + 0.00001
					) ) MONTH )
				)
			)
			ELSE `start`
		END AS current_start
	SQL;
}

function get_where_clause( $range_start, $range_end, $input_mode = 'date-range', $match_ongoing = true ) {
	$range_start = esc_sql( $range_start );
	$range_end = esc_sql( $range_end );

	if ( $input_mode === 'single-date' ) {
		$query = <<<SQL
			( `start` >= '{$range_start}' )
			OR ( `unit` = 'DAY' AND (
				DATE_ADD( `start`, INTERVAL ( `frequency` * CEIL(
					( TIMESTAMPDIFF( DAY, `start`, '{$range_start}' ) / `frequency` ) + 0.00001
				) ) DAY ) <= `until`
			) )
			OR ( `unit` = 'MONTH' AND (
				DATE_ADD( `start`, INTERVAL ( `frequency` * CEIL(
					( TIMESTAMPDIFF( MONTH, `start`, '{$range_start}' ) / `frequency` ) + 0.00001
				) ) MONTH ) <= `until`
			) )
		SQL;

		if ( $match_ongoing ) {
			$query .= <<<SQL
				OR ( `end` >= '{$range_start}' )
				OR ( `unit` = 'DAY' AND (
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( DAY, `start`, '{$range_start}' ) / `frequency` )
					) ) DAY ) BETWEEN '{$range_start}' AND `until`
				) )
				OR ( `unit` = 'MONTH' AND (
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( MONTH, `start`, '{$range_start}' ) / `frequency` )
					) ) MONTH ) BETWEEN '{$range_start}' AND '{$range_end}', `until`
				) )
			SQL;
		}
	} else {
		$query = <<<SQL
			( `start` BETWEEN '{$range_start}' AND '{$range_end}' )
			OR ( `unit` = 'DAY' AND (
				DATE_ADD( `start`, INTERVAL ( `frequency` * CEIL(
					( TIMESTAMPDIFF( DAY, `start`, '{$range_start}' ) / `frequency` ) + 0.00001
				) ) DAY ) <= LEAST( '{$range_end}', `until` )
			) )
			OR ( `unit` = 'MONTH' AND (
				DATE_ADD( `start`, INTERVAL ( `frequency` * CEIL(
					( TIMESTAMPDIFF( MONTH, `start`, '{$range_start}' ) / `frequency` ) + 0.00001
				) ) MONTH ) <= LEAST( '{$range_end}', `until` )
			) )
		SQL;

		if ( $match_ongoing ) {
			$query .= <<<SQL
				OR ( `end` BETWEEN '{$range_start}' AND '{$range_end}' )
				OR ( `unit` = 'DAY' AND (
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( DAY, `start`, '{$range_start}' ) / `frequency` )
					) ) DAY ) BETWEEN '{$range_start}' AND LEAST( '{$range_end}', `until` )
				) )
				OR ( `unit` = 'MONTH' AND (
					DATE_ADD( `end`, INTERVAL ( `frequency` * FLOOR(
						( TIMESTAMPDIFF( MONTH, `start`, '{$range_start}' ) / `frequency` )
					) ) MONTH ) BETWEEN '{$range_start}' AND LEAST( '{$range_end}', `until` )
				) )
			SQL;
		}
	}

	return $query;
}

function get_upcoming( $recurring_dates, $limit = 10, $max = null ) {
	$next = [];
	$now = \Voxel\utc();

	foreach ( $recurring_dates as $date ) {
		$start = date_create_from_format( 'Y-m-d H:i:s', $date['start'] );
		$end = date_create_from_format( 'Y-m-d H:i:s', $date['end'] );
		$until = isset( $date['until'] ) ? date_create_from_format( 'Y-m-d', $date['until'] ) : null;
		$count = $limit;

		if ( ! ( $start && $end ) ) {
			continue;
		}

		if ( $start >= $now ) {
			$next[] = [
				'start' => $start->format( 'Y-m-d H:i:s' ),
				'end' => $end->format( 'Y-m-d H:i:s' ),
			];
			$count--;
		}

		$frequency = isset( $date['frequency'] ) ? absint( $date['frequency'] ) : null;
		$unit = \Voxel\from_list( $date['unit'] ?? null, [ 'day', 'week', 'month', 'year' ] );

		if ( ! ( $frequency >= 1 && $unit && $until && $until > $now ) ) {
			continue;
		}

		if ( $unit === 'week' ) {
			$unit = 'day';
			$frequency *= 7;
		} elseif ( $unit === 'year' ) {
			$unit = 'month';
			$frequency *= 12;
		}

		if ( $start < $now ) {
			if ( $unit === 'day' ) {
				$days_to_add = $frequency * ceil( $now->diff( $start )->days / $frequency );
				$start->modify( sprintf( '+%d days', $days_to_add ) );
				$end->modify( sprintf( '+%d days', $days_to_add ) );
			} elseif ( $unit === 'month' ) {
				$diff = $now->diff( $start );
				$months_to_add = $frequency * ceil( ( $diff->m + ( $diff->y * 12 ) ) / $frequency );
				$start->modify( sprintf( '+%d months', $months_to_add ) );
				$end->modify( sprintf( '+%d months', $months_to_add ) );
			}

			$next[] = [
				'start' => $start->format( 'Y-m-d H:i:s' ),
				'end' => $end->format( 'Y-m-d H:i:s' ),
			];
			$count--;
		}

		for ( $i=0; $i < $count; $i++ ) {
			if ( $unit === 'day' ) {
				$start->modify( sprintf( '+%d days', $frequency ) );
				$end->modify( sprintf( '+%d days', $frequency ) );
			} elseif ( $unit === 'month' ) {
				$start->modify( sprintf( '+%d months', $frequency ) );
				$end->modify( sprintf( '+%d months', $frequency ) );
			}

			if ( $start > $until ) {
				break;
			}

			$next[] = [
				'start' => $start->format( 'Y-m-d H:i:s' ),
				'end' => $end->format( 'Y-m-d H:i:s' ),
			];
		}
	}

	usort( $next, function( $a, $b ) {
		return strtotime( $a['start'] ) - strtotime( $b['start'] );
	} );

	$next = array_slice( $next, 0, $limit );

	if ( $max && $timestamp = strtotime( $max ) ) {
		$next = array_filter( $next, function( $date ) use ( $timestamp ) {
			return strtotime( $date['start'] ) <= $timestamp;
		} );
	}

	return $next;
}
