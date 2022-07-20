<script type="text/html" id="search-form-range-filter">
	<form-group :popup-key="filter.id" ref="formGroup" @save="onSave" @clear="onClear">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div
				class="ts-filter ts-popup-target"
				@mousedown="$root.activePopup = filter.id; onEntry();"
				:class="{'ts-filled': filter.value !== null}"
			>
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text">
					{{ filter.value ? displayValue : filter.label }}
				</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-form-group">
				<label>{{ filter.label }}</label>
				<small v-if="filter.description">{{ filter.description }}</small>

				<div class="range-slider-wrapper" ref="sliderWrapper">
					<div class="range-value">{{ popupDisplayValue }}</div>
				</div>
			</div>
		</template>
	</form-group>
</script>
