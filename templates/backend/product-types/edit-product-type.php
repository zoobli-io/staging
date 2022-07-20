<?php
/**
 * Edit product type fields in WP Admin.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

require_once locate_template('templates/backend/product-types/components/additions.php');
require_once locate_template('templates/backend/product-types/components/information-fields.php');
require_once locate_template('templates/backend/post-types/components/select-field-choices.php');
?>

<div class="wrap">
	<div id="voxel-edit-product-type" v-cloak>
		<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" @submit="prepareSubmission">
			<div class="edit-cpt-header">
				<div class="ts-container cpt-header-container">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-2 v-center ">
							<h1><?= $product_type->get_label() ?>
								<p>You are editing <?= $product_type->get_label() ?> product type</p>
							</h1>
						</div>
						<div class="cpt-header-buttons ts-col-1-2 v-center">
							<input type="hidden" name="product_type_config" :value="submit_config">
							<input type="hidden" name="action" value="voxel_save_product_type_settings">
							<?php wp_nonce_field( 'voxel_save_product_type_settings' ) ?>
							<button type="submit" name="remove_product_type" value="yes" class="ts-button ts-transparent"
								onclick="return confirm('Are you sure?')">
								Delete
							</button>

							<button type="submit" class="ts-button ts-save-settings btn-shadow">
								<i class="las la-save icon-sm"></i>
								Save changes
							</button>
						</div>
					</div>
					<span class="ts-separator"></span>
				</div>
			</div>

			<div class="ts-theme-options ts-container">

				<div class="ts-tab-content ts-container">
					<div class="ts-row ts-theme-options-nav">
						<div class="ts-nav ts-col-1-1">
							<div class="ts-nav-item" :class="{'current-item': tab === 'general'}">
								<a href="#" @click.prevent="setTab('general')">
									<span class="item-icon all-center">
										<i class="las la-home"></i>
									</span>
									<span class="item-name">
										General
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'booking'}">
								<a href="#" @click.prevent="setTab('booking')">
									<span class="item-icon all-center">
										<i class="las la-calendar-check"></i>
									</span>
									<span class="item-name">
										Booking
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'additions', 'vx-disabled': config.settings.payments.pricing === 'price_id'}">
								<a href="#" @click.prevent="setTab('additions')">
									<span class="item-icon all-center">
										<i class="las la-plus"></i>
									</span>
									<span class="item-name">
										Additions
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'fields'}">
								<a href="#" @click.prevent="setTab('fields')">
									<span class="item-icon all-center">
										<i class="las la-user-circle"></i>
									</span>
									<span class="item-name">
										Information fields
									</span>
								</a>
							</div>
							<div class="ts-nav-item" :class="{'current-item': tab === 'checkout'}">
								<a href="#" @click.prevent="setTab('checkout')">
									<span class="item-icon all-center">
										<i class="las la-shopping-bag"></i>
									</span>
									<span class="item-name">
										Checkout
									</span>
								</a>
							</div>
							</div>
					</div>

					<div v-if="tab === 'general'" class="inner-tab ts-row wrap-row all-center">
						<div class="ts-col-1-2">
							
							<div class="ts-row wrap-row">
								<div class="ts-tab-heading ts-col-1-1">
									<h1>General</h1>
									<p>General product type settings</p>
								</div>
								<div class="ts-form-group ts-col-1-1">
									<label>Label</label>
									<input type="text" v-model="config.settings.label">
								</div>
								<div class="ts-form-group ts-col-1-1">
									<label>Key</label>
									<input type="text" v-model="config.settings.key" maxlength="20" required disabled>
								</div>
								<!-- <div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Base price</h3>
									<p>
										Owner can set the base product price, before any additions are considered.
										If booking calendar is enabled, this is the base price for a single booking instance.
									</p>
								</div> -->
							

								<?php \Voxel\Form_Models\Select_Model::render( [
									'v-model' => 'config.settings.payments.mode',
									'label' => 'Payment mode',
									'choices' => [
										'payment' => 'Single payment: Users pay once for products of this type',
										'subscription' => 'Subscription: Users pay on a recurring interval for products of this type',
									],
								] ) ?>

								<?php \Voxel\Form_Models\Select_Model::render( [
									'v-model' => 'config.settings.payments.transfer_destination',
									'label' => 'Upon successful payment, funds are transferred to:',
									'choices' => [
										'vendor_account' => 'Vendor: Funds are transferred directly to the seller\'s account',
										'admin_account' => 'Admin: Funds are transferred to the admin account',
									],
								] ) ?>

								<template v-if="config.settings.payments.transfer_destination === 'vendor_account'">
									<template v-if="config.settings.payments.mode === 'subscription'">
										<?php \Voxel\Form_Models\Number_Model::render( [
											'v-model' => 'config.checkout.application_fee.amount',
											'label' => 'Platform fee on subscription sales (in percentage)',
											'min' => 0,
											'max' => 100,
										] ) ?>
									</template>
									<template v-else>
										<?php \Voxel\Form_Models\Radio_Buttons_Model::render( [
											'v-model' => 'config.checkout.application_fee.type',
											'label' => 'Platform fee on product sales',
											'choices' => [
												'percentage' => 'Percentage of product price',
												'fixed_amount' => 'Fixed amount',
											],
										] ) ?>

										<?php \Voxel\Form_Models\Number_Model::render( [
											'v-model' => 'config.checkout.application_fee.amount',
											'v-if' => 'config.checkout.application_fee.type === "percentage"',
											'label' => 'Percentage',
											'min' => 0,
											'max' => 100,
										] ) ?>

										<?php \Voxel\Form_Models\Number_Model::render( [
											'v-model' => 'config.checkout.application_fee.amount',
											'v-if' => 'config.checkout.application_fee.type === "fixed_amount"',
											'label' => 'Amount (in cents)',
											'min' => 0,
										] ) ?>
									</template>
								</template>

								<?php \Voxel\Form_Models\Select_Model::render( [
									'v-if' => 'config.settings.payments.mode === \'payment\'',
									'v-model' => 'config.settings.payments.capture_method',
									'label' => 'Funds capture method',
									'choices' => [
										'automatic' => 'Automatic: Capture funds when the customer authorizes the payment.',
										'manual' => 'Manual: Capture funds when the vendor approves the customer order.',
									],
								] ) ?>

								<?php \Voxel\Form_Models\Radio_Buttons_Model::render( [
									'v-model' => 'config.settings.payments.pricing',
									'label' => 'Product pricing',
									'choices' => [
										'dynamic' => <<<HTML
											<span>Dynamic</span>
											<p class="mt0">Price is calculated as the sum of the base price and used additions.</p>
										HTML,
										'price_id' => <<<HTML
											<span>Price ID</span>
											<p class="mt0">
												Price references a product price ID created directly in the Stripe dashboard.
												Product additions are not available with this method.
											</p>
										HTML,
									],
								] ) ?>
							</div>
						</div>
					</div>

					<div v-if="tab === 'booking'" class="inner-tab ts-row wrap-row all-center">
						<div class="ts-col-1-2">
							
							<div class="ts-row wrap-row">
								<div class="ts-tab-heading ts-col-1-1">
									<h1>Booking</h1>
									<p>Enable and configure booking</p>
								</div>
								<?php \Voxel\Form_Models\Select_Model::render( [
									'v-model' => 'config.calendar.type',
									'width' => '1/1',
									'label' => 'Get bookable instances from:',
									'choices' => [
										'booking' => 'Booking calendar',
										'recurring-date' => 'Recurring Date field',
										'none' => 'None: Booking is disabled',
									],
								] ) ?>

								<?php \Voxel\Form_Models\Radio_Buttons_Model::render( [
									'v-model' => 'config.calendar.format',
									'v-if' => 'config.calendar.type === \'booking\'',
									'width' => '1/1',
									'label' => 'Owner can create bookable',
									'choices' => [
										'days' => 'Days',
										'slots' => 'Time slots',
									],
								] ) ?>

								<?php \Voxel\Form_Models\Switcher_Model::render( [
									'v-model' => 'config.calendar.allow_range',
									'v-if' => 'config.calendar.type === \'booking\' && config.calendar.format === \'days\'',
									'width' => '1/1',
									'label' => 'Owner can create bookable day ranges',
								] ) ?>
							</div>
						</div>
					</div>

					<div v-if="tab === 'additions'" class="inner-tab ts-row wrap-row all-center">
						<product-additions></product-additions>
					</div>

					<div v-if="tab === 'fields'" class="inner-tab ts-row wrap-row all-center">
						<information-fields></information-fields>
					</div>

					<div v-if="tab === 'checkout'" class="inner-tab ts-row wrap-row all-center">
						<div class="ts-col-1-2">
						
							<div class="ts-row">
								<div class="ts-tab-heading ts-col-1-1">
									<h1>Checkout</h1>
									<p>Configure checkout</p>
								</div>
							</div>
							<div class="ts-row wrap-row">
								
								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Tax</h3>
								</div>

								<?php \Voxel\Form_Models\Select_Model::render( [
									'v-model' => 'config.checkout.tax.mode',
									'label' => 'Tax collection mode',
									'choices' => [
										'auto' => 'Automatic',
										'manual' => 'Manual',
										'none' => 'None',
									],
								] ) ?>

								<template v-if="config.checkout.tax.mode === 'auto'">
									<div class="ts-form-group ts-col-1-1">
										<h3>Collect taxes automatically using <a href="https://stripe.com/tax" target="_blank">Stripe Tax</a></h3>
										<p>
											<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/settings/tax' ) ) ?>" target="_blank">Configure Stripe Tax</a>
											<span> &middot; </span>
											<a href="https://stripe.com/docs/tax/tax-codes" target="_blank">Available Tax Codes</a>
										</p>
									</div>

									<?php \Voxel\Form_Models\Select_Model::render( [
										'v-model' => 'config.checkout.tax.auto.tax_code',
										'label' => 'Tax code',
										'choices' => [ '' => 'Select a code' ] + \Voxel\Stripe\Tax_Codes::all(),
									] ) ?>

									<?php \Voxel\Form_Models\Select_Model::render( [
										'v-model' => 'config.checkout.tax.auto.tax_behavior',
										'label' => 'Tax behavior',
										'choices' => [
											'inclusive' => 'Inclusive',
											'exclusive' => 'Exclusive',
										],
									] ) ?>

									<?php \Voxel\Form_Models\Switcher_Model::render( [
										'v-model' => 'config.checkout.tax.auto.tax_id_collection',
										'label' => 'Enable customer Tax ID collection',
									] ) ?>
								</template>

								<template v-if="config.checkout.tax.mode === 'manual'">
									<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
										<h3>
											Collect taxes manually using Tax Rates
										</h3>
										<p>
											<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/tax-rates' ) ) ?>" target="_blank">Manage Tax Rates</a>
										</p>
									</div>

									<div class="ts-form-group ts-col-1-2">
										<h4>Live mode</h4>

										<rate-list
											v-model="config.checkout.tax.manual.tax_rates"
											mode="live"
											source="backend.list_tax_rates"
										></rate-list>
									</div>

									<div class="ts-form-group ts-col-1-2">
										<h4>Test mode</h4>

										<rate-list
											v-model="config.checkout.tax.manual.test_tax_rates"
											mode="test"
											source="backend.list_tax_rates"
										></rate-list>
									</div>
								</template>

								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Shipping</h3>
								</div>

								<?php \Voxel\Form_Models\Switcher_Model::render( [
									'v-model' => 'config.checkout.shipping.enabled',
									'label' => 'Enable shipping',
								] ) ?>

								<template v-if="config.checkout.shipping.enabled">
									<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
										<h3>
											Shipping Rates
										</h3>
										<p>
											<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/shipping-rates' ) ) ?>" target="_blank">Manage Shipping Rates</a>
										</p>
									</div>

									<div class="ts-form-group ts-col-1-2">
										<h4>Live mode</h4>

										<rate-list
											v-model="config.checkout.shipping.shipping_rates"
											mode="live"
											source="backend.list_shipping_rates"
										></rate-list>
									</div>

									<div class="ts-form-group ts-col-1-2">
										<h4>Test mode</h4>

										<rate-list
											v-model="config.checkout.shipping.test_shipping_rates"
											mode="test"
											source="backend.list_shipping_rates"
										></rate-list>
									</div>

									<?php \Voxel\Form_Models\Checkboxes_Model::render( [
										'v-model' => 'config.checkout.shipping.allowed_countries',
										'label' => 'Allowed countries',
										'description' => sprintf(
											'These countries are currently not supported: %s',
											"\n - ".join( "\n - ", \Voxel\Stripe\Country_Codes::shipping_unsupported() )
										),
										'columns' => 'two',
										'choices' => \Voxel\Stripe\Country_Codes::shipping_supported(),
									] ) ?>
								</template>

								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>
										Promotion codes
									</h3>
									<p>
										<a href="<?= esc_url( \Voxel\Stripe::dashboard_url( '/coupons' ) ) ?>" target="_blank">Manage Promotion Codes</a>
									</p>
								</div>

								<?php \Voxel\Form_Models\Switcher_Model::render( [
									'v-model' => 'config.checkout.promotion_codes.enabled',
									'label' => 'Allow promotion codes',
								] ) ?>

								<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
									<h3>Order notes</h3>
								</div>

								<?php \Voxel\Form_Models\Switcher_Model::render( [
									'v-model' => 'config.notes.enabled',
									'width' => '1/1',
									'label' => 'Allow product vendor to include notes',
								] ) ?>

								<template v-if="config.notes.enabled">
									<div class="ts-form-group ts-col-1-1">
										<label>Label</label>
										<input type="text" v-model="config.notes.label">
									</div>
									<div class="ts-form-group ts-col-1-1">
										<label>Description</label>
										<textarea v-model="config.notes.description"></textarea>
									</div>
									<div class="ts-form-group ts-col-1-1">
										<label>Placeholder</label>
										<input type="text" v-model="config.notes.placeholder">
									</div>
								</template>
							</div>
						</div>
					</div>
				</div>

				<?php if ( \Voxel\is_dev_mode() ): ?>
					<!-- <pre debug>{{ config }}</pre> -->
				<?php endif ?>
			</div>
		</form>
	</div>
</div>

<script type="text/html" id="product-type-rate-list-template">
	<div v-if="modelValue.length">
		<ul>
			<li v-for="rate in modelValue">
				{{ rate }}
				<a href="#" @click.prevent="remove(rate)">Remove</a>
			</li>
		</ul>
	</div>
	<div v-else>
		No rates added yet.
	</div>

	<a href="#" @click.prevent="show">+ Add rate</a>

	<teleport to="body">
		<div v-if="open" class="ts-field-modal ts-theme-options">
			<div class="modal-backdrop" @click="open = false"></div>
			<div class="modal-content ">
				<div class="field-modal-head">
					<a href="#" @click.prevent="open = false" class="ts-button btn-shadow">
						<i class="las la-check icon-sm"></i> Done
					</a>
				</div>

				<div class="field-modal-body min-scroll">
					<div class="ts-row wrap-row">
						<template v-if="rates === null">
							<p>Loading...</p>
						</template>
						<template v-else-if="!rates.length">
							<p>No results found.</p>
						</template>
						<template v-else>
							<ul class="ts-form-group ts-col-1-1">
								<li v-for="rate in rates">
									<a href="#" @click.prevent="toggle(rate)">
										{{ rate.display_name }} &middot; {{ rate.id }}
										<span v-if="isSelected(rate)">Selected</span>
									</a>
								</li>
							</ul>

							<div class="ts-form-group ts-col-1-1">
								<a href="#" v-if="rates[0].id !== first_item" @click.prevent="prev">Prev</a>
								<a href="#" v-if="!is_last_page" @click.prevent="next">Next</a>
							</div>
						</template>
					</div>
				</div>
			</div>
		</div>
	</teleport>
</script>
