<?php

namespace Voxel\Form_Models;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Key_Model extends Base_Form_Model {

	protected $args = [
		'editable' => true,
		'ref' => null,
		'unlocked' => false,
	];

	protected function template() { ?>
		<field-key
			<?= $this->attributes('v-model', 'ref') ?>
			:editable="<?= is_string( $this->args['editable'] )
				? $this->args['editable']
				: ( !! $this->args['editable'] ? 'true' : 'false' ) ?>"
			:unlocked="<?= $this->args['unlocked'] ? 'true' : 'false' ?>"
		></field-key>
	<?php }

}
