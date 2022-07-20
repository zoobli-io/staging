<?php

namespace Voxel\Controllers\Onboarding;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Demo_Import_Controller extends \Voxel\Controllers\Base_Controller {

	protected function authorize() {
		return current_user_can( 'administrator' );
	}

	protected function hooks() {
		$this->on( 'voxel_ajax_onboarding.import_demo', '@import_demo' );
	}

	protected function import_demo() {
		// \Voxel\verify_nonce( $_REQUEST['_wpnonce'] ?? '', 'vx_admin_onboarding' ); // @todo
		if ( isset( $_GET['step'] ) ) {
			$state = [
				'step' => sanitize_text_field( $_GET['step'] ),
				'status' => 'starting',
			];
			\Voxel\set( 'demo_import', $state );
		} else {
			$state = \Voxel\get( 'demo_import', [] );
			if ( empty( $state ) ) {
				$state = [
					'step' => 'download_package',
					'status' => 'starting',
				];
				\Voxel\set( 'demo_import', $state );
			}
		}

		$steps = [
			[
				'key' => 'download_package',
				'callback' => [ $this, 'download_package' ],
			],
			[
				'key' => 'unzip_package',
				'callback' => [ $this, 'unzip_package' ],
			],
			[
				'key' => 'import_media',
				'callback' => [ $this, 'import_media' ],
			],
			[
				'key' => 'generate_attachments',
				'callback' => [ $this, 'generate_attachments' ],
			],
			[
				'key' => 'import_site_config',
				'callback' => [ $this, 'import_site_config' ],
			],
			[
				'key' => 'import_terms',
				'callback' => [ $this, 'import_terms' ],
			],
			[
				'key' => 'import_posts',
				'callback' => [ $this, 'import_posts' ],
			],
			[
				'key' => 'import_post_layouts',
				'callback' => [ $this, 'import_post_layouts' ],
			],
			[
				'key' => 'import_menus',
				'callback' => [ $this, 'import_menus' ],
			],
			[
				'key' => 'map_data',
				'callback' => [ $this, 'map_data' ],
			],
			[
				'key' => 'finish_import',
				'callback' => [ $this, 'finish_import' ],
			],
		];

		$step = null;
		$step_index = null;
		foreach ( $steps as $i => $s ) {
			if ( $s['key'] === $state['step'] ) {
				$step = $s;
				$step_index = $i;
				break;
			}
		}

		if ( $step === null ) {
			return wp_send_json( [
				'success' => false,
			] );
		}

		if ( $state['status'] === 'starting' || $state['status'] === 'in_progress' ) {
			call_user_func( $step['callback'] );
		} else {
			if ( $step['key'] !== 'finish_import' ) {
				call_user_func( $steps[ $step_index + 1 ]['callback'] );
			}
		}

		$steps[ $state['step'] ]();
	}

