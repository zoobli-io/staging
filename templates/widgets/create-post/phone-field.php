<script type="text/html" id="create-post-phone-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		<input
			type="tel"
			v-model="field.value"
			:placeholder="field.props.placeholder"
			class="ts-filter"
		>
	</div>
</script>
