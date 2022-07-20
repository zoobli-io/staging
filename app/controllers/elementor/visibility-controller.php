<?php

namespace Voxel\Controllers\Elementor;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Visibility_Controller extends \Voxel\Controllers\Base_Controller {

	protected function hooks() {
		$this->on( 'elementor/element/common/_section_style/after_section_end', '@register_settings', 90 );
		$this->on( 'elementor/element/section/section_advanced/after_section_end', '@register_settings', 90 );
		$this->on( 'elementor/element/column/section_advanced/after_section_end', '@register_settings', 90 );
		$this->on( 'elementor/element/container/section_layout/after_section_end', '@register_settings', 90 );
		$this->on( 'elementor/controls/controls_registered', '@register_settings_in_repeater', 1010 );

		foreach ( [ 'container', 'section', 'column', 'widget' ] as $element_type ) {
			$this->filter( sprintf( 'elementor/frontend/%s/should_render', $element_type ), '@apply_visibility_settings', 1000, 2 );
		}
	}

	protected function register_settings( $element ) {
		$element->start_controls_section( '_voxel_visibility_settings', [
			'label' => __( 'Visibility', 'voxel' ),
			'tab' => 'tab_voxel',
		] );

		$element->add_control( '_voxel_visibility_behavior', [
			'label' => __( 'Element visibility', 'voxel' ),
			'label_block' => true,
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'show',
			'options' => [
				'show' => 'Show this element if',
				'hide' => 'Hide this element if',
			],
		] );

		$element->add_control( '_voxel_visibility_rules', [
			'type' => 'voxel-visibility',
		] );

		$element->end_controls_section();
	}

	protected function apply_visibility_settings( $should_render, $element ) {
		$behavior = $element->get_settings( '_voxel_visibility_behavior' );
		$rules = $element->get_settings( '_voxel_visibility_rules' );

		if ( ! is_array( $rules ) || empty( $rules ) ) {
			return $should_render;
		}

		$rules_passed = \Voxel\evaluate_visibility_rules( $rules );
		if ( $behavior === 'hide' ) {
			return $rules_passed ? false : true;
		} else {
			return $rules_passed ? true : false;
		}
	}

	protected function register_settings_in_repeater( $controls_manager ) {
		$repeater = $controls_manager->get_control('repeater');
		$fields = $repeater->get_settings('fields');
		$fields['_voxel_visibility_behavior'] = [
			'name' => '_voxel_visibility_behavior',
			'type' => 'select',
			'label' => 'Row visibility',
			'default' => 'show',
			'options' => [
				'show' => 'Show this row if',
				'hide' => 'Hide this row if',
			],
		];

		$fields['_voxel_visibility_rules'] = [
			'name' => '_voxel_visibility_rules',
			'type' => 'voxel-visibility',
		];

		$repeater->set_settings( 'fields', $fields );
	}
}
