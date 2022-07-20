<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Db_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'after_setup_theme', '@prepare_db', 0 );
		$this->on( 'after_setup_theme', '@create_events_table', 0 );
		$this->on( 'after_setup_theme', '@create_orders_table', 0 );
		$this->on( 'after_setup_theme', '@create_timeline_table', 0 );
		$this->on( 'after_setup_theme', '@create_followers_table', 0 );
		$this->on( 'after_setup_theme', '@create_work_hours_table', 0 );
		$this->on( 'after_setup_theme', '@modify_terms_table', 0 );
	}

	protected function prepare_db() {
		$db_version = '0.12';
		$current_version = \Voxel\get( 'versions.db' );
		if ( $db_version === $current_version ) {
			return;
		}

		global $wpdb;

		// wp_posts must use InnoDB
		$wp_posts = $wpdb->get_row( $wpdb->prepare( "SHOW TABLE STATUS WHERE name = %s", $wpdb->posts ) );
		$wp_posts_engine = $wp_posts->Engine ?? null;
		if ( $wp_posts_engine !== 'InnoDB' ) {
			$wpdb->query( "ALTER TABLE {$wpdb->posts} ENGINE = InnoDB;" );
		}

		\Voxel\set( 'versions.db', $db_version );
	}

	protected function create_events_table() {
		$table_version = '0.14';
		$current_version = \Voxel\get( 'versions.events_table' );
		if ( $table_version === $current_version ) {
			return;
		}

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// create events table
		$table_name = $wpdb->prefix . 'voxel_events';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				`post_id` BIGINT(20) UNSIGNED NOT NULL,
				`post_type` VARCHAR(64) NOT NULL,
				`field_key` VARCHAR(64) NOT NULL,
				`details` VARCHAR(256) NOT NULL,
				`start` DATETIME GENERATED ALWAYS AS (
					CONVERT_TZ(
						JSON_UNQUOTE( JSON_EXTRACT( details, "\$.start" ) ),
						JSON_UNQUOTE( JSON_EXTRACT( details, "\$.tz" ) ),
						"+00:00"
					)
				) VIRTUAL,
				`end` DATETIME GENERATED ALWAYS AS (
					CONVERT_TZ(
						JSON_UNQUOTE( JSON_EXTRACT( details, "\$.end" ) ),
						JSON_UNQUOTE( JSON_EXTRACT( details, "\$.tz" ) ),
						"+00:00"
					)
				) VIRTUAL,
				`frequency` SMALLINT UNSIGNED GENERATED ALWAYS AS (
					CASE WHEN JSON_VALID( JSON_EXTRACT( details, "\$.frequency" ) )
						THEN ( CASE
							WHEN JSON_UNQUOTE( JSON_EXTRACT( details, "\$.unit" ) ) = 'week' THEN ( JSON_UNQUOTE( JSON_EXTRACT( details, "\$.frequency" ) ) * 7 )
							WHEN JSON_UNQUOTE( JSON_EXTRACT( details, "\$.unit" ) ) = 'year' THEN ( JSON_UNQUOTE( JSON_EXTRACT( details, "\$.frequency" ) ) * 12 )
							ELSE JSON_UNQUOTE( JSON_EXTRACT( details, "\$.frequency" ) )
						END )
					ELSE NULL END
				) VIRTUAL,
				`unit` enum('day','month') GENERATED ALWAYS AS (
					CASE WHEN JSON_VALID( JSON_EXTRACT( details, "\$.unit" ) )
						THEN ( CASE
							WHEN JSON_UNQUOTE( JSON_EXTRACT( details, "\$.unit" ) ) IN ('day','week') THEN 'day'
							WHEN JSON_UNQUOTE( JSON_EXTRACT( details, "\$.unit" ) ) IN ('month','year') THEN 'month'
							ELSE NULL
						END )
					ELSE NULL END
				) VIRTUAL,
				`until` DATETIME GENERATED ALWAYS AS (
					CASE WHEN JSON_VALID( JSON_EXTRACT( details, "\$.until" ) )
						THEN CONVERT_TZ(
							JSON_UNQUOTE( JSON_EXTRACT( details, "\$.until" ) ),
							JSON_UNQUOTE( JSON_EXTRACT( details, "\$.tz" ) ),
							"+00:00"
						)
					ELSE NULL END
				) VIRTUAL,
				PRIMARY KEY (`id`),
					KEY (`post_id`),
					KEY (`post_type`),
					KEY (`field_key`),
					KEY (`start`),
					KEY (`end`),
					KEY (`frequency`),
					KEY (`unit`),
					KEY (`until`),
				FOREIGN KEY (`post_id`)
					REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		\Voxel\set( 'versions.events_table', $table_version );
	}

	protected function create_orders_table() {
		$table_version = '0.26';
		$current_version = \Voxel\get( 'versions.orders_table' );
		if ( $table_version === $current_version ) {
			return;
		}

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// create orders table
		$table_name = $wpdb->prefix . 'voxel_orders';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				post_id BIGINT(20) UNSIGNED NOT NULL,
				product_type VARCHAR(64) NOT NULL,
				product_key VARCHAR(64) NOT NULL,
				customer_id BIGINT(20) UNSIGNED NOT NULL,
				details MEDIUMTEXT NOT NULL,
				status VARCHAR(32) NOT NULL,
				session_id VARCHAR(128) NOT NULL,
				mode ENUM("payment", "subscription") NOT NULL,
				object_id VARCHAR(128),
				object_details MEDIUMTEXT,
				testmode BOOLEAN NOT NULL DEFAULT false,
				created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				checkin DATE GENERATED ALWAYS AS (
					CASE WHEN ( JSON_VALID( details ) AND JSON_VALID( JSON_EXTRACT( details, "\$.booking.checkin" ) ) )
						THEN DATE( JSON_EXTRACT( details, "\$.booking.checkin" ) )
						ELSE NULL END
				) VIRTUAL,
				checkout DATE GENERATED ALWAYS AS (
					CASE WHEN ( JSON_VALID( details ) AND JSON_VALID( JSON_EXTRACT( details, "\$.booking.checkout" ) ) )
						THEN DATE( JSON_EXTRACT( details, "\$.booking.checkout" ) )
						ELSE NULL END
				) VIRTUAL,
				timeslot VARCHAR(32) GENERATED ALWAYS AS (
					CASE WHEN ( JSON_VALID( details ) AND JSON_VALID( JSON_EXTRACT( details, "\$.booking.timeslot" ) ) )
						THEN CONCAT_WS(
							'-',
							JSON_UNQUOTE( JSON_EXTRACT( details, "\$.booking.timeslot.from" ) ),
							JSON_UNQUOTE( JSON_EXTRACT( details, "\$.booking.timeslot.to" ) )
						)
						ELSE NULL END
				) VIRTUAL,
				PRIMARY KEY (id),
					KEY (post_id),
					KEY (product_type),
					KEY (product_key),
					KEY (customer_id),
					KEY (status),
					KEY (session_id),
					KEY (mode),
					KEY (object_id),
					KEY (testmode),
					KEY (checkin),
					KEY (checkout),
					KEY (timeslot),
					KEY (created_at)
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		// create order notes table
		$table_name = $wpdb->prefix . 'voxel_order_notes';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				order_id BIGINT(20) UNSIGNED NOT NULL,
				type VARCHAR(96) NOT NULL,
				details TEXT,
				created_at DATETIME NOT NULL,
				PRIMARY KEY (id),
					KEY (order_id),
					KEY (type),
					KEY (created_at),
				FOREIGN KEY (order_id)
					REFERENCES {$wpdb->prefix}voxel_orders(id) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		\Voxel\set( 'versions.orders_table', $table_version );
	}

	protected function create_timeline_table() {
		$table_version = '0.20';
		$current_version = \Voxel\get( 'versions.timeline_table' );
		if ( $table_version === $current_version ) {
			return;
		}

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// create statuses table
		$table_name = $wpdb->prefix . 'voxel_timeline';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				`user_id` BIGINT(20) UNSIGNED,
				`published_as` BIGINT(20) UNSIGNED,
				`post_id` BIGINT(20) UNSIGNED,
				`content` TEXT,
				`details` TEXT,
				`review_score` DECIMAL(3,2),
				`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`edited_at` DATETIME,
				PRIMARY KEY (`id`),
					KEY (`user_id`),
					KEY (`post_id`),
					KEY (`published_as`),
					FULLTEXT (`content`),
					KEY (`review_score`),
					KEY (`created_at`),
				FOREIGN KEY (`user_id`) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`published_as`) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`post_id`) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		// create status likes table
		$table_name = $wpdb->prefix . 'voxel_timeline_likes';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`user_id` BIGINT(20) UNSIGNED NOT NULL,
				`status_id` BIGINT(20) UNSIGNED NOT NULL,
				PRIMARY KEY (`user_id`,`status_id`),
					KEY (`user_id`),
					KEY (`status_id`),
				FOREIGN KEY (`user_id`) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`status_id`) REFERENCES {$wpdb->prefix}voxel_timeline(id) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		// create replies table
		$table_name = $wpdb->prefix . 'voxel_timeline_replies';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				`user_id` BIGINT(20) UNSIGNED,
				`published_as` BIGINT(20) UNSIGNED,
				`status_id` BIGINT(20) UNSIGNED NOT NULL,
				`parent_id` BIGINT(20) UNSIGNED,
				`content` TEXT,
				`details` TEXT,
				`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`edited_at` DATETIME,
				PRIMARY KEY (`id`),
					KEY (`user_id`),
					KEY (`status_id`),
					KEY (`parent_id`),
					FULLTEXT (`content`),
					KEY (`created_at`),
				FOREIGN KEY (`user_id`) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`published_as`) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`status_id`) REFERENCES {$wpdb->prefix}voxel_timeline(id) ON DELETE CASCADE,
				FOREIGN KEY (`parent_id`) REFERENCES {$wpdb->prefix}voxel_timeline_replies(id) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		// create reply likes table
		$table_name = $wpdb->prefix . 'voxel_timeline_reply_likes';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`user_id` BIGINT(20) UNSIGNED NOT NULL,
				`reply_id` BIGINT(20) UNSIGNED NOT NULL,
				PRIMARY KEY (`user_id`,`reply_id`),
					KEY (`user_id`),
					KEY (`reply_id`),
				FOREIGN KEY (`user_id`) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`reply_id`) REFERENCES {$wpdb->prefix}voxel_timeline_replies(id) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		\Voxel\set( 'versions.timeline_table', $table_version );
	}

	protected function create_followers_table() {
		$table_version = '0.1';
		$current_version = \Voxel\get( 'versions.followers_table' );
		if ( $table_version === $current_version ) {
			return;
		}

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// create user followers table
		$table_name = $wpdb->prefix . 'voxel_followers_user';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`user_id` BIGINT(20) UNSIGNED NOT NULL,
				`follower_id` BIGINT(20) UNSIGNED NOT NULL,
				`status` TINYINT NOT NULL,
				PRIMARY KEY (`user_id`,`follower_id`),
					KEY (`user_id`),
					KEY (`follower_id`),
					KEY (`status`),
				FOREIGN KEY (`user_id`) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`follower_id`) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		// create post followers table
		$table_name = $wpdb->prefix . 'voxel_followers_post';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`post_id` BIGINT(20) UNSIGNED NOT NULL,
				`follower_id` BIGINT(20) UNSIGNED NOT NULL,
				`status` TINYINT NOT NULL,
				PRIMARY KEY (`post_id`,`follower_id`),
					KEY (`post_id`),
					KEY (`follower_id`),
					KEY (`status`),
				FOREIGN KEY (`post_id`) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE,
				FOREIGN KEY (`follower_id`) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		\Voxel\set( 'versions.followers_table', $table_version );
	}

	protected function create_work_hours_table() {
		$table_version = '0.1';
		$current_version = \Voxel\get( 'versions.work_hours_table' );
		if ( $table_version === $current_version ) {
			return;
		}

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name = $wpdb->prefix . 'voxel_work_hours';
		$sql = <<<SQL
			CREATE TABLE IF NOT EXISTS $table_name (
				`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				`post_id` BIGINT(20) UNSIGNED NOT NULL,
				`post_type` VARCHAR(64) NOT NULL,
				`field_key` VARCHAR(64) NOT NULL,
				`start` SMALLINT(5) UNSIGNED NOT NULL,
				`end` SMALLINT(5) UNSIGNED NOT NULL,
				PRIMARY KEY (`id`),
					KEY (`post_id`),
					KEY (`post_type`),
					KEY (`field_key`),
					KEY (`start`),
					KEY (`end`),
				FOREIGN KEY (`post_id`) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
			) ENGINE = InnoDB;
		SQL;
		dbDelta( $sql );

		\Voxel\set( 'versions.work_hours_table', $table_version );
	}

	protected function modify_terms_table() {
		$table_version = '0.1';
		$current_version = \Voxel\get( 'versions.terms_table' );
		if ( $table_version === $current_version ) {
			return;
		}

		global $wpdb;

		$order_col_exists = $wpdb->query( "SHOW COLUMNS FROM {$wpdb->terms} LIKE 'voxel_order'" );
		if ( ! $order_col_exists ) {
			$wpdb->query( "ALTER TABLE {$wpdb->terms} ADD COLUMN `voxel_order` INT NOT NULL DEFAULT 0" );
		}

		\Voxel\set( 'versions.terms_table', $table_version );
	}
}
