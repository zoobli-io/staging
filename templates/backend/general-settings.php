<?php
/**
 * Admin general settings.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<div class="wrap">
	<div id="vx-general-settings" class="vx-use-vue" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>" v-cloak>
		<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ) ?>" @submit="state.submit_config = JSON.stringify( config )">
			<div class="edit-cpt-header">
				<div class="ts-container cpt-header-container">
					<div class="ts-row wrap-row">
						<div class="ts-col-1-2 v-center ">
							<h1>General Settings</h1>
						</div>
						<div class="cpt-header-buttons ts-col-1-2 v-center">
							<input type="hidden" name="config" :value="state.submit_config">

							<input type="hidden" name="action" value="voxel_save_general_settings">
							<?php wp_nonce_field( 'voxel_save_general_settings' ) ?>

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
				<div class="ts-row wrap-row">
					<div class="ts-col-1-3">
						<ul class="inner-tabs vertical-tabs">
							<li :class="{'current-item': tab === 'membership'}">
								<a href="#" @click.prevent="tab = 'membership'">Membership</a>
							</li>
							<li :class="{'current-item': tab === 'stripe'}">
								<a href="#" @click.prevent="tab = 'stripe'">Stripe</a>
							</li>
							<li :class="{'current-item': tab === 'stripe.portal'}">
								<a href="#" @click.prevent="tab = 'stripe.portal'">Stripe Customer Portal</a>
							</li>
							<li :class="{'current-item': tab === 'maps.google_maps'}">
								<a href="#" @click.prevent="tab = 'maps.google_maps'">Google Maps</a>
							</li>
							<li :class="{'current-item': tab === 'auth.google'}">
								<a href="#" @click.prevent="tab = 'auth.google'">Login with Google</a>
							</li>
							<li :class="{'current-item': tab === 'recaptcha'}">
								<a href="#" @click.prevent="tab = 'recaptcha'">Recaptcha</a>
							</li>
							<li :class="{'current-item': tab === 'timeline'}">
								<a href="#" @click.prevent="tab = 'timeline'">Timeline</a>
							</li>
						</ul>
					</div>

					<div v-if="tab === 'recaptcha'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Google recaptcha v3</h1>
							<p>Configure Google reCAPTCHA in the <a href="https://www.google.com/recaptcha/admin" target="_blank">v3 Admin Console</a></p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.recaptcha.enabled',
								'label' => 'Enable reCAPTCHA',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.recaptcha.key',
								'label' => 'Site key',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.recaptcha.secret',
								'label' => 'Secret key',
							] ) ?>
						</div>
					</div>

					<div v-else-if="tab === 'stripe'" class="ts-col-2-3">
						<div class="ts-tab-heading no-top-space">
							<h1>Stripe</h1>
							<p>Add your Stripe account details</p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.stripe.currency',
								'label' => 'Currency',
								'choices' => \Voxel\Stripe\Currencies::all(),
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.stripe.key',
								'label' => 'Public key',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.stripe.secret',
								'label' => 'Secret key',
							] ) ?>

							<div class="ts-tab-subheading ts-col-1-1">
								<h3>Test mode</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.stripe.test_mode',
								'label' => 'Enable test mode',
							] ) ?>

							<template v-if="config.stripe.test_mode">
								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'config.stripe.test_key',
									'label' => 'Test public key',
								] ) ?>

								<?php \Voxel\Form_Models\Text_Model::render( [
									'v-model' => 'config.stripe.test_secret',
									'label' => 'Test secret key',
								] ) ?>
							</template>


							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.stripe.webhook_secret',
								'label' => 'Webhook secret',
							] ) ?>
						</div>
					</div>

					<div v-if="tab === 'stripe.portal'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Stripe Customer Portal</h1>
							<p>Stripe customer portal allows your customers to edit their payment methods, view invoice history, and update their customer details.</p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.stripe.portal.invoice_history',
								'label' => 'Show invoice history',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.stripe.portal.customer_update.enabled',
								'label' => 'Allow updating details',
							] ) ?>

							<?php \Voxel\Form_Models\Checkboxes_Model::render( [
								'v-if' => 'config.stripe.portal.customer_update.enabled',
								'v-model' => 'config.stripe.portal.customer_update.allowed_updates',
								'label' => 'Allowed fields',
								'choices' => [
									'email' => 'Email',
									'address' => 'Billing address',
									'shipping' => 'Shipping address',
									'phone' => 'Phone numbers',
									'tax_id' => 'Tax IDs',
								],
							] ) ?>
						</div>
					</div>

					<div v-if="tab === 'membership'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Membership</h1>
							<p>Configure registration and membership</p>
						</div>
						<div class="ts-row wrap-row">
							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Registration</h3>
							</div>
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.enabled',
								'label' => 'Enable user registration',
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.membership.after_registration',
								'label' => 'After registration is complete',
								'choices' => [
									'welcome_step' => 'Show welcome screen',
									'redirect_back' => 'Redirect back where the user left off',
								],
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Membership</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.plans_enabled',
								'label' => 'Enable membership plans',
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.membership.update.proration_behavior',
								'label' => 'Proration behavior when switching between subscription plans',
								'choices' => [
									'create_prorations' => 'Create prorations',
									'always_invoice' => 'Create prorations and invoice immediately',
									'none' => 'Disable prorations',
								],
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'config.membership.cancel.behavior',
								'label' => 'When a cancel request is submitted, cancel the subscription:',
								'choices' => [
									'at_period_end' => 'At the end of current billing period',
									'immediately' => 'Immediately',
								],
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.membership.trial.enabled',
								'label' => 'Enable free trial',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-if' => 'config.membership.trial.enabled',
								'v-model' => 'config.membership.trial.period_days',
								'label' => 'Trial period days',
							] ) ?>
						</div>
					</div>

					<div v-else-if="tab === 'auth.google'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Login with Google</h1>
							<p>Configure project and retrieve client id & secret in the <a href="https://console.cloud.google.com/home" target="_blank">Google API Console</a></p>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.auth.google.enabled',
								'label' => 'Enable Login with Google',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.auth.google.client_id',
								'label' => 'Client ID',
							] ) ?>

							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.auth.google.client_secret',
								'label' => 'Client secret',
							] ) ?>
						</div>
					</div>

					<div v-else-if="tab === 'maps.google_maps'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Google Maps</h1>
						</div>
						<div class="ts-row wrap-row">
							<?php \Voxel\Form_Models\Text_Model::render( [
								'v-model' => 'config.maps.google_maps.api_key',
								'label' => 'Api key',
							] ) ?>
						</div>
					</div>

					<div v-if="tab === 'timeline'" class="ts-col-1-2">
						<div class="ts-tab-heading no-top-space">
							<h1>Timeline</h1>
						</div>
						<div class="ts-row wrap-row">

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Statuses</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.timeline.posts.editable',
								'label' => 'Allow editing',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.maxlength',
								'label' => 'Max length (in characters)',
							] ) ?>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.timeline.posts.images.enabled',
								'label' => 'Allow image attachments',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-if' => 'config.timeline.posts.images.enabled',
								'v-model' => 'config.timeline.posts.images.max_count',
								'label' => 'Max image count',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-if' => 'config.timeline.posts.images.enabled',
								'v-model' => 'config.timeline.posts.images.max_size',
								'label' => 'Max image size (in kB)',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Replies</h3>
							</div>

							<?php \Voxel\Form_Models\Switcher_Model::render( [
								'v-model' => 'config.timeline.replies.editable',
								'label' => 'Allow editing',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.maxlength',
								'label' => 'Max length (in characters)',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.max_nest_level',
								'label' => 'Highest reply nesting level',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Post rate limiting</h3>
								<p>Limit the number of statuses a user can publish in a time period</p>
							</div>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.rate_limit.time_between',
								'label' => 'Minimum time between posts (in seconds)',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.rate_limit.hourly_limit',
								'label' => 'Maximum number of posts allowed in an hour',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.posts.rate_limit.daily_limit',
								'label' => 'Maximum number of posts allowed in a day',
							] ) ?>

							<div class="ts-form-group ts-col-1-1 ts-tab-subheading">
								<h3>Reply rate limiting</h3>
								<p>Limit the number of replies a user can publish in a time period</p>
							</div>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.rate_limit.time_between',
								'label' => 'Minimum time between replies (in seconds)',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.rate_limit.hourly_limit',
								'label' => 'Maximum number of replies allowed in an hour',
							] ) ?>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'config.timeline.replies.rate_limit.daily_limit',
								'label' => 'Maximum number of replies allowed in a day',
							] ) ?>
						</div>
					</div>

					<!-- <div class="ts-col-1-1">
						<pre debug>{{ config }}</pre>
					</div> -->
				</div>

				<div class="ts-tab-content ts-container">
					<div class="ts-row wrap-row h-center">
						
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
