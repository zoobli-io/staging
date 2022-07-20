<script type="text/html" id="create-post-number-field">
	<div v-if="field.props.display === 'stepper'" class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<div class="ts-stepper-input flexify">
			<button class="ts-stepper-left ts-icon-btn" @click.prevent="decrement">
				<i aria-hidden="true" class="las la-minus"></i>
			</button>
			<input
				v-model="field.value"
				type="number"
				class="ts-input-box"
				:min="field.props.min"
				:max="field.props.max"
				:step="field.props.step"
				:placeholder="field.props.placeholder"
			>
			<button class="ts-stepper-right ts-icon-btn" @click.prevent="increment">
				<i aria-hidden="true" class="las la-plus"></i>
			</button>
		</div>
	</div>
	<div v-else class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<input
			v-model="field.value"
			:placeholder="field.props.placeholder"
			type="number"
			class="ts-filter"
		>
	</div>
</script>
