<?php

namespace Voxel\Dynamic_Tags;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Dynamic_Tags {

	static $modifier_instances;
	static $visibility_rules;

	/**
	 * Parse a string containing dynamic tags.
	 *
	 * @param $addslashes Add slashes to parsed tag value. Useful when parsing a json encoded string.
	 * @since 1.0
	 */
	public static function parse( $data, $addslashes = false ) {
		\Voxel\measure_start( 'total-parsing-time' );

		// dump($data);

		$parsed = preg_replace_callback( \Voxel\REG_MATCH_DYNAMIC_STRING, function( $matches ) use ( $addslashes ) {
			$value = static::render( $matches['dynamic_string'] );

			// add indicator that this string has dtags so inline-editing can be handled in the editor
			if ( is_admin() ) {
				$value .= \Voxel\PREVIEW_DTAGS_IDENTIFIER;
			}

			return $addslashes ? wp_slash( $value ) : $value;
		}, $data );

		\Voxel\measure_end( 'total-parsing-time' );
		return $parsed;
	}

	public static function render( $string ) {
		$groups = \Voxel\config('dynamic_tags.groups');
		\Voxel\measure_start( 'total-rendering-time' );
		$render = preg_replace_callback( \Voxel\REG_MATCH_TAGS, function( $matches ) use ( $groups ) {
			$group_key = $matches['group'] ?? null;

			if ( $group_key === 'tags' || $group_key === 'endtags' ) {
				return '';
			}

			if ( ! isset( $groups[ $group_key ] ) ) {
				return $matches[0];
			}

			$group = new $groups[ $group_key ];
			$value = '';

			$property = $group->get_property( $matches['property'] ?? null );
			if ( $property !== null && isset( $property['callback'] ) ) {
				$value = ($property['callback'])( $property['_loop_index'] );
			}

			if ( isset( $matches['modifiers'] ) ) {
				$last_condition = true;
				preg_replace_callback( \Voxel\REG_MATCH_MODIFIERS, function( $matches ) use ( &$value, &$last_condition, $group ) {
					static::handle_modifier( $matches['name'], $matches['args'], $value, $last_condition, $group );
					return;
				}, $matches['modifiers'] );
			}

			return $value;
		}, $string );

		\Voxel\measure_end( 'total-rendering-time' );
		return $render;
	}

	private static function handle_modifier( $name, $args, &$value, &$last_condition, $group ) {
		$modifier = $group->get_methods()[ $name ] ?? null;
		if ( ! $modifier ) {
			$modifier = static::get_modifier_instance( $name );
		}

		if ( ! $modifier ) {
			return;
		}

		// prepare args
		$args = preg_split( \Voxel\REG_SPLIT_ARGS, $args );
		foreach ( $args as $key => $arg ) {
			$arg = preg_replace( \Voxel\REG_UNESCAPE_ARG, '${1}', $arg );
			$arg = str_replace( '@value()', $value, $arg );

			$args[ $key ] = $arg;
		}

		if ( $modifier->get_type() === 'control-structure' ) {
			$last_condition = $modifier->passes( $last_condition, $value, $args, $group );
		}

		if ( ! $last_condition ) {
			return;
		}

		$value = $modifier->apply( $value, $args, $group );
	}

	public static function get_frontend_config() {
		$config = [];
		$groups = \Voxel\config('dynamic_tags.groups');
		foreach ( $groups as $group_class ) {
			$group = new $group_class;
			$config[ $group->get_key() ] = [
				'key' => $group->get_key(),
				'title' => $group->get_title(),
				'properties' => $group->get_properties(),
				'methods' => array_map( function( $method ) {
					return $method->get_editor_config();
				}, $group->get_methods() ),
			];
		}

		return $config;
	}

	public static function get_modifier_instances() {
		if ( is_array( static::$modifier_instances ) ) {
			return static::$modifier_instances;
		}

		$list = \Voxel\config('dynamic_tags.modifiers');

		static::$modifier_instances = [];
		foreach ( $list as $key => $classname ) {
			$modifier = new $classname;
			static::$modifier_instances[ $key ] = $modifier;
		}

		return static::$modifier_instances;
	}

	public static function get_modifier_instance( $key ) {
		$modifiers = static::get_modifier_instances();
		return $modifiers[ $key ] ?? null;
	}

	public static function get_visibility_rule_instances() {
		if ( is_array( static::$visibility_rules ) ) {
			return static::$visibility_rules;
		}

		$list = \Voxel\config('dynamic_tags.visibility_rules');

		static::$visibility_rules = [];
		foreach ( $list as $key => $classname ) {
			$rule = new $classname;
			static::$visibility_rules[ $key ] = $rule;
		}

		return static::$visibility_rules;
	}

	public static function get_visibility_rule_instance( $key ) {
		$visibility_rules = static::get_visibility_rule_instances();
		return $visibility_rules[ $key ] ?? null;
	}
}
