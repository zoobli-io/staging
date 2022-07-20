<script type="text/html" id="create-post-text-field">
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
</script>
