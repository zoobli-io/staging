<?php

namespace Voxel\Controllers;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Post_Type_Controller extends Base_Controller {

	protected function hooks() {
		$this->on( 'voxel/backend/post-types/screen:edit-type', '@render_edit_screen', 30 );
		$this->on( 'admin_post_voxel_save_post_type_settings', '@save_settings' );
	}

	protected function render_edit_screen() {
		$key = $_GET['post_type'] ?? null;
		$post_type = \Voxel\Post_Type::get( $key );
		if ( ! ( $key && $post_type ) ) {
			return;
		}

		// create templates for post type if they don't exist
		$post_type->get_templates( $create_if_not_exists = true );

		// load required assets
		wp_enqueue_script('vue');
		wp_enqueue_script('sortable');
		wp_enqueue_script('vue-draggable');
		wp_enqueue_script('vx:post-type-editor.js');

		$auto_index = false;
		$indexing = (array) json_decode( get_option(
			sprintf( 'post_type_index:%s', $post_type->get_key() )
		), ARRAY_A );
		if ( in_array( $indexing['status'] ?? null, [ 'needs-processing', 'batch-processed' ], true ) ) {
			$auto_index = true;
		}

		$editor_options = [
			'elementor_edit_link' => admin_url( 'post.php?post={id}&action=elementor' ),
			'field_types' => [],
			'field_presets' => $this->get_field_presets( $post_type ),
			'supported_conditions' => [],
			'filter_types' => [],
			'condition_types' => [],
			'orderby_types' => [],
			'orderby_type_labels' => [],
			'orderby_presets' => $this->get_orderby_presets( $post_type ),
			'auto_index' => $auto_index,
		];

		// fields config
		ob_start();
		foreach ( \Voxel\config('post_types.field_types') as $field_type => $field_class ) {
			$field = new $field_class;
			$field->set_post_type( $post_type );

			printf( '<template v-if="field.type === \'%s\'">', $field->get_type() );
			foreach ( $field->get_models() as $model_key => $model_args ) {
				if ( is_callable( $model_args ) ) {
					$model_args();
				} else {
					if ( is_string( $model_args ) ) {
						\Voxel\log($field_type);
					}
					$model_type = $model_args['type'];
					$model_args['v-model'] = sprintf( 'field[%s]', wp_json_encode( $model_key ) );
					unset( $model_args['type'] );
					$model_type::render( $model_args );
				}
			}
			printf( '</template>' );

			$editor_options['field_types'][ $field->get_type() ] = (object) $field->get_props();
			$editor_options['supported_conditions'][ $field->get_type() ] = $field->get_supported_conditions();
		}
		$field_options_markup = ob_get_clean();

		// filters config
		ob_start();
		foreach ( \Voxel\config('post_types.filter_types') as $filter_type => $filter_class ) {
			$filter = new $filter_class;
			$filter->set_post_type( $post_type );

			printf( '<template v-if="filter.type === \'%s\'">', $filter->get_type() );
			foreach ( $filter->get_models() as $model_key => $model_args ) {
				if ( is_callable( $model_args ) ) {
					$model_args();
				} else {
					$model_type = $model_args['type'];
					$model_args['v-model'] = sprintf( 'filter[%s]', wp_json_encode( $model_key ) );
					unset( $model_args['type'] );
					$model_type::render( $model_args );
				}
			}
			printf( '</template>' );

			$editor_options['filter_types'][ $filter->get_type() ] = (object) $filter->get_props();
		}
		$filter_options_markup = ob_get_clean();

		// orderby config
		ob_start();
		foreach ( \Voxel\config('post_types.orderby_types') as $orderby_type => $orderby_class ) {
			$orderby = new $orderby_class;
			$orderby->set_post_type( $post_type );

			printf( '<template v-if="clause.type === \'%s\'">', $orderby->get_type() );
			foreach ( $orderby->get_models() as $model_key => $model_args ) {
				if ( is_callable( $model_args ) ) {
					$model_args();
				} else {
					$model_type = $model_args['type'];
					$model_args['v-model'] = sprintf( 'clause[%s]', wp_json_encode( $model_key ) );
					unset( $model_args['type'] );
					$model_type::render( $model_args );
				}
			}
			printf( '</template>' );

			$editor_options['orderby_types'][ $orderby->get_type() ] = (object) $orderby->get_props();
			$editor_options['orderby_type_labels'][ $orderby->get_type() ] = $orderby->get_label();
		}
		$orderby_options_markup = ob_get_clean();

		// conditions config
		ob_start();
		foreach ( \Voxel\config('post_types.condition_types') as $condition_type => $condition_class ) {
			$condition = new $condition_class;

			printf( '<template v-if="condition.type === \'%s\'">', $condition->get_type() );
			foreach ( $condition->get_models() as $model_key => $model_args ) {
				if ( is_callable( $model_args ) ) {
					$model_args();
				} else {
					$model_type = $model_args['type'];
					$model_args['v-model'] = sprintf( 'condition[%s]', wp_json_encode( $model_key ) );
					unset( $model_args['type'] );
					$model_type::render( $model_args );
				}
			}
			printf( '</template>' );

			$editor_options['condition_types'][ $condition->get_type() ] = [
				'props' => $condition->get_props(),
				'label' => $condition->get_label(),
				'type' => $condition->get_type(),
				'group' => $condition->get_group(),
			];
		}
		$condition_options_markup = ob_get_clean();

		$editor_options['product_types'] = [];
		foreach ( \Voxel\Product_Type::get_all() as $product_type ) {
			$editor_options['product_types'][ $product_type->get_key() ] = [
				'key' => $product_type->get_key(),
				'label' => $product_type->get_label(),
				'calendar_type' => $product_type->config('calendar.type'),
				'additions' => array_values( array_map( function( $addition ) {
					return $addition->get_props();
				}, $product_type->get_additions() ) ),
			];
		}

		// dd($editor_options);

		// general editor config
		printf( '<script type="text/javascript">window.Post_Type_Options = %s;</script>', wp_json_encode( (object) $editor_options ) );

		// post type config
		printf(
			'<script type="text/javascript">window.Post_Type_Config = %s;</script>',
			wp_json_encode( (object) $post_type->repository->get_editor_config() )
		);

		require locate_template( 'templates/backend/post-types/edit-post-type.php' );
	}

