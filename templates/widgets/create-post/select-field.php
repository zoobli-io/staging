<script type="text/html" id="create-post-select-field">
	<form-group :popup-key="field.key" ref="formGroup" @save="$refs.formGroup.blur()" @clear="field.value = null">
		<template #trigger>
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
			<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value !== null}" @mousedown="$root.activePopup = field.key">
				<span><i aria-hidden="true" class="las la-check-circle"></i></span>
				<div class="ts-filter-text">
					<span>{{ field.props.choices[ field.value ] ? field.props.choices[ field.value ].label : field.props.placeholder }}</span>
				</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-term-dropdown ts-multilevel-dropdown">
				<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
					<li v-for="choice in field.props.choices">
						<a href="#" class="flexify" @click.prevent="field.value = choice.value">
							<span v-if="choice.icon" v-html="choice.icon"></span>
							<span v-else><i aria-hidden="true" class="las la-angle-right"></i></span>

							<p>{{ choice.label }}</p>
							<div class="ts-radio-container">
								<label class="container-radio">
									<input
										type="radio"
										:value="choice.value"
										:checked="field.value === choice.value"
										disabled
										hidden
									>
									<span class="checkmark"></span>
								</label>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</template>
	</form-group>
</script>
