<script type="text/html" id="membership-edit-plan">
	<div class="edit-cpt-header">
		<div class="ts-container cpt-header-container">
			<div class="ts-row wrap-row">
				<div class="ts-col-2-3 v-center">
					<h1>{{ plan.label }}
						<p>You are editing {{ plan.label }} plan</p>
					</h1>
				</div>
				<div class="cpt-header-buttons ts-col-1-3">
					<a href="#" class="ts-button ts-faded" @click.prevent="$root.activePlan = null">
						<i class="icon-sm las la-arrow-alt-circle-left"></i>
						Back to all plans
					</a>
					&nbsp;&nbsp;
					<a href="#" @click.prevent="save" class="ts-button ts-save-settings btn-shadow">
						<i class="las la-save icon-sm"></i>
						Save changes
					</a>
				</div>
			</div>
			<span class="ts-separator"></span>
		</div>
	</div>
	<div class="ts-theme-options ts-container">
		<div v-if="plan.key !== 'default'" class="ts-row wrap-row">
			<div class="ts-col-1-1">
				<ul class="inner-tabs">
					<li :class="{'current-item': tab === 'general'}">
						<a href="#" @click.prevent="tab = 'general'">
							General
						</a>
					</li>
					<li :class="{'current-item': tab === 'pricing'}">
						<a href="#" @click.prevent="tab = 'pricing'">
							Pricing
						</a>
					</li>
					<li :class="{'current-item': tab === 'more'}">
						<a href="#" @click.prevent="tab = 'more'">
							More
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="inner-tab ts-row wrap-row">
			<div class="ts-col-1-2">
				<div v-if="tab === 'general'" :class="{'vx-disabled': loading}">
					<div class="ts-row wrap-row">
						<div class="ts-tab-heading ts-col-1-1">
							<h1>General</h1>
							<p>General plan details</p>
						</div>

						<?php \Voxel\Form_Models\Key_Model::render( [
							'v-model' => 'plan.key',
							'label' => 'Key',
							'editable' => false,
						] ) ?>

						<?php \Voxel\Form_Models\Text_Model::render( [
							'v-model' => 'plan.label',
							'label' => 'Label',
						] ) ?>

						<?php \Voxel\Form_Models\Textarea_Model::render( [
							'v-model' => 'plan.description',
							'label' => 'Description',
						] ) ?>

						<div class="ts-form-group ts-col-1-1">
							<h2>Post submission limits</h2>
							<p>Set the post limit (by post type) that users with this plan can create.</p>
						</div>

						<div v-for="postTypeLimit, postType in plan.submissions" class="no-margin ts-col-1-1 ts-row wrap-row">
							<div class="ts-form-group ts-col-3-4">
								<label><strong>{{ $root.postTypes[ postType ] }}</strong> Limit</label>
								<input type="number" v-model="plan.submissions[postType]">
							</div>
							<div class="ts-form-group ts-col-1-4">
								<a class="ts-button ts-faded icon-only" href="#" @click.prevent="delete plan.submissions[ postType ]">Delete</a>
							</div>
						</div>

						<div class="no-margin ts-col-1-1 ts-row wrap-row">
							<div class="ts-form-group ts-col-3-4">
								<label>Choose post type</label>
								<select v-model="submissionValue">
									<template v-for="label, postType in $root.postTypes">
										<option v-if="!plan.submissions[postType]" :value="postType">{{ label }}</option>
									</template>
								</select>
							</div>
							<div class="ts-form-group ts-col-1-4">
								<a href="#" @click.prevent="addSubmission" class="ts-button ts-faded">Add</a>
							</div>
						</div>
					</div>
				</div>
				<div v-if="tab === 'pricing'" :class="{'vx-disabled': loading}">
					<div class="ts-row wrap-row">
						<div class="ts-tab-heading ts-col-1-1">
							<h1>Pricing</h1>
							<p>Plan pricing settings</p>
						</div>
						<div class="ts-col-1-1">
							<ul class="inner-tabs ts-col-1-1">
								<li :class="{'current-item': mode === 'live'}">
									<a href="#" @click.prevent="mode = 'live'">Live mode</a>
								</li>
								<li :class="{'current-item': mode === 'test'}">
									<a href="#" @click.prevent="mode = 'test'">Test mode</a>
								</li>
							</ul>
						</div>

						<div v-if="plan.pricing[mode].product_id" class="ts-col-1-1">
							<p>Stripe Product ID: {{ plan.pricing[mode].product_id }}</p>
							<div class="basic-ul">
								<a :href="stripeProductUrl()" target="_blank" class="ts-button ts-faded">View on Stripe Dashboard</a>
								<a href="#" @click.prevent="syncPrices" class="ts-button ts-faded">Sync prices with Stripe</a>
							</div>
						</div>

						<template v-if="plan.pricing[mode].prices.length">
							<div v-for="price in plan.pricing[mode].prices" class="ts-col-1-1">
								<div class="post-type-card">
									<h3>
										{{ price.currency.toUpperCase() }} {{ ( price.amount / 100 ).toLocaleString() }}
										<span v-if="price.type === 'recurring'">every {{ price.recurring.interval_count }} {{ price.recurring.interval }}s</span>
									</h3>
									<ul>
										<li>ID: {{ price.id }}</li>
										<li>Active: {{ price.active ? 'Yes' : 'No' }}</li>
									</ul>
									<a class="ts-button ts-faded" href="#" @click.prevent="togglePrice( price.id )">{{ price.active ? 'Disable' : 'Enable' }}</a>
								</div>
							</div>
						</template>
						<div v-else class="ts-col-1-1">
							<p>No prices created yet in {{ mode }} mode.</p>
						</div>

						<div class="ts-form-group ts-col-1-1">
							<h2>Add price</h2>
						</div>

						<?php \Voxel\Form_Models\Number_Model::render( [
							'v-model' => 'createPrice[mode].amount',
							'label' => 'Amount',
							'width' => '2/3',
						] ) ?>

						<?php \Voxel\Form_Models\Select_Model::render( [
							'v-model' => 'createPrice[mode].currency',
							'label' => 'Currency',
							'choices' => \Voxel\Stripe\Currencies::all(),
							'width' => '1/3',
						] ) ?>

						<?php \Voxel\Form_Models\Radio_Buttons_Model::render( [
							'v-model' => 'createPrice[mode].type',
							'label' => 'Type',
							'columns' => 'two',
							'choices' => [
								'recurring' => 'Recurring',
								'one_time' => 'One time',
							],
						] ) ?>

						<template v-if="createPrice[mode].type === 'recurring'">
							<div class="ts-form-group ts-col-1-1">
								<span>Billing period</span>
							</div>

							<?php \Voxel\Form_Models\Number_Model::render( [
								'v-model' => 'createPrice[mode].intervalCount',
								'label' => 'Every',
								'width' => '1/3',
							] ) ?>

							<?php \Voxel\Form_Models\Select_Model::render( [
								'v-model' => 'createPrice[mode].interval',
								'label' => 'Unit',
								'width' => '2/3',
								'choices' => [
									'day' => 'Day(s)',
									'week' => 'Week(s)',
									'month' => 'Month(s)',
								],
							] ) ?>
						</template>

						<div class="ts-col-1-1">
							<a href="#" @click.prevent="insertPrice" class="ts-button">Create</a>
						</div>
					</div>
				</div>
				<div v-if="tab === 'more'" :class="{'vx-disabled': loading}">
					<div class="ts-row wrap-row">
						<div v-if="plan.archived" class="ts-form-group ts-col-1-1">
							<a href="#" class="ts-button ts-faded" @click.prevent="archivePlan">Unarchive plan</a>
						</div>
						<div v-else class="ts-form-group ts-col-1-1">
							<a href="#" class="ts-button ts-faded" @click.prevent="archivePlan">Archive this plan</a>
						</div>

						<!-- <div class="ts-form-group ts-col-1-1">
							<pre debug>{{ plan }}</pre>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
