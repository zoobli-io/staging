<div class="ts-form">
	<div class="create-form-step">
		<div class="ts-form-group">
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
			<input
				v-model="field.value"
				:placeholder="field.props.placeholder"
				type="text"
				class="ts-filter"
			>
		</div>
	</div>
	<div class="ts-form-footer flexify">
		<a href="#" class="ts-btn ts-btn-2 ts-btn-large">
			Submit order
		</a>
	</div>
</div>