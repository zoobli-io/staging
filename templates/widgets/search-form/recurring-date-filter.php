<script type="text/html" id="search-form-recurring-date-filter">
	<form-group
		:popup-key="filter.id"
		ref="formGroup"
		@save="onSave"
		@clear="onClear"
		:wrapper-class="filter.props.inputMode === 'date-range' ? 'ts-availability-wrapper' : ''"
	>
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div
				v-if="filter.props.inputMode === 'single-date'"
				class="ts-filter ts-popup-target"
				@mousedown="$root.activePopup = filter.id"
				:class="{'ts-filled': filter.value !== null}"
			>
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text">{{ filter.value ? displayValue.start : filter.props.l10n.pickDate }}</div>
			</div>
			<div v-else class="ts-double-input flexify">
				<div
					class="ts-filter ts-popup-target"
					@mousedown="openRangePicker('start')"
					:class="{'ts-filled': filter.value !== null}"
				>
					<span v-html="filter.icon"></span>
					<div class="ts-filter-text">{{ filter.value ? displayValue.start : filter.props.l10n.from }}</div>
				</div>

				<div
					class="ts-filter"
					@mousedown="openRangePicker('end')"
					:class="{'ts-filled': filter.value !== null}"
				>
					<span v-html="filter.icon"></span>
					<div class="ts-filter-text">{{ filter.value ? displayValue.end : filter.props.l10n.to }}</div>
				</div>
			</div>
		</template>
		<template #popup>
			<date-picker
				v-if="filter.props.inputMode === 'single-date'"
				ref="picker"
				:filter="filter"
			></date-picker>
			<range-picker
				v-else
				ref="picker"
				:filter="filter"
			></range-picker>
		</template>
	</form-group>
</script>

<script type="text/html" id="recurring-date-range-picker">
	<div class="ts-popup-head flexify">
		<div class="ts-popup-name flexify">
			<i aria-hidden="true" class="las la-calendar"></i>
			<p>
				<a
					href="#"
					:class="{chosen: activePicker === 'start'}"
					@click.prevent="activePicker = 'start'"
				>
					{{ startLabel }}
				</a>
				<span v-if="value.start"> &mdash; </span>
				<a
					href="#"
					v-if="value.start"
					:class="{chosen: activePicker === 'end'}"
					@click.prevent="activePicker = 'end'"
				>
					{{ endLabel }}
				</a>
			</p>
		</div>
	</div>
	<div class="ts-booking-date ts-booking-date-range ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>

<script type="text/html" id="recurring-date-picker">
	<div class="ts-booking-date ts-booking-date-single ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>
