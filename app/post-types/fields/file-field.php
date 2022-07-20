<?php

namespace Voxel\Post_Types\Fields;

use \Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class File_Field extends Base_Post_Field {
	use \Voxel\Object_Fields\File_Field_Trait;

	protected $supported_conditions = ['file'];

	protected $props = [
		'type' => 'file',
		'label' => 'File',
		'max-count' => 1,
		'max-size' => 2000,
		'allowed-types' => [],
	];

	public function sanitize( $value ) {
		$files = [];
		$uploads = \Voxel\Utils\File_Uploader::prepare( $this->get_id(), $_FILES['files'] ?? [] );
		$upload_index = 0;

		foreach ( (array) $value as $file ) {
			if ( $file === 'uploaded_file' ) {
				$files[] = [
					'source' => 'new_upload',
					'data' => $uploads[ $upload_index ],
				];

				$upload_index++;
			} elseif ( is_numeric( $file ) ) {
				$files[] = [
					'source' => 'existing',
					'file_id' => absint( $file ),
				];
			}
		}

		return $files;
	}

	public function update( $value ): void {
		$file_ids = $this->_prepare_ids_from_sanitized_input( $value, [
			'post_parent' => $this->post->get_id(),
		] );

		if ( empty( $file_ids ) ) {
			delete_post_meta( $this->post->get_id(), $this->get_key() );
		} else {
			update_post_meta( $this->post->get_id(), $this->get_key(), join( ',', $file_ids ) );
		}
	}

	public function update_value_in_repeater( $value ) {
		$file_ids = $this->_prepare_ids_from_sanitized_input( $value, [
			'post_parent' => $this->post->get_id(),
		] );

		return ! empty( $file_ids ) ? $file_ids : null;
	}

	public function get_value_from_post() {
		$meta_value = get_post_meta( $this->post->get_id(), $this->get_key(), true );
		$ids = explode( ',', $meta_value );
		$ids = array_filter( array_map( 'absint', $ids ) );
		return $ids;
	}

	protected function editing_value() {
		if ( ! $this->post ) {
			return [];
		}

		$ids = $this->get_value();
		if ( $ids === null ) {
			return [];
		}

		$config = [];

		foreach ( $ids as $attachment_id ) {
			if ( $attachment = get_post( $attachment_id ) ) {
				$config[] = [
					'source' => 'existing',
					'id' => $attachment->ID,
					'name' => wp_basename( get_attached_file( $attachment->ID ) ),
					'type' => $attachment->post_mime_type,
					'preview' => wp_get_attachment_image_url( $attachment->ID, 'medium' ),
				];
			}
		}

		return $config;
	}

	public function exports() {
		return [
			'label' => $this->get_label(),
			'type' => \Voxel\T_OBJECT,
			'loopable' => true,
			'loopcount' => function() {
				return count( $this->get_value() );
			},
			'properties' => [
				'id' => [
					'label' => 'File ID',
					'type' => \Voxel\T_NUMBER,
					'callback' => function( $index ) {
						$value = (array) $this->get_value();
						return $value[ $index ] ?? null;
					},
				],
				'url' => [
					'label' => 'File URL',
					'type' => \Voxel\T_URL,
					'callback' => function( $index ) {
						$value = (array) $this->get_value();
						return wp_get_attachment_url( $value[ $index ] ?? null ) ?: null;
					},
				],
				'name' => [
					'label' => 'File Name',
					'type' => \Voxel\T_STRING,
					'callback' => function( $index ) {
						$value = (array) $this->get_value();
						$attachment = get_post( $value[ $index ] ?? null );
						return $attachment ? wp_basename( get_attached_file( $attachment->ID ) ) : null;
					},
				],
				'ids' => [
					'label' => 'All ids',
					'type' => \Voxel\T_STRING,
					'callback' => function() {
						$value = (array) $this->get_value();
						return join( ',', $value );
					},
				],
			],
		];
	}
}
