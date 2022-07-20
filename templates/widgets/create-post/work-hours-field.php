<script type="text/html" id="create-post-work-hours-field">
	<div class="ts-work-hours-field ts-form-group">
		
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>
		

		<template v-for="group, index in field.value">
			<form-group
				:ref="id(index)"
				class="work-hours-field ts-repeater-container"
				:popup-key="id(index)"
				:default-class="false"
				@clear="group.days = []"
				@save="$refs[id(index)].blur()"
			>
				<template #trigger>
					
					<div class="ts-field-repeater">
						<div class="ts-repeater-head">
							
							<label><i aria-hidden="true" class="las la-calendar-check"></i>Work days</label>
							<div class="ts-repeater-controller">
								<a href="#" @click.prevent="removeGroup(group)" class="ts-repeater-remove">
									<i aria-hidden="true" class="las la-trash"></i>
								</a>
							</div>
						</div>
						<div class="elementor-row">
							<div class="ts-form-group elementor-col-100 elementor-column">
								<label>Choose days<small></small></label>
								<div class="ts-filter ts-popup-target ts-datepicker-input" :class="{'ts-filled': group.days.length}" @mousedown="$root.activePopup = id(index)">
									<i aria-hidden="true" class="las la-calendar-check"></i>
									<div v-if="group.days.length" class="ts-filter-text">{{ displayDays( group.days ) }}</div>
									<div v-else class="ts-filter-text">Choose day(s)</div>
								</div>
							</div>
							<template v-if="group.days.length">	
								<form-group
									:ref="id(index, 'status')"
									:popup-key="id(index, 'status')"
									:show-clear="false"
									:show-save="false"
									class="elementor-column elementor-col-100"
								>
									<template #trigger>
										<label>Select work hours</label>
										<div class="ts-filter ts-popup-target ts-filled" @mousedown="$root.activePopup = id(index, 'status')">
											<i aria-hidden="true" class="lar la-clock"></i>
											<div class="ts-filter-text">{{ field.props.statuses[ group.status ] }}</div>
										</div>
									</template>
									<template #popup>
										<div class="ts-term-dropdown ts-multilevel-dropdown">
											<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
												<li v-for="label, status in field.props.statuses">
													<a href="#" @click.prevent="group.status = status; $refs[id(index, 'status')].blur();" class="flexify">
														<i aria-hidden="true" class="lar la-clock"></i>
														<p>{{ label }}</p>
														<div class="ts-radio-container">
															<label class="container-radio">
																<input :checked="group.status === status" type="radio">
																<span class="checkmark"></span>
															</label>
														</div>
													</a>
												</li>
											</ul>
										</div>
									</template>
								</form-group>
								<template v-if="group.status === 'hours'">

									<template v-if="group.hours.length">
										<div class="ts-form-group elementor-col-100 elementor-column">
												<label>Add work hours</label>
												<div v-for="hours in group.hours" class="ts-double-input has-controller flexify">
													<input type="time" class="ts-filter" v-model="hours.from">
													<input type="time" class="ts-filter" v-model="hours.to">
													<div class="ts-repeater-controller">
														<a href="#" @click.prevent="removeHours(hours, group)" class="ts-repeater-remove">
															<i aria-hidden="true" class="las la-trash"></i>
														</a>
													</div>
												</div>
										</div>
									</template>	
									<div class="ts-form-group elementor-col-100 elementor-column">
										<a href="#" @click.prevent="addHours(group)" class="ts-repeater-add add-hours ts-btn ts-btn-3">
											<i aria-hidden="true" class="lar la-clock"></i>
											{{ group.hours.length >= 1 ? 'Add additional hours' : 'Add hours' }}
										</a>
									</div>
								</template>
							</template>
						</div>
					</div>
				</template>
				<template #popup>
					<div class="ts-term-dropdown ts-multilevel-dropdown">
						<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
							<li v-for="label, key in field.props.weekdays">
								<a href="#" v-if="isDayAvailable( key, group )" @click.prevent="check( key, group.days )" class="flexify">
									<i aria-hidden="true" class="las la-calendar-check"></i>
									<p>{{ label }}</p>
									<div class="ts-checkbox-container">
										<label class="container-checkbox">
											<input :checked="isChecked( key, group.days )" type="checkbox">
											<span class="checkmark"></span>
										</label>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>
		</template>

		<a v-if="unusedDays.length" href="#" @click.prevent="addGroup" class="ts-repeater-add ts-btn ts-btn-3">
			<i aria-hidden="true" class="las la-plus"></i>
			Add work days
		</a>
	</div>
</script>
