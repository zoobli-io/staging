<?php

namespace Voxel\Controllers\Elementor;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Document_Controller extends \Voxel\Controllers\Base_Controller {

	protected $printed_styles;

	protected function hooks() {
		$this->on( 'elementor/documents/register_controls', '@register_document_settings', 100 );
		$this->on( 'elementor/frontend/before_get_builder_content', '@store_printed_styles', 100 );
		$this->filter( 'elementor/frontend/the_content', '@restrict_content', 100 );
	}

	protected function register_document_settings( $document ) {
		$document->start_controls_section( 'voxel_document_settings', [
			'label' => __( 'Voxel Settings ✨', 'voxel' ),
			'tab' => 'tab_voxel',
		] );

		$document->add_control( 'voxel_hide_header', [
			'label' => __( 'Hide header on this page', 'voxel' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
			'label_on' => __( 'Hide', 'voxel' ),
			'label_off' => __( 'Show', 'voxel' ),
		] );

		$document->add_control( 'voxel_hide_footer', [
			'label' => __( 'Hide footer on this page', 'voxel' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'default' => '',
			'label_on' => __( 'Hide', 'voxel' ),
			'label_off' => __( 'Show', 'voxel' ),
		] );

		$template_selector = '.elementor.elementor-'.$document->get_id();
		// $document->add_control( 'voxel_sticky_template', [
		// 	'label' => __( 'Make template sticky?', 'plugin-domain' ),
		// 	'type' => \Elementor\Controls_Manager::SWITCHER,
		// 	'return_value' => 'yes',
		// 	'selectors' => [
		// 		$template_selector => 'position: sticky;',
		// 	],
		// ] );

		$document->add_control(
			'sticky_container_desktop',
			[
				'label' => __( 'Sticky on desktop', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'sticky'  => __( 'Enable', 'plugin-domain' ),
					'initial' => __( 'Disable', 'plugin-domain' ),
				],

				'selectors' => [
					'(desktop)'.$template_selector => 'position: {{VALUE}}',
				],
			]
		);

		$document->add_control(
			'sticky_container_tablet',
			[
				'label' => __( 'Sticky on tablet', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'sticky'  => __( 'Enable', 'plugin-domain' ),
					'initial' => __( 'Disable', 'plugin-domain' ),
				],

				'selectors' => [
					'(tablet)'.$template_selector => 'position: {{VALUE}}',
				],
			]
		);

		$document->add_control(
			'sticky_container_mobile',
			[
				'label' => __( 'Sticky on mobile', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'sticky'  => __( 'Enable', 'plugin-domain' ),
					'initial' => __( 'Disable', 'plugin-domain' ),
				],

				'selectors' => [
					'(mobile)'.$template_selector => 'position: {{VALUE}}',
				],
			]
		);


		$document->add_control( 'sticky_top_value', [
			'label' => __( 'Top', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'voxel_sticky_template' => 'yes' ],
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				$template_selector => 'top: {{SIZE}}{{UNIT}};',
			],
		] );

		$document->add_control( 'sticky_left_value', [
			'label' => __( 'Left', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'voxel_sticky_template' => 'yes' ],
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				$template_selector => 'left: {{SIZE}}{{UNIT}};',
			],
		] );

		$document->add_control( 'sticky_right_value', [
			'label' => __( 'Right', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'voxel_sticky_template' => 'yes' ],
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				$template_selector => 'right: {{SIZE}}{{UNIT}};',
			],
		] );

		$document->add_control( 'sticky_bottom_value', [
			'label' => __( 'Bottom', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'voxel_sticky_template' => 'yes' ],
			'size_units' => [ 'px', '%', 'vh'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				$template_selector => 'bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$document->add_control( 'sticky_z_index', [
			'label' => __( 'Z-index', 'plugin-domain' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'condition' => [ 'voxel_sticky_template' => 'yes' ],
			'size_units' => [ 'px'],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 1,
				],
			],
			'selectors' => [
				$template_selector => 'z-index: {{SIZE}}',
			],
		] );

		$document->end_controls_section();

		$document->start_controls_section( '_voxel_visibility_settings', [
			'label' => __( 'Visibility', 'voxel' ),
			'tab' => 'tab_voxel',
		] );

		$document->add_control( '_voxel_visibility_behavior', [
			'label' => __( 'Document visibility', 'voxel' ),
			'label_block' => true,
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'show',
			'options' => [
				'show' => 'Show this document if',
				'hide' => 'Hide this document if',
			],
		] );

		$document->add_control( '_voxel_visibility_rules', [
			'type' => 'voxel-visibility',
		] );

		$document->add_control( '_voxel_visibility_hidden', [
			'label' => __( 'When document is restricted, display:', 'voxel' ),
			'label_block' => true,
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none' => 'Blank',
				'auth' => 'Auth template',
				'restricted' => 'Restricted page template',
				'404' => '404 page template',
				'custom' => 'Custom template',
			],
		] );

		$document->add_control( '_voxel_visibility_hidden_custom', [
			'label' => __( 'Template ID', 'elementor' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'condition' => [ '_voxel_visibility_hidden' => 'custom' ],
		] );

		$document->end_controls_section();
	}

	protected function store_printed_styles() {
		$this->printed_styles = wp_styles()->done;
	}

	protected function restrict_content( $content ) {
		$post_id = \Elementor\Plugin::$instance->documents->get_current()->get_post()->ID;
		$behavior = \Voxel\get_page_setting( '_voxel_visibility_behavior', $post_id );
		$rules = \Voxel\get_page_setting( '_voxel_visibility_rules', $post_id );
		$on_hidden = \Voxel\get_page_setting( '_voxel_visibility_hidden', $post_id );
		$custom_template_id = \Voxel\get_page_setting( '_voxel_visibility_hidden_custom', $post_id );

		if ( ! is_array( $rules ) || empty( $rules ) ) {
			return $content;
		}

		$rules_passed = \Voxel\evaluate_visibility_rules( $rules );
		if ( $behavior === 'hide' ) {
			$should_render = $rules_passed ? false : true;
		} else {
			$should_render = $rules_passed ? true : false;
		}

		if ( $should_render ) {
			return $content;
		}

		$frontend = \Elementor\Plugin::$instance->frontend;

		// unset any styles printed on the restricted document content
		wp_styles()->done = $this->printed_styles;

		$getTemplate = function( $template_id ) {
			ob_start();
			\Voxel\print_template( $template_id );
			return ob_get_clean();
		};

		if ( $on_hidden === 'none' ) {
			return '';
		} elseif ( $on_hidden === 'auth' ) {
			return $getTemplate( \Voxel\get('templates.auth') );
		} elseif ( $on_hidden === 'restricted' ) {
			return $getTemplate( \Voxel\get('templates.restricted') );
		} elseif ( $on_hidden === '404' ) {
			return $getTemplate( \Voxel\get('templates.404') );
		} else {
			if ( absint( $custom_template_id ) === absint( $post_id ) ) {
				return '';
			}

			return $getTemplate( $custom_template_id );
		}
	}
}
