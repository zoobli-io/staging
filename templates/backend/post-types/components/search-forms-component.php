<?php
/**
 * Search filters - component template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<script type="text/html" id="post-type-search-forms-template">
	<div class="ts-tab-content ts-container">
		<div class="ts-row wrap-row h-center">
			<div class="ts-col-3-4">
				<div class="ts-tab-heading">
					<h1>Search filters</h1>
					<p>Create filters available for this post type.</p>
				</div>
				<ul class="inner-tabs">
					<li :class="{'current-item': $root.subtab === 'general'}">
						<a href="#" @click.prevent="$root.setTab('filters', 'general')">Search filters</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'order'}">
						<a href="#" @click.prevent="$root.setTab('filters', 'order')">Search order</a>
					</li>
					<li :class="{'current-item': $root.subtab === 'status'}">
						<a href="#" @click.prevent="$root.setTab('filters', 'status')">Indexing status</a>
					</li>
				</ul>
		
				<div v-if="$root.subtab === 'general'" class="inner-tab fields-layout">
					<search-filters></search-filters>
				</div>
				<div v-if="$root.subtab === 'order'" class="inner-tab fields-layout">
					<search-order></search-order>
				</div>
				<div v-if="$root.subtab === 'status'" class="inner-tab fields-layout">
					<div v-if="!$root.indexing.loaded">
						<p>Loading data...</p>
						{{ $root.getIndexData() }}
					</div>
					<div v-else>
						<div v-if="$root.indexing.running">
							<p>{{ $root.indexingStatus }}</p>
						</div>
						<div v-else class="post-type-card">
							<p>Published posts: {{ $root.indexing.items_total }}</p>
							<p>Indexed posts: {{ $root.indexing.items_indexed }}</p>
							<p>DB table: {{ $root.indexing.table_name }}</p>
							<a class="ts-button ts-faded" href="#" @click.prevent="$root.indexPosts">Index all posts</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>