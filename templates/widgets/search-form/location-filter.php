<script type="text/html" id="search-form-location-filter">
	<form-group :popup-key="filter.id" ref="formGroup" @save="onSave" @clear="onClear" prevent-blur=".pac-container">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = filter.id; onOpen();" :class="{'ts-filled': filter.value !== null}">
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text">{{ filter.value ? displayValue : filter.label }}</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-form-group elementor-column elementor-col-100">
				<div class="ts-input-icon flexify" ref="addressWrapper">
					<i aria-hidden="true" class="las la-map-marker"></i>
				</div>
			</div>
			<div class="ts-form-group elementor-column elementor-col-100">
				<a @click.prevent="geolocate" href="#" class="ts-btn ts-btn-4">
					<i aria-hidden="true" class="las la-location-arrow"></i>
					<p>Use my current location</p>
				</a>
			</div>
		</template>
	</form-group>
</script>
