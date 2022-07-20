<script type="text/html" id="search-form-order-by-filter">
	<template v-if="filter.props.display_as === 'buttons'">
		<div v-for="choice in filter.props.choices" class="ts-form-group" :class="$attrs.class">
			<label v-if="$root.config.showLabels" class="">{{ choice.label }}</label>
			<div class="ts-filter" @click.prevent="selectChoice(choice)" :class="{'ts-filled': sortKey(filter.value) === choice.key}">
				<span v-html="choice.icon"></span>
				<div class="ts-filter-text">
					<span>{{ choice.label }}</span>
				</div>
			</div>
		</div>
	</template>
	<form-group v-else :popup-key="filter.id" ref="formGroup" @save="onSave" @clear="onClear" :class="$attrs.class">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
			<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = filter.id" :class="{'ts-filled': filter.value !== null}">
				<span v-html="filter.icon"></span>
				<div class="ts-filter-text">
					{{ filter.value ? displayValue : filter.label }}
				</div>
			</div>
		</template>
		<template #popup>
			<!-- <div class="ts-form-group">
				<label>{{ filter.label }}</label>
				<small v-if="filter.description">{{ filter.description }}</small>
			</div> -->

			<div class="ts-term-dropdown">
				<transition name="dropdown-popup" mode="out-in">
					<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
						<li v-for="choice in filter.props.choices">
							<a href="#" class="flexify" @click.prevent="selectDropdownChoice(choice)">
								<span v-html="choice.icon"></span>
								<p>{{ choice.label }}</p>

								<div class="ts-radio-container">
									<label class="container-radio">
										<input
											type="radio"
											:value="choice.key"
											:checked="sortKey(value) === choice.key"
											disabled
											hidden
										>
										<span class="checkmark"></span>
									</label>
								</div>
							</a>
						</li>
					</ul>
				</transition>
			</div>
		</template>
	</form-group>
</script>
