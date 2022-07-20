<script type="text/html" id="orders-single">
	<div class="ts-social-feed ts-single-order" :class="{'vx-pending': pending}">
		<!-- <template v-if="!order">
			<div v-if="loading" class="ts-no-posts">
				<?php \Voxel\render_icon( $this->get_settings('ts_order_icon') ) ?>
				<p>Loading requests</p>
			</div>
			<div v-else  class="ts-no-posts">
				<?php \Voxel\render_icon( $this->get_settings('ts_order_icon') ) ?>
				<p>No requests found</p>
			</div>
		</template> -->
		<transition name="fade">
			<div v-if="order">
				<div class="ts-status-list">
					<div class="ts-order-head">
						<display-actions :order="this" :order-details="order"></display-actions>
					</div>

					<div class="ts-status">
						<div class="ts-status-head flexify ts-single-order-head">
							<a :href="order.customer.link" v-html="order.customer.avatar"></a>
							<div>
								<a :href="order.customer.link">{{ order.customer.name }}</a>
								<span>sent a request on</span>
								<a :href="order.post.link">{{ order.post.title }}</a>
								<span class="ts-status-time">{{ order.time }}</span>
							</div>
						</div>
						<div class="ts-inner-status" :class="order.status.slug">
							<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_status'), [ 'aria-hidden' => 'true' ] ); ?>
							<p>{{ order.status.label }}</p>
						</div>

						<div class="order-cards">
							<booking-details v-if="booking" :order="this" :booking="booking"></booking-details>
							<div class="ts-order-card">
								<ul class="flexify simplify-ul">

									<li class="ts-card-icon">
										<i class="lab la-cc-stripe"></i>
									</li>
									<li>
										<small>Price</small>
										<p>{{ pricing.total }} <span v-if="pricing.period">/ {{ pricing.period }}</span></p>
									</li>
								</ul>
							</div>

							<div class="ts-order-card">
								<ul class="flexify simplify-ul">
									<li class="ts-card-icon">
										<i class="las la-hashtag"></i>
									</li>
									<li>
										<small>Order number</small>
										<p>#{{ order.id }}</p>
									</li>

								</ul>
							</div>

							<div v-for="addition in additions" class="ts-order-card">
								<ul class="flexify simplify-ul">
									<li class="ts-card-icon" v-html="addition.icon"></li>
									<li>
										<small>{{ addition.label }}</small>
										<p>{{ addition.content }}</p>
									</li>

								</ul>
							</div>
						</div>

						<div v-if="fields.length" class="order-info-container">
							<div class="order-info-head">
								<i class="las la-info-circle"></i>
								<p>Additional information</p>
								<a href="#" @click.prevent="state.showFields = !state.showFields" class="ts-icon-btn ts-smaller">
									<i aria-hidden="true" class="las la-angle-down"></i>
								</a>
							</div>
							<ul v-if="state.showFields" class="simplify-ul">
								<li v-for="field in fields">
									<small>{{ field.label }}</small>
									<p v-html="field.content"></p>
								</li>
							</ul>
						</div>

						<div v-if="order.role.is_customer && order.subscription.exists" class="order-info-container">
							<div class="order-info-head">
								<i class="las la-info-circle"></i>
								<subscription-status :order="this" :subscription="order.subscription"></subscription-status>
							</div>
						</div>

						<div v-if="pricing.additions.length" class="order-info-container">
							<div class="order-info-head">
								<i class="lar la-money-bill-alt"></i>
								<p>Price breakdown</p>
								<a href="#" @click.prevent="state.showPricing = !state.showPricing" class="ts-icon-btn ts-smaller">
									<i aria-hidden="true" class="las la-angle-down"></i>
								</a>
							</div>
							<ul v-if="state.showPricing" class="simplify-ul">
								<li v-if="pricing.additions.length">
									<small>Base price</small>
									<p>{{ pricing.base_price }}</p>
								</li>
								<li v-for="addition in pricing.additions">
									<small>{{ addition.label }}</small>
									<p>{{ addition.price }}</p>
								</li>
								<li class="ts-total">
									<small>Total</small>
									<p>{{ pricing.total }} <span v-if="pricing.period">/ {{ pricing.period }}</span></p>
								</li>
							</ul>
						</div>

						<div v-if="order.vendor_rules" class="order-info-container">
							<div class="order-info-head">
								<i class="las la-info-circle"></i>
								<p>Vendor notes</p>
								<a href="#" @click.prevent="state.showRules = !state.showRules" class="ts-icon-btn ts-smaller">
									<i aria-hidden="true" class="las la-angle-down"></i>
								</a>
							</div>
							<ul v-if="state.showRules" class="simplify-ul">
								<li>
									<p>{{ order.vendor_rules }}</p>
								</li>
							</ul>
						</div>

						<?php do_action( 'voxel/view-order/after-infoboxes' ) ?>
					</div>

					<div v-for="note in notes" class="ts-status ts-order-comment">
						<div class="post-divider">
							<span></span>
							<b></b>
							<span></span>
						</div>
						<display-note :note="note" :order="this"></display-note>
					</div>
				</div>

				<div class="ts-form ts-add-status">
					<div class="post-divider">
						<span></span>
						<b></b>
						<span></span>
					</div>
					<create-note :order="this"></create-note>
				</div>
			</div>
		</transition>
	</div>
</script>
