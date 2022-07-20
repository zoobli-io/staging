<?php
/**
 * Create post widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$deferred_templates = [];
$deferred_templates[] = locate_template( 'templates/widgets/create-post/_media-popup.php' );
?>

<div class="ts-form ts-create-post create-post-form ts-hidden" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<transition name="fade">
		<template v-if="submission.done">
			<div class="ts-edit-success flexify">
				<i class="las la-check-circle"></i>
				<h4>{{ submission.message }}</h4>
				<!-- <p>{{ submission.message }}</p> -->
				<div class="es-buttons flexify">
					<a :href="submission.viewLink" class="ts-btn ts-btn-2 ts-btn-large create-btn">
						<i class="las la-arrow-alt-circle-right"></i>
						{{ post_type.key === 'profile' ? 'View your profile' : 'View your post' }}
					</a>
					<!-- <a v-if="!post" href="#" class="ts-btn ts-btn-1 ts-btn-large">
						<i aria-hidden="true" class="las la-share"></i>
						Share to timeline
					</a> -->
					<a :href="submission.editLink" class="ts-btn ts-btn-1 ts-btn-large create-btn">
						<i aria-hidden="true" class="las la-angle-left"></i>
						Back to editing
					</a>
				</div>
			</div>
		</template>
	</transition>
	<template v-if="!submission.done">
		<div class="ts-form-progres">
			<ul class="step-percentage simplify-ul flexify">
				<li v-for="step_key, index in steps" :class="{'step-done': step_index >= index}"></li>
			</ul>
			<div class="ts-active-step flexify">
				<div class="active-step-details">
					<p>{{ currentStep.label }}</p>
				</div>
				<div v-if="steps.length > 1" class="step-nav flexify">
					<a href="#" @click.prevent="prevStep" class="ts-icon-btn" :class="{'disabled': step_index === 0}">
						<?php \Voxel\render_icon( $this->get_settings_for_display('prev_icon') ) ?>
					</a>
					<a href="#" @click.prevent="nextStep" class="ts-icon-btn" :class="{'disabled': step_index === (steps.length - 1)}">
						<?php \Voxel\render_icon( $this->get_settings_for_display('next_icon') ) ?>
					</a>
				</div>
			</div>
		</div>

		<div class="create-form-step">
			<?php foreach ( $post_type->get_fields() as $field ):
				if ( $field->get_type() === 'ui-step' ) {
					continue;
				}

				if ( $field_template = locate_template( sprintf( 'templates/widgets/create-post/%s-field.php', $field->get_type() ) ) ) {
					$deferred_templates[] = $field_template;
				}

				if ( $field->get_type() === 'repeater' ) {
					$deferred_templates = array_merge( $deferred_templates, $field->get_field_templates() );
				}

				$field_object = sprintf( '$root.fields[%s]', esc_attr( wp_json_encode( $field->get_key() ) ) );
				?>

				<field-<?= $field->get_type() ?>
					:field="<?= $field_object ?>"
					v-if="conditionsPass( <?= $field_object ?> )"
					:style="<?= $field_object ?>.step === currentStep.key ? '' : 'display: none;'"
					ref="field:<?= esc_attr( $field->get_key() ) ?>"
				></field-<?= $field->get_type() ?>>
				<?php
			endforeach; ?>

		</div>

		<div class="ts-form-footer flexify">
			<ul v-if="steps.length > 1" class="ts-nextprev simplify-ul flexify">
				<li>
					<a :class="{'disabled': step_index === 0}" href="#" @click.prevent="prevStep" class="ts-prev">
						<?php \Voxel\render_icon( $this->get_settings_for_display('prev_icon') ) ?>
						<span><?= _x( 'Previous step', 'create post form', 'voxel' ) ?></span>
					</a>
				</li>
				<li>
					<a :class="{'disabled': step_index === (steps.length - 1)}" href="#" @click.prevent="$event.shiftKey ? submit() : nextStep()" class="ts-next">
						<span><?= _x( 'Next step', 'create post form', 'voxel' ) ?></span>
						<?php \Voxel\render_icon( $this->get_settings_for_display('next_icon') ) ?>
					</a>
				</li>
			</ul>

			<!-- only when submitting  -->
			<a
				v-if="!post && step_index === (steps.length - 1)"
				href="#"
				@click.prevent="submit"
				class="ts-btn ts-btn-2 ts-icon-right create-btn"
				:class="{'vx-pending': submission.processing}"
			>
				<?php \Voxel\render_icon( $this->get_settings_for_display('publish_icon') ) ?>
				<?= _x( 'Publish', 'create post form', 'voxel' ) ?>
				
			</a>

			<!-- only when editing -->
			<a v-if="post" href="#" @click.prevent="submit" class="ts-btn ts-btn-2 ts-icon-right create-btn" :class="{'vx-pending': submission.processing}">
				<i class="lar la-check-circle"></i>
				<?= _x( 'Save changes', 'create post form', 'voxel' ) ?>
				
			</a>
		</div>
	</template>
</div>

<?php foreach ( $deferred_templates as $template_path ): ?>
	<?php require_once $template_path ?>
<?php endforeach ?>
