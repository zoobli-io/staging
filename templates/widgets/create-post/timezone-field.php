<script type="text/html" id="create-post-timezone-field">
	<form-group :popup-key="field.key" ref="formGroup" @save="onSave" @clear="onClear">
		<template #trigger>
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
			<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value !== null}" @mousedown="$root.activePopup = field.key">
				<span><i aria-hidden="true" class="lar la-clock"></i></span>
				<div class="ts-filter-text">
					<span>{{ field.value || field.props.default }}</span>
				</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-form-group elementor-column elementor-col-100">
				<div class="ts-input-icon flexify">
					<i aria-hidden="true" class="las la-search"></i>
					<input v-model="search" ref="searchInput" type="text" placeholder="Search timezones" class="autofocus">
				</div>
			</div>

			<div class="ts-term-dropdown ts-multilevel-dropdown">
				<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
					<li v-for="timezone in choices">
						<a href="#" class="flexify" @click.prevent="field.value = timezone">
							<span><i aria-hidden="true" class="lar la-clock"></i></span>
							<p>{{ timezone }}</p>

							<div class="ts-radio-container">
								<label class="container-radio">
									<input
										type="radio"
										:value="timezone"
										:checked="field.value === timezone"
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
