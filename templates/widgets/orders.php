<?php
/**
 * Orders widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
}

require_once locate_template( 'templates/widgets/orders/single-order.php' );
require_once locate_template( 'templates/widgets/create-post/_media-popup.php' );
require_once locate_template( 'templates/widgets/orders/booking-details.php' );
require_once locate_template( 'templates/widgets/orders/create-note.php' );
require_once locate_template( 'templates/widgets/orders/display-actions.php' );
require_once locate_template( 'templates/widgets/orders/display-note.php' );
require_once locate_template( 'templates/widgets/orders/subscription-status.php' );
?>

<div v-cloak class="ts-orders" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<template v-if="activeOrder">
		<single-order :order-id="activeOrder"></single-order>
	</template>
	<template v-else>
		<!-- <span class="ts-empty-box">
			<i class="las la-box"></i>
			<p>Loading order</p>
		</span> -->
		<div class="ts-form ts-order-filters min-scroll min-scroll-h">
			<form-group popup-key="type" ref="type" @clear="setType('all'); $refs.type.blur();" @save="$refs.type.blur();">
				<template #trigger>
					<div class="ts-filter ts-popup-target ts-filled" :class="{'ts-filled': type !== 'all'}" @mousedown="$root.activePopup = 'type'">
						<i class="las la-exchange-alt"></i>
						<div class="ts-filter-text">{{ type === 'incoming' ? 'Incoming' : ( type === 'outgoing' ? 'Outgoing' : 'All requests' ) }}</div>
					</div>
				</template>
				<template #popup>
					<div class="ts-term-dropdown">
						<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
							<li>
								<a href="#" class="flexify" @click.prevent="setType('all')">
									<span>
										<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_all_requests'), [ 'aria-hidden' => 'true' ] ); ?>
									</span>
									<p>All requests</p>
									<div class="ts-checkbox-container">
										<label class="container-radio">
											<input type="radio" :checked="type === 'all'" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
								</a>
							</li>
							<li>
								<a href="#" class="flexify" @click.prevent="setType('incoming')">
									<span>
										<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_incoming'), [ 'aria-hidden' => 'true' ] ); ?>
									</span>
									<p>Incoming</p>
									<div class="ts-checkbox-container">
										<label class="container-radio">
											<input type="radio" :checked="type === 'incoming'" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
								</a>
							</li>
							<li>
								<a href="#" class="flexify" @click.prevent="setType('outgoing')">
									<span>
										<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_outgoing'), [ 'aria-hidden' => 'true' ] ); ?>
									</span>
									<p>Outgoing</p>
									<div class="ts-checkbox-container">
										<label class="container-radio">
											<input type="radio" :checked="type === 'outgoing'" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>

			<form-group popup-key="status" ref="status" @clear="setStatus('all'); $refs.status.blur();" @save="$refs.status.blur();">
				<template #trigger>
					<div class="ts-filter ts-popup-target" :class="{'ts-filled': status !== 'all'}" @mousedown="$root.activePopup = 'status'">
						<span>
							<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_status'), [ 'aria-hidden' => 'true' ] ); ?>
						</span>
						<div class="ts-filter-text">{{ status === 'all' ? 'Any status' : config.statuses[ status ] }}</div>
					</div>
				</template>
				<template #popup>
					<div class="ts-term-dropdown">
						<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
							<li v-for="status_label, status_key in config.statuses">
								<a href="#" class="flexify" @click.prevent="setStatus( status_key )">
									<span>
										<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_status'), [ 'aria-hidden' => 'true' ] ); ?>
									</span>
									<p>{{ status_label }}</p>
									<div class="ts-checkbox-container">
										<label class="container-radio">
											<input type="radio" :value="status_key" :checked="status === status_key" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>

			<form-group popup-key="search" ref="search" @clear="search = ''; searchOrders(); $refs.search.blur();" @save="searchOrders(); $refs.search.blur();">
				<template #trigger>
					<div class="ts-filter ts-popup-target" :class="{'ts-filled': search.trim().length}" @mousedown="$root.activePopup = 'search'">
						<span>
							<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_order_keyword'), [ 'aria-hidden' => 'true' ] ); ?>
						</span>
						<div class="ts-filter-text">{{ search.trim().length ? search.trim() : 'Search' }}</div>
					</div>
				</template>
				<template #popup>
					<div class="ts-form-group">
						<div class="ts-input-icon flexify">
							<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_order_keyword'), [ 'aria-hidden' => 'true' ] ); ?>
							<input v-model="search" type="text" placeholder="Search orders">
						</div>
					</div>
				</template>
			</form-group>
		</div>

		<transition name="fade">
			<div v-if="orders.length">
				<div class="orders-flex" :class="{'vx-disabled': loading}">
					<div class="ts-order-item" v-for="order in orders" @click.prevent="viewOrder( order.id )">
						<div class="data-con">
							<span v-html="order.customer.avatar"></span>
						</div>
						<div class="data-con">
							<p>{{ order.customer.name }}</p><span>sent a</span><p>{{ order.price }}</p><span>request {{ order.time }}</span><!-- <p>#{{ order.id }}</p> -->
						</div>
						<div class="ts-order-status"  :class="order.status.slug">
							<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_status'), [ 'aria-hidden' => 'true' ] ); ?>
							<p>{{ order.status.label }}</p>
						</div>
					</div>
				</div>

				<div class="orders-pagination flexify" v-if="page > 1 || hasMore">
					<a href="#" class="ts-btn ts-btn-1" :class="{'vx-disabled': page <= 1}" @click.prevent="page -= 1; getOrders();">
						<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('rpag_prev_icon'), [ 'aria-hidden' => 'true' ] ); ?>
						Previous
					</a>
					<a href="#" class="ts-btn ts-btn-1 ts-btn-large btn-icon-right" :class="{'vx-disabled': !hasMore}" @click.prevent="page += 1; getOrders();">
						Next
						<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('rpag_next_icon'), [ 'aria-hidden' => 'true' ] ); ?>
					</a>
				</div>
			</div>
			<div v-else>
				<div v-else  class="ts-no-posts">
					<?php \Voxel\render_icon( $this->get_settings('ts_all_requests') ) ?>
					<p>No requests found</p>
				</div>
			</div>
		</transition>
	</template>
</div>
