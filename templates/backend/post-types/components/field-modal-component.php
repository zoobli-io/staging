<?php
/**
 * Field modal component.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-field-modal-template">
	<teleport to="body">
		<div class="ts-field-modal ts-theme-options">
			<div class="modal-backdrop" @click="save"></div>
			<div class="modal-content ">
				<div class="field-modal-head">
					<a href="#" @click.prevent="save" class="ts-button btn-shadow"><i class="las la-check icon-sm"></i>Done</a>
				</div>
				<field-props :field="field"></field-props>
			</div>
		</div>
	</teleport>
</script>
