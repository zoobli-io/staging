<script type="text/html" id="search-form-keywords-filter">
	<form-group :popup-key="filter.id" ref="formGroup" @save="onSave" @clear="onClear">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = filter.id" :class="{'ts-filled': filter.value !== null}">
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text">{{ filter.value ? filter.value : filter.label }}</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-form-group">
				<!-- <label>{{ filter.label }}</label>
				<small v-if="filter.description">{{ filter.description }}</small> -->
				<div class="ts-input-icon flexify">
					<i aria-hidden="true" class="las la-search"></i>
					<input
						ref="input"
						v-model="value"
						type="text"
						placeholder="Type your keywords"
						class="autofocus"
						@keyup.enter="onSave"
					>
				</div>
			</div>
		</template>
	</form-group>
</script>
