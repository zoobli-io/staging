<?php

namespace Voxel\Controllers\Elementor;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Loop_Controller extends \Voxel\Controllers\Base_Controller {

	protected $looped_elements = [];

	protected function hooks() {
		$this->on( 'elementor/element/common/_section_style/after_section_end', '@register_loop_settings', 100 );
		$this->on( 'elementor/element/section/section_advanced/after_section_end', '@register_loop_settings', 100 );
		$this->on( 'elementor/element/column/section_advanced/after_section_end', '@register_loop_settings', 100 );
		$this->on( 'elementor/element/container/section_layout/after_section_end', '@register_loop_settings', 100 );

		$this->on( 'elementor/controls/controls_registered', '@add_repeater_loop_setting', 1005 );

		foreach ( [ 'widget', 'column', 'section', 'container' ] as $element_type ) {
			$this->on( sprintf( 'elementor/frontend/%s/before_render', $element_type ), '@run_loops', 100 );
			$this->on( sprintf( 'elementor/frontend/%s/should_render', $element_type ), '@should_render', 100, 2 );
		}
	}

	protected function register_loop_settings( $element ) {
		$element->start_controls_section( '_voxel_loop_settings', [
			'label' => __( 'Loop element', 'voxel' ),
			'tab' => 'tab_voxel',
		] );

		$element->add_control( '_voxel_loop', [
			'label' => __( 'Loop this element based on', 'voxel' ),
			'label_block' => true,
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => static::_get_loopable_properties(),
		] );

		$element->end_controls_section();
	}

	protected function add_repeater_loop_setting( $controls_manager ) {
		if ( $loop_options = static::_get_loopable_properties() ) {
			$repeater = $controls_manager->get_control('repeater');
			$fields = $repeater->get_settings('fields');
			$fields[ '_voxel_loop' ] = [
				'name' => '_voxel_loop',
				'type' => 'select',
				'label' => 'Loop repeater row',
				'default' => '',
				'options' => $loop_options,
			];
			$repeater->set_settings( 'fields', $fields );
		}
	}

	public function run_loops( $element ) {
		$loopable = $element->get_settings('_voxel_loop');
		if ( empty( $loopable ) ) {
			return;
		}

		if ( \Voxel\Dynamic_Tags\Loop::is_running( $loopable ) ) {
			unset( $this->looped_elements[ $element->get_id() ] );
			return;
		}

		\Voxel\measure_start( 'handle-element-loops' );

		\Voxel\Dynamic_Tags\Loop::run( $loopable, function() use ( $element ) {
			$classname = get_class( $element );
			$loop_element = new $classname( $element->get_data(), [] );
			$loop_element->print_element();
		} );

		$this->looped_elements[ $element->get_id() ] = true;

		\Voxel\measure_end( 'handle-element-loops' );
	}

	protected function should_render( $should_render, $element ) {
		if ( isset( $this->looped_elements[ $element->get_id() ] ) ) {
			return false;
		}

		return $should_render;
	}

	private static function _get_loopable_properties() {
		static $loopables;
		if ( ! is_null( $loopables ) ) {
			return $loopables;
		}

		$loopables = [
			'' => 'No loop',
		];

		$groups = \Voxel\config('dynamic_tags.groups');
		foreach ( $groups as $group_class ) {
			$group = new $group_class;
			static::_find_loopables( $group->get_properties(), $loopables, 0, $group->get_key() );
		}

		if ( count( $loopables ) === 1 ) {
			$loopables = [
				'' => 'No loopable properties available',
			];
		}

		return $loopables;
	}

	private static function _find_loopables( $properties, &$loopables, $depth, $group_key, $path = [] ) {
		foreach ( $properties as $key => $property ) {
			if ( ! empty( $property['loopable'] ) ) {
				$path_string = ! empty( $path ) ? join( '.', $path ).'.' : '';
				$tag = sprintf( '@%s(%s%s)', $group_key, $path_string, $key );
				$loopables[ $tag ] = str_repeat( ' - ', $depth ).$property['label'];
			}

			if ( isset( $property['properties'] ) ) {
				$_path = $path;
				$_path[] = $key;
				static::_find_loopables(
					$property['properties'], $loopables, $depth + 1, $group_key, $_path
				);
			}
		}
	}
}
