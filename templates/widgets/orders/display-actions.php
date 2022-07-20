<script type="text/html" id="orders-display-actions">
	<ul class="simplify-ul flexify">
		<li>
			<a href="#" @click.prevent="order.backToAll" class="ts-icon-btn">
				<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_sr_back'), [ 'aria-hidden' => 'true' ] ); ?>
			</a>
		</li>
		<li v-if="orderDetails.mode === 'payment' && orderDetails.role.is_author && orderDetails.status.slug === 'pending_approval'">
			<a href="#" @click.prevent="order.doAction('author.approve')" class="ts-btn ts-btn-2 ts-btn-large ts-approve-btn">
				<i :class="$root.config.actions['author.approve'].icon"></i>
				{{ $root.config.actions['author.approve'].label }}
			</a>
		</li>
		<li v-if="order.actions.length">
			<form-group popup-key="actions" ref="actions" :show-save="false" clear-label="Close" @clear="$event.blur()">
				<template #trigger>
					<a href="#" @mousedown="$root.activePopup = 'actions'" class="ts-icon-btn ts-popup-target">
						<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_sr_more'), [ 'aria-hidden' => 'true' ] ); ?>
					</a>
				</template>
				<template #popup>
					<div class="ts-term-dropdown">
						<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
							<li v-for="action in order.actions">
								<a v-if="$root.config.actions[ action ]" href="#" @click.prevent="order.doAction(action); $root.activePopup = null;" class="flexify">
									<span><i :class="$root.config.actions[ action ].icon"></i></span>
									<p>{{ $root.config.actions[ action ].label }}</p>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>
		</li>
	</ul>
</script>