	protected function save_settings() {
		check_admin_referer( 'voxel_save_post_type_settings' );
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		if ( empty( $_POST['post_type_config'] ) ) {
			die;
		}

		$config = json_decode( stripslashes( $_POST['post_type_config'] ), true );
		$settings = $config['settings'];
		$post_type = \Voxel\Post_Type::get( $settings['key'] );
		if ( ! ( $settings['key'] && $post_type && json_last_error() === JSON_ERROR_NONE ) ) {
			die;
		}

		// delete post type
		if ( ! empty( $_POST['remove_post_type'] ) && $_POST['remove_post_type'] === 'yes' ) {
			$post_type->repository->remove();

			wp_safe_redirect( admin_url( 'admin.php?page=voxel-post-types' ) );
			die;
		}

		$previous_index_table_sql = $post_type->get_index_table()->get_sql();

		// edit post type
		$post_type->repository->set_config( [
			'settings' => $config['settings'],
			'fields' => $config['fields'],
			'search' => $config['search'],
			'templates' => $config['templates'],
		] );

		$post_type = \Voxel\Post_Type::force_get( $post_type->get_key() );
		$index_table = $post_type->get_index_table();
		$new_index_table_sql = $index_table->get_sql();

		if ( $previous_index_table_sql !== $new_index_table_sql || ! $index_table->exists() ) {
			$index_table->recreate();
			update_option( sprintf( 'post_type_index:%s', $post_type->get_key() ), wp_json_encode( [
				'status' => 'needs-processing',
				'offset' => 0,
			] ) );
		}

		wp_safe_redirect( add_query_arg( 'tab', $_POST['active_tab'] ?? null, $post_type->get_edit_link() ) );
		die;
	}

