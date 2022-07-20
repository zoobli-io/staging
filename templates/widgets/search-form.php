<?php
/**
 * Search form widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$deferred_templates = [];
?>
<div class="ts-form ts-search-widget ts-hidden"
	data-post-types="<?= esc_attr( wp_json_encode( $post_type_config ) ) ?>"
	data-config="<?= esc_attr( wp_json_encode( $general_config ) ) ?>"
	>
	<form method="GET" ref="form" @submit="onSubmit">
		<div class="elementor-row ts-filter-wrapper min-scroll min-scroll-h">

			<?php if ( $this->get_settings_for_display('cpt_filter_show') === 'yes' ): ?>
				<filter-post-types></filter-post-types>
				<?php $deferred_templates[] = locate_template( 'templates/widgets/search-form/post-types-filter.php' ) ?>
			<?php endif ?>

			<?php
			foreach ( $post_types as $post_type ):
				$filter_list = (array) $this->get_settings_for_display(
					sprintf( 'ts_filter_list__%s', $post_type->get_key() )
				); ?>

				<template v-if="post_type.key === <?= esc_attr( wp_json_encode( $post_type->get_key() ) ) ?>">
					<?php
					foreach ( $filter_list as $filter_config ):
						$filter = $post_type->get_filter( $filter_config['ts_choose_filter'] ?? '' );
						if ( ! $filter ) {
							continue;
						}

						if ( $filter_template = locate_template(
							sprintf( 'templates/widgets/search-form/%s-filter.php', $filter->get_type() )
						) ) {
							$deferred_templates[] = $filter_template;
						}

						$filter_object = sprintf(
							'$root.post_types[%s].filters[%s]',
							esc_attr( wp_json_encode( $post_type->get_key() ) ),
							esc_attr( wp_json_encode( $filter->get_key() ) )
						);
						?>
						<filter-<?= $filter->get_type() ?>
							class="elementor-repeater-item-<?= $filter_config['_id'] ?>"
							:filter="<?= $filter_object ?>"
						></filter-<?= $filter->get_type() ?>>
						<?php
					endforeach; ?>
				</template>
			<?php endforeach ?>

			<?php if ( ! empty( $post_types ) ): ?>
				<?php $this->_ssr_filters() ?>
			<?php endif ?>

			<div class="ts-form-group flexify ts-form-submit" id="sf_submit" :class="{'vx-pending': loading}">
				<?php if ( $this->get_settings_for_display('ts_show_search_btn') === 'true' ): ?>
					<button ref="submitButton" type="submit" class="ts-form-submit ts-btn ts-btn-2 ts-btn-large">
						<?php \Voxel\render_icon( $this->get_settings_for_display('ts_sf_form_btn_icon') ) ?>Search
					</button>
				<?php endif ?>

				<?php if ( $this->get_settings_for_display('ts_show_reset_btn') === 'true' ): ?>
					<a @click.prevent="clearAll" href="#" class="ts-form-submit ts-btn ts-btn-1 ts-btn-large ts-form-reset">
						<?php \Voxel\render_icon( $this->get_settings_for_display('ts_sf_form_btn_reset_icon') ) ?>Reset
					</a>
				<?php endif ?>
			</div>
		</div>
	</form>

	<?php if ( $switchable_desktop || $switchable_tablet || $switchable_mobile ): ?>
		<div class="ts-switcher-btn">
			<a href="#" class="ts-btn ts-btn-1
				<?= ! $switchable_desktop ? 'vx-hidden-desktop' : '' ?>
				<?= ! $switchable_tablet ? 'vx-hidden-tablet' : '' ?>
				<?= ! $switchable_mobile ? 'vx-hidden-mobile' : '' ?>
				<?= $desktop_default === 'map' ? '' : 'vx-hidden-desktop' ?>
				<?= $tablet_default === 'map' ? '' : 'vx-hidden-tablet' ?>
				<?= $mobile_default === 'map' ? '' : 'vx-hidden-mobile' ?>"
				@click.prevent="toggleListView"
				ref="listViewToggle"
			>
				<i aria-hidden="true" class="las la-bars"></i>
				List view
			</a>
			<a href="#" class="ts-btn ts-btn-1
				<?= ! $switchable_desktop ? 'vx-hidden-desktop' : '' ?>
				<?= ! $switchable_tablet ? 'vx-hidden-tablet' : '' ?>
				<?= ! $switchable_mobile ? 'vx-hidden-mobile' : '' ?>
				<?= $desktop_default === 'map' ? 'vx-hidden-desktop' : '' ?>
				<?= $tablet_default === 'map' ? 'vx-hidden-tablet' : '' ?>
				<?= $mobile_default === 'map' ? 'vx-hidden-mobile' : '' ?>"
				@click.prevent="toggleMapView"
				ref="mapViewToggle"
			>
				<i aria-hidden="true" class="las la-map"></i>
				Map view
			</a>
		</div>
	<?php endif ?>
</div>

<?php foreach ( $deferred_templates as $template_path ): ?>
	<?php require_once $template_path ?>
<?php endforeach ?>
