<script type="text/html" id="search-form-stepper-filter">
	<form-group :popup-key="filter.id" ref="formGroup" @save="onSave" @clear="onClear">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
	 		<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = filter.id" :class="{'ts-filled': filter.value !== null}">
				<span v-html="filter.icon"></span>
	 			<div class="ts-filter-text">
					{{ filter.value ? filter.value : filter.label }}
	 			</div>
	 		</div>
	 	</template>
		<template #popup>
			<div class="ts-form-group">
				<label>
					{{ filter.label }}
					<small v-if="filter.description">{{ filter.description }}</small>
				</label>
				
				<div class="ts-stepper-input flexify">
					<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement">
						<i aria-hidden="true" class="las la-minus"></i>
					</button>
					<input
						ref="input"
						v-model="value"
						type="number"
						class="ts-input-box"
						:min="filter.props.range_start"
						:max="filter.props.range_end"
						:step="filter.props.step_size"
						:placeholder="filter.props.placeholder"
					>
					<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment">
						<i aria-hidden="true" class="las la-plus"></i>
					</button>
				</div>
			</div>
		</template>
	</form-group>
</script>
