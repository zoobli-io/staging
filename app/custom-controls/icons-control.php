<?php

namespace Voxel\Custom_Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Icons_Control extends \Elementor\Control_Icons {

	public function get_value( $control, $settings ) {
		$value = parent::get_value( $control, $settings );

		if ( is_string( $value['value'] ) && strncmp( $value['value'], '@tags()', 7 ) === 0 ) {
			$icon_string = \Voxel\render( $value['value'] );
			if ( is_admin() ) {
				$icon_string = str_replace( \Voxel\PREVIEW_DTAGS_IDENTIFIER, '', $icon_string );
			}

			$value = \Voxel\parse_icon_string( $icon_string );
		}

		return $value;
	}

}
