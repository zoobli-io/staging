<?php
require_once locate_template( 'templates/widgets/create-post/_media-popup.php' );
require_once locate_template( 'templates/widgets/timeline/create-status.php' );
require_once locate_template( 'templates/widgets/timeline/create-reply.php' );
require_once locate_template( 'templates/widgets/timeline/status-replies.php' );
require_once locate_template( 'templates/widgets/timeline/file-field.php' );
require_once locate_template( 'templates/widgets/timeline/review-score.php' );
require_once locate_template( 'templates/widgets/timeline/single-status.php' );
?>

<div class="ts-social-feed" data-config="<?= esc_attr( wp_json_encode( $config ) ) ?>">
	<template v-if="!isSingle">
		<div v-if="config.user.can_post" class="ts-form ts-add-status">
			<create-status class="ts-form-group ts-no-padding"></create-status>
		</div>

		<div v-if="orderingOptions.length >= 2" class="ts-timeline-tabs">
			<ul class="flexify simplify-ul ts-generic-tabs" :class="{'vx-inert': statuses.loading}">
				<li v-for="order in orderingOptions" :class="{'ts-tab-active': activeOrder === order}">
					<a @click.prevent="setActiveOrder(order)" href="#">{{ order.label }}</a>
				</li>
			</ul>
		</div>
	</template>
	<div class="ts-status-list">
		<template v-if="!statuses.list.length">
			<div class="ts-no-posts">
					<?php \Voxel\render_icon( $this->get_settings('ts_create_icon') ) ?>
				<p>{{ statuses.loading ? 'Loading...' : 'No posts to show.' }}</p>
			</div>
		</template>
		<template v-else>
			<single-status v-for="status, index in statuses.list" :status="status" :index="index"></single-status>
			<a
				href="#"
				v-if="statuses.hasMore"
				@click.prevent="statuses.page++; getStatuses();"
				class="ts-load-more ts-btn ts-btn-1"
				:class="{'vx-pending': statuses.loading}"
			>
				<?php \Voxel\render_icon( $this->get_settings('ts_timeline_load_icon') ); ?>
				Load more
			</a>
		</template>
	</div>
</div>
