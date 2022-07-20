<script type="text/html" id="search-form-open-now-filter">
	<form-group v-if="filter.props.openInPopup" :popup-key="filter.id" ref="formGroup" @save="onSave" @clear="value = false">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = filter.id" :class="{'ts-filled': filter.value !== null}">
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text"><span>{{ filter.label }}</span></div>
			</div>
		</template>
		<template #popup>
			<div class="ts-form-group">
				<label>{{ filter.label }}<small v-if="filter.description">{{ filter.description }}</small></label>
				
				<div class="switch-slider">
					<div class="onoffswitch">
						<input
							:checked="value"
							type="checkbox"
							class="onoffswitch-checkbox"
							tabindex="0"
						>
						<label class="onoffswitch-label" @click.prevent="value=!value"></label>
					</div>
				</div>
			</div>
		</template>
	</form-group>
	<div v-else class="ts-form-group">
		<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
		<div class="ts-filter" @click.prevent="toggle" :class="{'ts-filled': filter.value !== null}">
			<span v-html="filter.icon"></span>
			<div class="ts-filter-text">
				<span>{{ filter.label }}</span>
			</div>
		</div>
	</div>
</script>
