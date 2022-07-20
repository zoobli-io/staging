<?php
/**
 * Product form widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

$deferred_templates = [];
$deferred_templates[] = locate_template( 'templates/widgets/product-form/date-picker.php' );
$deferred_templates[] = locate_template( 'templates/widgets/product-form/date-range-picker.php' );
$deferred_templates[] = locate_template( 'templates/widgets/product-form/information-fields.php' );
$deferred_templates[] = locate_template( 'templates/widgets/create-post/_media-popup.php' );
?>

<div
	class="ts-form ts-booking-form"
	data-post-id="<?= absint( $post->get_id() ) ?>"
	data-field-key="<?= esc_attr( $field->get_key() ) ?>"
	data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>"
	data-l10n="<?= esc_attr( wp_json_encode( [
		'checkIn' => _x( 'Check-in', 'product form', 'voxel' ),
		'checkOut' => _x( 'Check-out', 'product form', 'voxel' ),
		'pickDate' => _x( 'Choose date', 'product form', 'voxel' ),
	] ) ) ?>"
	v-cloak
>
	<div v-show="step === 'main'" class="ts-booking-main">
		<div class="booking-head">
			<?php \Voxel\render_icon( $this->get_settings_for_display('prform_stepone_ico') ) ?>
			<!-- <p>Starting from $<?= $config['base_price'] . (
				$product_type->config('calendar.type') === 'booking'
				&& $product_type->config('calendar.format') === 'days'
				&& $product_type->config('calendar.allow_range')
					? '/day' : '' ) ?>
			</p> -->
			<p><?= $this->get_settings_for_display('prform_stepone_text') ?></p>
		</div>
		<?php if ( $product_type->config( 'calendar.type' ) === 'booking' ): ?>
			<?php if ( $product_type->config( 'calendar.format' ) === 'days' && $product_type->config( 'calendar.allow_range' ) ): ?>
				<form-group popup-key="datePicker" ref="datePicker" @save="saveDateRange" @clear="resetPicker" wrapper-class="ts-booking-range-wrapper">
					<template #trigger>
						<label>Pick day</label>
						<div class="ts-double-input flexify">
							<div class="ts-filter ts-popup-target" :class="{'ts-filled':booking.checkIn}" @mousedown="$root.activePopup = 'datePicker'">
								<i class="las la-calendar"></i>
								<div class="ts-filter-text">{{ checkInLabel }}</div>
							</div>

							<div class="ts-filter ts-popup-target" :class="{'ts-filled':booking.checkOut}" @mousedown="$root.activePopup = 'datePicker'">
								<i class="las la-calendar"></i>
								<div class="ts-filter-text">{{ checkOutLabel }}</div>
							</div>
						</div>
					</template>
					<template #popup>
						<date-range-picker ref="picker"></date-range-picker>
					</template>
				</form-group>
			<?php else: ?>
				<form-group popup-key="datePicker" ref="datePicker" @save="saveSingleDate" @clear="resetPicker" wrapper-class="ts-booking-date-wrapper">
					<template #trigger>
						<label>Pick day</label>
						<div class="ts-filter ts-popup-target" :class="{'ts-filled':booking.checkIn}" @mousedown="$root.activePopup = 'datePicker'">
							<i aria-hidden="true" class="las la-calendar-minus"></i>
							<div class="ts-filter-text">{{ pickDateLabel }}</div>
						</div>
					</template>
					<template #popup>
						<date-picker ref="picker"></date-picker>
					</template>
				</form-group>
			<?php endif ?>

			<?php if ( $product_type->config( 'calendar.format' ) === 'slots' ): ?>
				<div v-if="timeslots" class="ts-form-group">
					<label>Pick slot</label>
					<ul class="ts-pick-slot simplify-ul">
						<li v-for="slot in timeslots" :class="{'slot-picked': slot === booking.timeslot}">
							<a href="#" @click.prevent="booking.timeslot = slot">
								<i class="lar la-check-circle"></i>
								<span>{{ slot.from }} — {{ slot.to }}</span>
							</a>
						</li>
					</ul>
				</div>
			<?php endif ?>
		<?php elseif ( $product_type->config( 'calendar.type' ) === 'recurring-date' ): ?>
			<div class="ts-form-group">
				<label>Pick available date</label>
				<ul v-if="config.recurring_date.bookable.length" class="ts-pick-slot simplify-ul">
					<li
						v-for="date in config.recurring_date.bookable"
						:class="{'slot-picked': booking.checkIn === date.start && booking.checkOut === date.end}"
						@click.prevent="booking.checkIn = date.start; booking.checkOut = date.end"
					>
						<a href="#">
							<i class="lar la-check-circle"></i>
							<span>{{ date.formatted }}</span>
						</a>
					</li>
				</ul>
				<small v-else>There are no available dates at the moment.</small>
			</div>
		<?php endif ?>

		<template v-for="addition in additions">
			<div v-if="addition.type === 'numeric'" class="ts-form-group">
				<label>{{ addition.label }}</label>
				<div class="ts-stepper-input flexify">
					<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement(addition)">
						<i aria-hidden="true" class="las la-minus"></i>
					</button>
					<input
						v-model="addition.value"
						type="number"
						class="ts-input-box"
						@change="validateValueInBounds(addition)"
					>
					<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment(addition)">
						<i aria-hidden="true" class="las la-plus"></i>
					</button>
				</div>
			</div>
			<div v-if="addition.type === 'checkbox'" class="ts-form-group">
				<label>{{ addition.label }}</label>
				<div class="switch-slider">
					<div class="onoffswitch">
						<input v-model="addition.value" type="checkbox" class="onoffswitch-checkbox">
						<label class="onoffswitch-label" @click.prevent="addition.value = !addition.value"></label>
					</div>
				</div>
			</div>
			<template v-if="addition.type === 'select'">
				<form-group
					v-if="Object.keys(addition.choices).length"
					:popup-key="addition.key"
					:ref="'select-'+addition.key"
					@save="$refs['select-'+addition.key].blur()"
					@clear="addition.value = null"
					:show-clear="!addition.required"
				>
					<template #trigger>
						<label>{{ addition.label }}</label>
						<div class="ts-filter ts-popup-target" :class="{'ts-filled': addition.value !== null}" @mousedown="$root.activePopup = addition.key">
							<span><i aria-hidden="true" class="las la-check-circle"></i></span>
							<div class="ts-filter-text">
								<span>{{ addition.choices[addition.value] ? addition.choices[addition.value].label : addition.placeholder }}</span>
							</div>
						</div>
					</template>
					<template #popup>
						<div class="ts-term-dropdown ts-multilevel-dropdown">
							<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
								<template v-for="choice, choice_value in addition.choices">
									<li>
										<a href="#" class="flexify" @click.prevent="addition.value = choice_value">
											<span v-if="choice.icon" v-html="choice.icon"></span>
											<span v-else><i aria-hidden="true" class="las la-angle-right"></i></span>
											<p>{{ choice.label }}</p>
											<div class="ts-radio-container">
												<label class="container-radio">
													<input
														type="radio"
														:value="choice_value"
														:checked="addition.value === choice_value"
														disabled
														hidden
													>
													<span class="checkmark"></span>
												</label>
											</div>
										</a>
									</li>
								</template>
							</ul>
						</div>
					</template>
				</form-group>
			</template>
		</template>

		<div class="ts-form-group">
			<a
				href="#"
				@click.prevent="prepareCheckout"
				class="ts-btn ts-btn-2 ts-btn-large ts-booking-submit"
				:class="{'vx-pending': loading}"
			>
				<?php \Voxel\render_icon( $this->get_settings_for_display('sub_con_ico') ) ?>
				<?= $this->get_settings_for_display('prform_continue') ?>
			</a>
		</div>

		<div class="ts-form-group tcc-container">
			<ul class="ts-cost-calculator simplify-ul flexify">
				<li v-if="pricing.additions.length && pricing.base_price">
					<div class="ts-item-name">
						<p>Base price <span v-if="repeatDayCount > 1">({{ repeatDayCount }} days)</span></p>
					</div>
					<div class="ts-item-price">
						<p><?= \Voxel\get('settings.stripe.currency') ?> {{ pricing.base_price }}</p>
					</div>
				</li>
				<template v-for="addition in pricing.additions">
					<li v-if="addition.price">
						<template v-if="addition.repeat && repeatDayCount > 1">
							<div class="ts-item-name">
								<p>{{ addition.label }} ({{ repeatDayCount }} days)</p>
							</div>
							<div class="ts-item-price">
								<p><?= \Voxel\get('settings.stripe.currency') ?> {{ addition.price }}</p>
							</div>
						</template>
						<template v-else>
							<div class="ts-item-name">
								<p>{{ addition.label }}</p>
							</div>
							<div class="ts-item-price">
								<p><?= \Voxel\get('settings.stripe.currency') ?> {{ addition.price }}</p>
							</div>
						</template>
					</li>
				</template>
				<li class="ts-total">
					<div class="item-name">
						<p>Total</p>
					</div>
					<div class="item-price">
						<p><?= \Voxel\get('settings.stripe.currency') ?> {{ pricing.total }}</p>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<div v-show="step === 'checkout'" class="ts-booking-fields">
		<div class="booking-head">
			<?php \Voxel\render_icon( $this->get_settings_for_display('prform_steptwo_ico') ) ?>
			<p><?= $this->get_settings_for_display('prform_steptwo_text') ?></p>
			
		</div>

		<?php foreach ( $product_type->get_fields() as $field ):
			$field_object = sprintf( '$root.config.fields[%s]', esc_attr( wp_json_encode( $field->get_key() ) ) );
			?>
			<field-<?= $field->get_type() ?>
				:field="<?= $field_object ?>"
				ref="field:<?= esc_attr( $field->get_key() ) ?>"
			></field-<?= $field->get_type() ?>>
		<?php endforeach ?>

		<div class="ts-form-group">
			<a href="#" @click.prevent="submit" class="ts-btn ts-btn-2 ts-btn-large ts-booking-submit" :class="{'vx-pending': loading}">
				<?php \Voxel\render_icon( $this->get_settings_for_display('sub_checkout_ico') ) ?>
				<?= $this->get_settings_for_display('prform_checkout') ?>
			</a>
		</div>
		<div class="ts-form-group">
		
	   		<a href="#" class="ts-btn ts-btn-4 ts-btn-large"  @click.prevent="step = 'main'" >
	   			<i aria-hidden="true" class="las la-angle-left"></i>
	   			Go back
	   		</a>
			 
		</div>
	</div>
</div>

<?php foreach ( $deferred_templates as $template_path ): ?>
	<?php require_once $template_path ?>
<?php endforeach ?>
