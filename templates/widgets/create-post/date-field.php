<script type="text/html" id="create-post-date-field">

	<form-group
		:popup-key="field.id"
		:ref="field.id"
		@save="$refs[field.id].blur()"
		@clear="field.value.date = null; field.value.time = null;"
	>	

		<template #trigger>
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
			<div class="ts-filter ts-popup-target" :class="{'ts-filled': displayDate !== null}" @mousedown="$root.activePopup = field.id">
		 		<i aria-hidden="true" class="las la-calendar-check"></i>
				<div class="ts-filter-text">
					{{ displayDate || field.props.placeholder }}
				</div>
			</div>
		</template>
		<template #popup>
			<date-picker v-model="field.value.date"></date-picker>
			<div v-if="field.props.enable_timepicker" class="ts-form-group">
				<label>Time</label>
				<input type="time" v-model="field.value.time" class="ts-filter">
			</div>
		</template>
	</form-group>
</script>

<script type="text/html" id="create-post-date-field-picker">
	<div class="ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>