	private function get_orderby_presets( \Voxel\Post_Type $post_type ) {
		return [
			'best-rated' => \Voxel\Post_Types\Order_By\Order_By_Group::preset( [
				'key' => 'best-rated',
				'label' => 'Best rated',
				'clauses' => [
					\Voxel\Post_Types\Order_By\Rating_Order::preset(),
				],
			], $post_type ),

			'relevance' => \Voxel\Post_Types\Order_By\Order_By_Group::preset( [
				'key' => 'relevance',
				'label' => 'Relevant',
				'clauses' => [
					\Voxel\Post_Types\Order_By\Relevance_Order::preset( [ 'source' => 'keywords' ] ),
				],
			], $post_type ),

			'nearby' => \Voxel\Post_Types\Order_By\Order_By_Group::preset( [
				'key' => 'nearby',
				'label' => 'Nearby',
				'clauses' => [
					\Voxel\Post_Types\Order_By\Nearby_Order::preset( [ 'source' => 'location' ] ),
				],
			], $post_type ),

			'priority' => \Voxel\Post_Types\Order_By\Order_By_Group::preset( [
				'key' => 'priority',
				'label' => 'Priority',
				'clauses' => [
					\Voxel\Post_Types\Order_By\Priority_Order::preset(),
				],
			], $post_type ),

			'alphabetical' => \Voxel\Post_Types\Order_By\Order_By_Group::preset( [
				'key' => 'alphabetical',
				'label' => 'Alphabetical',
				'clauses' => [
					\Voxel\Post_Types\Order_By\Text_Field_Order::preset( [ 'source' => 'title' ] ),
				],
			], $post_type ),

			'random' => \Voxel\Post_Types\Order_By\Order_By_Group::preset( [
				'key' => 'random',
				'label' => 'Random',
				'clauses' => [
					\Voxel\Post_Types\Order_By\Random_Order::preset( [ 'seed' => 10800 ] ),
				],
			], $post_type ),
		];
	}

	private function get_field_presets( $post_type ) {
		$presets = [
			\Voxel\Post_Types\Fields\Singular\Title_Field::preset(),
			\Voxel\Post_Types\Fields\Singular\Description_Field::preset(),
			\Voxel\Post_Types\Fields\Singular\Timezone_Field::preset(),
			\Voxel\Post_Types\Fields\Location_Field::preset( [
				'label' => 'Location',
				'key' => 'location',
			] ),
			\Voxel\Post_Types\Fields\Email_Field::preset( [
				'label' => 'Email',
				'key' => 'email',
			] ),
			\Voxel\Post_Types\Fields\Image_Field::preset( [
				'label' => 'Logo',
				'key' => 'logo',
				'max-count' => 1,
			] ),
			\Voxel\Post_Types\Fields\Image_Field::preset( [
				'label' => 'Cover image',
				'key' => 'cover',
				'max-count' => 1,
			] ),
			\Voxel\Post_Types\Fields\Image_Field::preset( [
				'label' => 'Gallery',
				'key' => 'gallery',
				'max-count' => 10,
			] ),
			\Voxel\Post_Types\Fields\Url_Field::preset( [
				'label' => 'Website',
				'key' => 'website',
			] ),
			\Voxel\Post_Types\Fields\Phone_Field::preset( [
				'label' => 'Phone number',
				'key' => 'phone',
			] ),
			\Voxel\Post_Types\Fields\Recurring_Date_Field::preset( [
				'label' => 'Event date',
				'key' => 'event_date',
			] ),
			\Voxel\Post_Types\Fields\Work_Hours_Field::preset( [
				'label' => 'Work hours',
				'key' => 'work_hours',
			] ),
		];

		if ( $post_type->get_key() === 'profile' ) {
			$presets[] = \Voxel\Post_Types\Fields\Profile\Profile_Avatar_Field::preset();
			$presets[] = \Voxel\Post_Types\Fields\Profile\Profile_Name_Field::preset();
		}

		return $presets;
	}
}
