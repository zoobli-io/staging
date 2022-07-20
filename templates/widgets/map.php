<?php
/**
 * Map widget template.
 *
 * @since 1.0
 */
?>

<?php if ( $source === 'current-post' ): ?>
	<div class="ts-map ts-map-autoload" data-config="<?= esc_attr( wp_json_encode( [
		'center' => [ 'lat' => $address['latitude'], 'lng' => $address['longitude'] ],
		'zoom' => $this->get_settings_for_display( 'ts_default_zoom' ),
		'minZoom' => $this->get_settings_for_display( 'ts_min_zoom' ),
		'maxZoom' => $this->get_settings_for_display( 'ts_max_zoom' ),
		'markers' => [ [
			'lat' => $address['latitude'],
			'lng' => $address['longitude'],
			'template' => \Voxel\_post_get_marker( $post ),
		] ],
	] ) ) ?>"></div>
<?php else: ?>
	<?php $checkbox_id = sprintf( 'map-drag-%d', wp_unique_id() ) ?>
	<?php if ( $this->get_settings_for_display('ts_drag_search') === 'yes' ): ?>
		<div class="ts-map-drag">
			<div class="switch-slider">
				<div class="onoffswitch">
					<input id="<?= $checkbox_id ?>" type="checkbox" class="onoffswitch-checkbox"
						<?php checked( $this->get_settings_for_display( 'ts_drag_search_default' ) === 'checked' ) ?>>
					<label for="<?= $checkbox_id ?>" class="onoffswitch-label"></label>
				</div>
				<p>Search as I move the map</p>
			</div>
		</div>
	<?php endif ?>

	<div class="ts-map" data-config="<?= esc_attr( wp_json_encode( [
		'center' => [
			'lat' => $this->get_settings_for_display( 'ts_default_lat' ),
			'lng' => $this->get_settings_for_display( 'ts_default_lng' ),
		],
		'zoom' => $this->get_settings_for_display( 'ts_default_zoom' ),
		'minZoom' => $this->get_settings_for_display( 'ts_min_zoom' ),
		'maxZoom' => $this->get_settings_for_display( 'ts_max_zoom' ),
	] ) ) ?>"></div>
<?php endif ?>