	protected function download_package() {
		try {
			set_time_limit(300);

			require_once ABSPATH.'wp-admin/includes/file.php';
			$download_to = \Voxel\uploads_dir('voxel-demo.zip');

			// if another import file has been downloaded previously, remove it
			\Voxel\delete_directory( \Voxel\uploads_dir('voxel-demo/') );
			@unlink( $download_to );

			$request_url = add_query_arg( [
				'action' => 'voxel_licenses.verify',
				'mode' => 'installing',
				'environment' => \Voxel\get( 'license.env' ),
				'license_key' => \Voxel\get( 'license.key' ),
				'site_url' => home_url('/'),
			], 'https://getvoxel.io/?vx=1' );

			$request = wp_remote_get( $request_url, [
				'timeout' => 10,
			] );

			$response = (array) json_decode( wp_remote_retrieve_body( $request ) );
			if ( ! isset( $response['success'] ) ) {
				throw new \Exception( 'Verification request failed, please try again.' );
			}

			if ( ! ( ( $response['success'] ?? false ) && ( $response['package_url'] ?? null ) ) ) {
				throw new \Exception( $response['message'] ?? 'Could not download package, please try again.' );
			}

			// download package
			$package_url = $response['package_url'];
			$download_file = download_url( $package_url, $timeout = 600 );
			if ( is_wp_error( $download_file ) ) {
				throw new \Exception( 'Couldn\'t download demo: '.$download_file->get_error_message() );
			}

			@copy( $download_file, $download_to );
			unlink( $download_file );

			\Voxel\set( 'demo_import', [
				'step' => 'download_package',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Unpacking...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function unzip_package() {
		try {
			require_once ABSPATH.'wp-admin/includes/file.php';
			WP_Filesystem();

			$package = \Voxel\uploads_dir('voxel-demo.zip');
			$unzip_to = \Voxel\uploads_dir('/');

			$result = unzip_file( $package, $unzip_to );
			if ( is_wp_error( $result ) ) {
				throw new \Exception( 'Unpacking failed: '.$result->get_error_message() );
			}

			// zip file is no longer needed
			@unlink( $package );

			\Voxel\set( 'demo_import', [
				'step' => 'unzip_package',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Importing media...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function import_media() {
		try {
			require_once ABSPATH.'wp-admin/includes/file.php';
			require_once ABSPATH.'wp-admin/includes/media.php';
			require_once ABSPATH.'wp-admin/includes/image.php';

			$files_dir = \Voxel\uploads_dir('voxel-demo/files/');
			$files = array_diff( scandir( $files_dir ), ['.', '..'] );
			$attachments_ids = [];

			// allow svgs
			add_filter( 'upload_mimes', function( $mimes ) {
				$mimes['svg'] = 'image/svg+xml';
				return $mimes;
			} );

			foreach ( $files as $filename ) {
				$filepath = $files_dir.$filename;
				$upload = wp_upload_bits( $filename, null, file_get_contents( $filepath ) );
				if ( ! empty( $upload['error'] ) ) {
					// @todo: log error mesage
					continue;
				}

				// create attachment
				$attachment_id = wp_insert_attachment( [
					'post_title' => pathinfo( $upload['file'], PATHINFO_FILENAME ),
					'guid' => $upload['url'],
					'post_mime_type' => $upload['type'],
					'post_status' => 'inherit',
				], $upload['file'] );

				if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
					continue;
				}

				// store to generate attachment details and sizes later
				$attachments_ids[] = $attachment_id;

				// set temporary postmeta to identify this file in other import steps
				update_post_meta( $attachment_id, '__demo_import_postid', $filename );
			}

			update_option( '__demo_import_generate_attachments', wp_json_encode( $attachments_ids ) );

			\Voxel\set( 'demo_import', [
				'step' => 'import_media',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Generating attachments...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function generate_attachments() {
		try {
			include_once ABSPATH.'wp-admin/includes/file.php';
			include_once ABSPATH.'wp-admin/includes/media.php';
			include_once ABSPATH.'wp-admin/includes/image.php';

			// allow svgs
			add_filter( 'upload_mimes', function( $mimes ) {
				$mimes['svg'] = 'image/svg+xml';
				return $mimes;
			} );

			$attachments = (array) json_decode( get_option( '__demo_import_generate_attachments' ) );
			$batch_size = apply_filters( 'voxel/demo-import/media-batch-size', 3 );
			$process = array_slice( $attachments, 0, $batch_size );

			foreach ( $process as $attachment_id ) {
				// generate attachment details and sizes
				wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata(
					$attachment_id,
					get_attached_file( $attachment_id )
				) );
			}

			// store the remaining items in wp_options and repeat import step
			$remaining = array_slice( $attachments, $batch_size );
			if ( ! empty( $remaining ) ) {
				update_option( '__demo_import_generate_attachments', wp_json_encode( $remaining ) );

				\Voxel\set( 'demo_import', [
					'step' => 'generate_attachments',
					'status' => 'in_progress',
				] );

				return wp_send_json( [
					'success' => true,
					'message' => sprintf( 'Generating attachments (%d remaining)', count( $remaining ) ),
				] );
			}

			// all files are processed, clean up db and go to next step
			delete_option( '__demo_import_generate_attachments' );

			\Voxel\set( 'demo_import', [
				'step' => 'generate_attachments',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Importing site options...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function import_site_config() {
		try {
			$config = $this->_load_config_file( 'site_config.json' );

			if ( isset( $config['permalinks'] ) && is_array( $config['permalinks'] ) ) {
				if ( ! empty( $config['permalinks']['wordpress'] ) ) {
					update_option( 'permalink_structure', $config['permalinks']['wordpress'] );
				}
			}

			// import elementor settings
			if ( isset( $config['elementor'] ) && is_array( $config['elementor'] ) ) {
				foreach ( $config['elementor'] as $option_key => $option_value ) {
					if ( ! empty( $option_key ) ) {
						update_option( $option_key, $option_value );
					}
				}
			}

			if ( ! empty( $config['active_kit'] ) ) {
				$active_kit = get_option( 'elementor_active_kit' );
				foreach ( $config['active_kit'] as $meta_key => $meta_value ) {
					update_post_meta( $active_kit, $meta_key, $meta_value );
				}
			}

			if ( ! empty( $config['admin_profile_id'] ) ) {
				update_user_meta( \Voxel\current_user()->get_id(), 'voxel:profile_id', (int) $config['admin_profile_id'] );
			}

			// import wordpress settings
			if ( isset( $config['wordpress'] ) && is_array( $config['wordpress'] ) ) {
				if ( ! empty( $config['wordpress']['show_on_front'] ) ) {
					update_option( 'show_on_front', $config['wordpress']['show_on_front'] );
				}

				if ( ! empty( $config['wordpress']['page_on_front'] ) ) {
					update_option( '__page_on_front', 'map:'.$config['wordpress']['page_on_front'] );
				}

				if ( ! empty( $config['wordpress']['page_for_posts'] ) ) {
					update_option( '__page_for_posts', 'map:'.$config['wordpress']['page_for_posts'] );
				}
			}

			// post types
			$existing = \Voxel\get( 'post_types', [] );
			$post_types = $this->_load_config_file( 'post_types.json' );
			foreach ( $post_types as $key => $config ) {
				$existing[ $key ] = $config;
			}
			\Voxel\set( 'post_types', $existing );

			// taxonomies
			$existing = \Voxel\get( 'taxonomies', [] );
			$taxonomies = $this->_load_config_file( 'taxonomies.json' );
			foreach ( $taxonomies as $key => $config ) {
				$existing[ $key ] = $config;
			}
			\Voxel\set( 'taxonomies', $existing );

			// templates
			$templates = $this->_load_config_file( 'templates.json' );
			\Voxel\set( 'templates', $templates );

			// product types
			$existing = \Voxel\get( 'product_types', [] );
			$product_types = $this->_load_config_file( 'product_types.json' );
			foreach ( $product_types as $key => $config ) {
				$existing[ $key ] = $config;
			}
			\Voxel\set( 'product_types', $product_types );

			// plans
			$plans = $this->_load_config_file( 'plans.json' );
			\Voxel\set( 'plans', $plans );

			\Voxel\set( 'demo_import', [
				'step' => 'import_site_config',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Importing terms...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function import_terms() {
		try {
			$config = $this->_load_config_file('terms.json');
			foreach ( $config as $item ) {
				if ( term_exists( $item['slug'] ) ) {
					continue;
				}

				$term_ids = wp_insert_term( $item['name'], $item['taxonomy'], [
					'description' => $item['description'],
					'slug' => $item['slug'],
				] );

				if ( is_wp_error( $term_ids ) ) {
					continue;
				}

				$term_id = $term_ids['term_id'];
				update_term_meta( $term_id, '__demo_import_termid', $item['id'] );

				if ( ! empty( $item['icon'] ) ) {
					update_term_meta( $term_id, 'voxel_icon', $item['icon'] );
				}

				if ( ! empty( $item['image'] ) ) {
					update_term_meta( $term_id, 'voxel_image', \Voxel\get_imported_post_id( $item['image'] ) );
				}
			}

			\Voxel\set( 'demo_import', [
				'step' => 'import_terms',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Importing posts...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function import_posts() {
		try {
			$config = $this->_load_config_file('posts.json');
			foreach ( $config as $item ) {
				$post_id = wp_insert_post( [
					'post_type' => $item['post_type'],
					'post_title' => $item['title'],
					'post_content' => \Voxel\import_post_content( $item['content'] ),
					'post_status' => 'publish',
					'post_name' => $item['slug'],
					'meta_input' => [
						'__demo_import_postid' => $item['id'],
					],
				], true );

				if ( is_wp_error( $post_id ) ) {
					continue;
				}

				$post = \Voxel\Post::get( $post_id );

				if ( $item['is-elementor'] ) {
					update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
					update_post_meta( $post_id, '_elementor_page_settings', $item['_elementor_page_settings'] );
					update_post_meta( $post_id, '_voxel_page_settings', $item['_voxel_page_settings'] );

					if ( ! empty( $item['_elementor_data'] ) ) {
						update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $item['_elementor_data'] ) ) );
					}
				}

				foreach ( $item['fields'] as $key => $value ) {
					$field = $post->get_field( $key );
					if ( ! $field ) {
						continue;
					}

					if ( in_array( $field->get_type(), [ 'file', 'image', 'profile-avatar' ], true ) ) {
						$value = array_map( function( $attachment_id ) {
							return [
								'source' => 'existing',
								'file_id' => (int) \Voxel\get_imported_post_id( $attachment_id ),
							];
						}, (array) $value );
					}

					$field->update( $value );
				}
			}

			\Voxel\set( 'demo_import', [
				'step' => 'import_posts',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Importing layouts...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function import_post_layouts() {
		try {
			global $wpdb;

			$results = $wpdb->get_results( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_data'" );

			foreach ( $results as $result ) {
				$data = \Voxel\import_elementor_data( $result->meta_value );
				update_post_meta( $result->post_id, '_elementor_data', wp_slash( $data ) );
			}

			\Voxel\set( 'demo_import', [
				'step' => 'import_post_layouts',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Importing menus...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function import_menus() {
		try {
			$config = $this->_load_config_file('menus.json');
			$locations = [];
			foreach ( $config as $location ) {
				$term_ids = wp_insert_term( $location['name'], 'nav_menu', [
					'slug' => $location['slug'],
				] );

				if ( is_wp_error( $term_ids ) ) {
					continue;
				}

				$term_id = $term_ids['term_id'];
				$locations[ $location['location'] ] = $term_id;

				// insert nav menu items in wp_posts
				foreach ( $location['items'] as $item ) {
					$post_id = wp_insert_post( [
						'post_type' => 'nav_menu_item',
						'post_title' => $item['title'],
						'post_status' => 'publish',
						'menu_order' => $item['menu_order'],
						'meta_input' => [
							'_menu_item_menu_item_parent' => $item['meta']['menu_item_menu_item_parent'],
							'_menu_item_type' => $item['meta']['menu_item_type'],
							'_menu_item_object' => $item['meta']['menu_item_object'],
							'_menu_item_object_id' => $item['meta']['menu_item_object_id'],
							'_menu_item_url' => str_replace(
								'<<#siteurl#>>',
								untrailingslashit( site_url() ),
								$item['meta']['menu_item_url']
							),
							'__demo_import_postid' => $item['id'],
							'_voxel_item_icon' => $item['meta']['_voxel_item_icon'],
							'_voxel_item_label' => $item['meta']['_voxel_item_label'],
							'_voxel_item_url' => $item['meta']['_voxel_item_url'],
						],
					], true );

					// attach nav menu item post to the menu term
					if ( ! is_wp_error( $post_id ) ) {
						wp_set_object_terms( $post_id, $term_id, 'nav_menu' );
					}
				}
			}

			// configure nav menu locations
			if ( ! empty( $locations ) ) {
				set_theme_mod( 'nav_menu_locations', $locations );
			}

			\Voxel\set( 'demo_import', [
				'step' => 'import_menus',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Mapping data...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function map_data() {
		try {
			$this->_map_templates();
			$this->_map_post_ids();
			$this->_map_menu_items();

			\Voxel\set( 'demo_import', [
				'step' => 'map_data',
				'status' => 'done',
			] );

			return wp_send_json( [
				'success' => true,
				'message' => 'Finishing up...'
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function _map_templates() {
		$templates = \Voxel\get( 'templates', [] );
		foreach ( $templates as $key => $id ) {
			$templates[ $key ] = \Voxel\get_imported_post_id( $id );
		}

		// dd($templates, \Voxel\get( 'templates', [] ));
		\Voxel\set( 'templates', $templates );

		$post_types = \Voxel\get( 'post_types', [] );
		foreach ( $post_types as $key => $post_type ) {
			foreach ( ( $post_type['templates'] ?? [] ) as $location => $template_id ) {
				$post_types[ $key ]['templates'][ $location ] = \Voxel\get_imported_post_id( $template_id );
			}

			foreach ( $post_type['fields'] as $field_key => $field ) {
				if ( in_array( $field['type'], [ 'image', 'profile-avatar' ], true ) ) {
					if ( ! empty( $field['default'] ) ) {
						$post_types[ $key ]['fields'][ $field_key ]['default'] = \Voxel\get_imported_post_id( $field['default'] );
					}
				}

				if ( $field['type'] === 'ui-image' ) {
					if ( ! empty( $field['image'] ) ) {
						$post_types[ $key ]['fields'][ $field_key ]['image'] = \Voxel\get_imported_post_id( $field['image'] );
					}
				}
			}
		}
		\Voxel\set( 'post_types', $post_types );

		$taxonomies = \Voxel\get( 'taxonomies', [] );
		foreach ( $taxonomies as $key => $taxonomy ) {
			foreach ( ( $taxonomy['templates'] ?? [] ) as $location => $template_id ) {
				$taxonomies[ $key ]['templates'][ $location ] = \Voxel\get_imported_post_id( $template_id );
			}
		}
		\Voxel\set( 'taxonomies', $taxonomies );

		$profile_id = \Voxel\get_imported_post_id( \Voxel\current_user()->get_profile_id() );
		update_user_meta( \Voxel\current_user()->get_id(), 'voxel:profile_id', $profile_id );
	}

	protected function _map_post_ids() {
		$options = [
			'__page_on_front',
			'__page_for_posts',
		];

		foreach ( $options as $option_key ) {
			$old_page_id = str_replace( 'map:', '', get_option( $option_key, '' ) );
			if ( ! empty( $old_page_id ) && ( $new_page_id = \Voxel\get_imported_post_id( $old_page_id ) ) ) {
				update_option( $option_key, $new_page_id );
			} else {
				delete_option( $option_key );
			}
		}

		update_option( 'page_on_front', get_option( '__page_on_front' ) );
		update_option( 'page_for_posts', get_option( '__page_for_posts' ) );
		delete_option( '__page_on_front' );
		delete_option( '__page_for_posts' );
	}

	protected function _map_menu_items() {
		$nav_menu_ids = get_posts( [
			'post_type' => 'nav_menu_item',
			'post_status' => 'publish',
			'fields' => 'ids',
			'posts_per_page' => -1,
			'meta_query' => [ [
				'key' => '__demo_import_postid',
				'compare' => 'EXISTS',
			] ],
		] );

		foreach ( $nav_menu_ids as $nav_menu_id ) {
			// when "_menu_item_type" is set to "post_type", map "_menu_item_object_id" to the imported post id
			// @todo: handle the case when "_menu_item_type" is "taxonomy"
			$object_type = get_post_meta( $nav_menu_id, '_menu_item_type', true );
			$object_id = get_post_meta( $nav_menu_id, '_menu_item_object_id', true );
			if ( $object_type === 'post_type' && $object_id && ( $post_id = \Voxel\get_imported_post_id( $object_id ) ) ) {
				update_post_meta( $nav_menu_id, '_menu_item_object_id', $post_id );
			}

			// map "_menu_item_menu_item_parent" to the imported nav_menu_item id
			$parent_id = get_post_meta( $nav_menu_id, '_menu_item_menu_item_parent', true );
			if ( $parent_id && ( $post_id = \Voxel\get_imported_post_id( $parent_id ) ) ) {
				update_post_meta( $nav_menu_id, '_menu_item_menu_item_parent', $post_id );
			}
		}
	}

	protected function finish_import() {
		try {
			$this->_regenerate_css();
			$this->_cleanup_database();
			$this->_cleanup_files();
			$this->_index_posts();
			flush_rewrite_rules(true);
			\Voxel\set( 'demo_import', null );
			\Voxel\set( 'onboarding', [ 'done' => true ] );

			return wp_send_json( [
				'success' => true,
				'import_finished' => true,
			] );
		} catch ( \Exception $e ) {
			return wp_send_json( [
				'success' => false,
				'message' => $e->getMessage(),
			] );
		}
	}

	protected function _regenerate_css() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		$elementor = \Elementor\Plugin::$instance;
		if ( is_object( $elementor ) && is_object( $elementor->files_manager ) && method_exists( $elementor->files_manager, 'clear_cache' ) ) {
			$elementor->files_manager->clear_cache();
		}
	}

	protected function _cleanup_database() {
		global $wpdb;

		// postmeta
		$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key IN (
			'__demo_import_postid'
		)" );

		// termmeta
		$wpdb->query( "DELETE FROM {$wpdb->termmeta} WHERE meta_key IN (
			'__demo_import_termid'
		)" );
	}

	protected function _cleanup_files() {
		\Voxel\delete_directory( \Voxel\uploads_dir( 'voxel-demo/' ) );
	}

	protected function _index_posts() {
		global $wpdb;
		foreach ( \Voxel\Post_Type::get_voxel_types() as $post_type ) {
			$index_table = $post_type->get_index_table();
			if ( ! $index_table->exists() ) {
				$post_ids = $wpdb->get_col( $wpdb->prepare(
					"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = 'publish'",
					$post_type->get_key()
				) );

				$index_table->create();
				$index_table->index( $post_ids );
			}
		}
	}

	protected function _load_config_file( $file ) {
		$raw_contents = file_get_contents( \Voxel\uploads_dir( 'voxel-demo/config/'.$file ) );
		$file_contents = json_decode( $raw_contents, ARRAY_A );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			throw new \Exception( 'Could not parse "'.$file.'", invalid file format.' );
		}

		return $file_contents;
	}
}
