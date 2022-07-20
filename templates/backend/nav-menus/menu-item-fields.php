<?php
/**
 * Menu item custom fields.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<div class="description description-wide">
	<label style="display: block;">Icon</label>
	<div class="ts-icon-picker" style="display: inline-block">
		<div class="icon-preview"></div>
		<input type="hidden" value="<?= esc_attr( $icon_string ) ?>" name="voxel_item_icon[<?= $item_id ?>]">
		<a href="#" class="button button-small choose-icon">Choose Icon</a>
		<a href="#" class="button button-small upload-svg">Upload SVG</a>
		<a href="#" class="button button-small clear-icon">Remove</a>
	</div>
</div>

<script type="text/javascript">
	if ( typeof window.voxel_init_icon_pickers === 'function' ) {
		window.voxel_init_icon_pickers();
	}
</script>

<?php if ( $item->type === 'custom' ): ?>
	<p class="description description-wide" onclick="Voxel_Backend._nav_dtags(this)">
		<label>Dynamic URL</label>
		<input type="text" readonly name="voxel_item_url[<?= $item_id ?>]" value="<?= esc_attr( $url ) ?>" class="widefat">
	</p>
<?php endif ?>

<p class="description description-wide" onclick="Voxel_Backend._nav_dtags(this)">
	<label>Dynamic label</label>
	<input type="text" readonly name="voxel_item_label[<?= $item_id ?>]" value="<?= esc_attr( $label ) ?>" class="widefat">
</p>
