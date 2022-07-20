<script type="text/html" id="search-form-user-filter">
	<form-group :popup-key="filter.id" ref="formGroup" v-if="filter.value !== null">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div class="ts-filter ts-popup-target" :class="{'ts-filled': filter.value !== null}">
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text">
					<!-- <span v-if="filter.props.user.avatar" v-html="filter.props.user.avatar"></span> -->
					{{ filter.props.user.name || 'Unknown' }}
				</div>
			</div>
		</template>
	</form-group>
</script>
