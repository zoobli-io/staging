<?php
/**
 * Admin membership settings.
 *
 * @since 1.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
} ?>

<div class="wrap">
	<div id="vx-membership-settings" v-cloak data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
		<edit-plan v-if="activePlan"></edit-plan>
		<div class="vx-plans-container" v-else>
			<div class="edit-cpt-header">
				<div class="ts-container cpt-header-container">
					<div class="ts-row wrap-row">
						<div class="ts-col-2-3 v-center">
							<h1>Pricing plans
								<p>Membership plans you created. </p>
							</h1>
						</div>
						<div class="cpt-header-buttons ts-col-1-3">
							<a href="<?= esc_url( $add_plan_url ) ?>" class="ts-button ts-save-settings btn-shadow">
								<i class="las la-plus icon-sm"></i>
								Create plan
							</a>
						</div>
					</div>
					<span class="ts-separator"></span>
				</div>
			</div>
			<div class="ts-theme-options ts-container">
				<div class="ts-col-1-1">
					<div class="ts-row wrap-row">
						<div v-if="plans.length" class="ts-row wrap-row ts-col-1-1">
							<template v-for="plan in plans">
								<div v-if="!plan.archived" class="ts-col-1-3">
									<div class="post-type-card">
										<i class="las la-briefcase"></i>
										<h3>{{ plan.label }}</h3>
										<ul>
											<li>Key: {{ plan.key }}</li>
										</ul>
										<a href="#" @click.prevent="activePlan = plan" class="ts-button ts-faded">
											<i class="las la-pen icon-sm"></i>Edit plan
										</a>
										<!-- <pre debug>{{ plan }}</pre> -->
									</div>
								</div>
							</template>
						</div>
						<div v-else>
							<p>No plans created yet.</p>
						</div>
					</div>
				</div>

				<div v-if="archivedPlans.length" class="ts-col-1-1" style="margin-top: 50px;">
					<div class="ts-row wrap-row">
						<div class="ts-form-group ts-row ts-col-1-1">
							<div class="ts-col-1-1">
								<a href="#" v-if="!showArchive" @click.prevent="showArchive = true">
									<i class="las la-arrow-down"></i>
									Show archived plans
								</a>
								<a href="#" v-else @click.prevent="showArchive = false">
									<i class="las la-arrow-up"></i>
									Hide archived plans
								</a>
							</div>
						</div>
						<div v-if="showArchive" class="ts-row wrap-row ts-col-1-1">
							<template v-for="plan in archivedPlans">
								<div class="ts-col-1-3">
									<div class="post-type-card">
										<i class="las la-briefcase"></i>
										<h3>{{ plan.label }}</h3>
										<ul>
											<li>Key: {{ plan.key }}</li>
										</ul>
										<a href="#" @click.prevent="activePlan = plan" class="ts-button ts-faded">
											<i class="las la-pen icon-sm"></i>Edit plan
										</a>
										<!-- <pre debug>{{ plan }}</pre> -->
									</div>
								</div>
							</template>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once locate_template('templates/backend/membership/edit-plan.php') ?>
