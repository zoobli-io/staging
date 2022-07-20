<script type="text/html" id="create-post-repeater-field">
	<div class="ts-form-group ts-repeater">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>

		<div class="ts-repeater-container">
			<div v-for="row, row_index in rows" class="ts-field-repeater">
				<div class="ts-repeater-head">
					
					<!-- <label><i class="las la-bars"></i>Item</label> -->
					<div class="ts-repeater-controller">
						<a href="#" @click.prevent="deleteRow(row_index)" class="ts-repeater-remove">
							<i aria-hidden="true" class="las la-trash"></i>
						</a>
					</div>
				</div>
				<template v-for="subfield in row">
					<component
						:field="subfield"
						:is="'field-'+subfield.type"
						:ref="'row#'+row_index+':'+subfield.key"
						v-if="$root.conditionsPass(subfield)"
					></component>
				</template>

			
			</div>
		</div>
		

		<a href="#" class="ts-repeater-add ts-btn ts-btn-3" @click.prevent="addRow">
			<i class="las la-plus"></i>
			Add row
		</a>
		<!-- <pre debug>{{ $data }}</pre> -->
	</div>
</script>
