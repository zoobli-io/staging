<?php
/**
 * Edit post type form in WP Admin.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<div class="wrap">
	<div id="voxel-edit-post-type" v-cloak>
		<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" @submit="prepareSubmission">
			<div class="edit-cpt-header">
				<div class="ts-container cpt-header-container">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-2 v-center ">
							<h1><?php echo $post_type->get_label() ?><p>You are editing <?php echo $post_type->get_label() ?> post type</p></h1>
						</div>
						<div class="cpt-header-buttons ts-col-1-2 v-center">
							<input type="hidden" name="post_type_config" :value="submit_config">
							<input type="hidden" name="active_tab" :value="subtab?tab+'.'+subtab:tab">
							<input type="hidden" name="action" value="voxel_save_post_type_settings">
							<?php wp_nonce_field( 'voxel_save_post_type_settings' ) ?>
							<button type="submit" name="remove_post_type" value="yes" class="ts-button ts-transparent "
								onclick="return confirm('Are you sure?')">
								<?= $post_type->is_created_by_voxel() ? 'Delete' : 'Stop managing with Voxel' ?>
							</button>
							&nbsp;&nbsp;
							<button type="submit" class="ts-button ts-save-settings btn-shadow"><i class="las la-save icon-sm"></i>Save changes</button>
						</div>
					</div>
					
					<span class="ts-separator"></span>
					
				</div>
			</div>
			<div class="ts-theme-options ts-container">
				<div class="ts-row ts-theme-options-nav">
					<div class="ts-nav ts-col-1-1">
						<div class="ts-nav-item" :class="{'current-item': tab === 'general'}">
							<a href="#" @click.prevent="setTab('general', 'base')">
								<span class="item-icon all-center">
									<i class="las la-home"></i>
								</span>
								<span class="item-name">
									General
								</span>
							</a>
						</div>
						<div class="ts-nav-item" :class="{'current-item': tab === 'fields'}">
							<a href="#" @click.prevent="setTab('fields')">
								<span class="item-icon all-center">
									<i class="las la-grip-lines"></i>
								</span>
								<span class="item-name">
									Fields
								</span>
							</a>
						</div>
						<div class="ts-nav-item" :class="{'current-item': tab === 'templates'}">
							<a href="#" @click.prevent="setTab('templates')">
								<span class="item-icon all-center">
									<i class="las la-pencil-ruler"></i>
								</span>
								<span class="item-name">
									Templates
								</span>
							</a>
						</div>
						<div class="ts-nav-item" :class="{'current-item': tab === 'filters'}">
							<a href="#" @click.prevent="setTab('filters', 'general')">
								<span class="item-icon all-center">
									<i class="las la-filter"></i>
								</span>
								<span class="item-name">
									Filtering
								</span>
							</a>
						</div>
					</div>
				</div>

				<div v-if="tab === 'general'">
					<general-settings></general-settings>
				</div>

				<div v-if="tab === 'fields'">
					<form-fields></form-fields>
				</div>

				<div v-if="tab === 'filters'">
					<search-forms></search-forms>
				</div>

				<div v-if="tab === 'templates'">
					<page-templates></page-templates>
				</div>

				<teleport to="#wp-admin-bar-top-secondary">
					<li v-if="$root.indexing.running" class="vx-topbar-indexing">
						<a href="#" class="ab-item" @click.prevent="$root.setTab('filters', 'status')">
							<span>{{ indexingStatus }}</span>
						</a>
					</li>
				</teleport>
			</div>
		</form>
	</div>
</div>

<?php require_once locate_template( 'templates/backend/post-types/components/general-settings-component.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/form-fields-component.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/page-templates-component.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/search-forms-component.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/search-filters-component.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/search-order-component.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/field-modal-component.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/field-conditions.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/repeater-fields.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/field-props.php' ) ?>
<?php require_once locate_template( 'templates/backend/post-types/components/select-field-choices.php' ) ?>
