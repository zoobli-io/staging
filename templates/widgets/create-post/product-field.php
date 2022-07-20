<script type="text/html" id="create-post-product-field">
	<div class="ts-form-group ts-product-field">
		<!-- <div class="ts-form-group">
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
		</div> -->

		<div v-if="!field.required" class="ts-form-group">
			<label>{{ field.label }}<small>{{ field.description }}</small></label>

			<div class="switch-slider">
				<div class="onoffswitch">
				    <input type="checkbox" class="onoffswitch-checkbox" v-model="enabled">
				    <label class="onoffswitch-label" @click.prevent="enabled = !enabled"></label>
				</div>
			</div>
		</div>
		<template v-if="enabled">
			<template v-if="field.props.is_using_price_id">
				<div class="ts-form-group">
					<label>Price ID</label>
					<div class="input-container">
						<input type="text" class="ts-filter" v-model="price_id">
					</div>
				</div>
			</template>
			<template v-else>
				<div class="ts-form-group">
					<label>Base price</label>
					<div class="input-container">
						<input type="number" class="ts-filter" v-model="base_price" placeholder="e.g 49" min="0">
						<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?></span>
					</div>
				</div>

				<div v-if="field.props.mode === 'subscription'" class="ts-form-group elementor-column elementor-col-100">
					<label>Repeat every</label>
					<div class="ts-double-input flexify">
						<input v-model="interval.count" type="number" class="ts-filter" min="1" :max="field.props.interval_limits[ interval.unit ] || 365">
						<form-group
							:popup-key="field.key+'.interval'"
							:ref="field.key+'.interval'"
							class="ts-filter"
							:default-class="false"
							@mousedown="$root.activePopup = field.key+'.interval'"
							:show-clear="false"
							:show-save="false"
						>
							<template #trigger>
								<i aria-hidden="true" class="las la-calendar-check"></i>
								<div class="ts-filter-text">{{ field.props.intervals[ interval.unit ] }}</div>
							</template>
							<template #popup>
								<div class="ts-term-dropdown">
									<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
										<li v-for="unit_label, unit in field.props.intervals">
											<a href="#" class="flexify" @click.prevent="interval.unit = unit; $refs[field.key+'.interval'].blur()">
												<p>{{ unit_label }}</p>
												<div class="ts-checkbox-container">
													<label class="container-radio">
														<input type="radio" :value="unit" :checked="interval.unit === unit" disabled hidden>
														<span class="checkmark"></span>
													</label>
												</div>
											</a>
										</li>
									</ul>
								</div>
							</template>
						</form-group>
					</div>
				</div>
			</template>

			<template v-if="field.props.calendar_type === 'booking'">
				<div class="ts-form-group">
					<div class="ts-double-input flexify">
						<div class="ts-form-group">
							<label>Make available the next</label>
							<div class="input-container">
								<input type="number" class="ts-filter" placeholder="e.g 30" v-model="calendar.make_available_next">
								<span class="input-suffix">days</span>
							</div>
						</div>

						<div v-if="field.props.calendar_format === 'days'" class="ts-form-group">
							<label>Instances per day</label>
							<input type="number" class="ts-filter" v-model="calendar.bookable_per_instance">
						</div>

						<div v-if="field.props.calendar_format === 'slots'" class="ts-form-group">
							<label>Instances per timeslot</label>
							<input type="number" class="ts-filter" v-model="calendar.bookable_per_instance">
						</div>

					</div>
				</div>
				<div v-if="field.props.calendar_format === 'slots'" class="ts-form-group ts-product-timeslots">
					<field-product-timeslots></field-product-timeslots>
				</div>

				<form-group v-if="field.props.calendar_format === 'days'" :popup-key="field.key+'.weekdays'" ref="weekdayExclusions" @save="saveWeekdayExclusions" @clear="clearWeekdayExclusions">
					<template #trigger>
						<label>Exclude days of week</label>
						<div class="ts-filter ts-popup-target" :class="{'ts-filled': state.weekdays_display_value.length}" @mousedown="$root.activePopup = field.key+'.weekdays'">
							<span><i aria-hidden="true" class="lar la-bookmark"></i></span>
							<div class="ts-filter-text">{{
								state.weekdays_display_value || <?= wp_json_encode( _x( 'Click to exclude weekdays', 'product field', 'voxel' ) ) ?>
							}}</div>
						</div>
					</template>
					<template #popup>
						<div class="ts-term-dropdown ts-multilevel-dropdown">
							<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
								<li v-for="day_label, day_key in field.props.weekdays">
									<a href="#" class="flexify" @click.prevent="toggleWeekdayExclusion( day_key )">
										<span><i aria-hidden="true" class="lar la-bookmark"></i></span>
										<p>{{ day_label }}</p>
										<div class="ts-checkbox-container">
											<label class="container-checkbox">
												<input type="checkbox" :value="day_key" :checked="state.excluded_weekdays[day_key]" disabled hidden>
												<span class="checkmark"></span>
											</label>
										</div>
									</a>
								</li>
							</ul>
						</div>
					</template>
				</form-group>

				<div class="ts-form-group">
					<label>Calendar <small>You can click on dates to change the availability</small></label>
					<field-product-calendar ref="datePicker"></field-product-calendar>
				</div>
			</template>
			<template v-else-if="field.props.calendar_type === 'recurring-date'">
				<div class="ts-form-group">
					<div class="ts-double-input flexify">
						<div class="ts-form-group">
							<label>Make available the next</label>
							<div class="input-container">
								<input type="number" class="ts-filter" placeholder="e.g 30" v-model="calendar.make_available_next" min="0">
								<span class="input-suffix">days</span>
							</div>
						</div>

						<div class="ts-form-group">
							<label>Instances per date</label>
							<input type="number" class="ts-filter" v-model="calendar.bookable_per_instance">
						</div>
					</div>
				</div>

				<div class="ts-form-group elementor-col-100">
					<label>Upcoming bookable dates<small>No upcoming bookable dates.</small></label>
					<ul v-if="state.recurring_dates.length" class="timeslot-list simplify-ul">
						<li v-for="date in state.recurring_dates">
							<i class="lar la-check-circle"></i>
							<span>
								{{ formatRecurrence( date ) }}
							</span>
							
						</li>
					</ul>
				
				</div>
			</template>

			<template v-if="!field.props.is_using_price_id">
				<div v-for="addition in field.props.additions" class="ts-form-group ts-addition">
					<label>
						{{ addition.label }}
						<small>{{ addition.description }}</small>
					</label>
					<div class="switch-slider" v-if="!addition.required">
						<div class="onoffswitch">
							<input type="checkbox" class="onoffswitch-checkbox" v-model="addition.values.enabled">
							<label class="onoffswitch-label" @click.prevent="addition.values.enabled = !addition.values.enabled"></label>
						</div>
					</div>
					<div v-if="addition.required || addition.values.enabled">
						<template v-if="addition.type === 'checkbox'">
							<div class="ts-form-group">
								<label>Price</label>
								<div class="input-container">
									<input type="number" v-model="addition.values.price" class="ts-filter" placeholder="e.g 30" min="0">
									<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?></span>
								</div>
							</div>
						</template>
						<template v-if="addition.type === 'numeric'">
							<div class="ts-form-group">
								<div class="input-container">
									<input type="number" v-model="addition.values.price" class="ts-filter" placeholder="Price per unit" min="0">
									<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?> per unit</span>
								</div>
							</div>
							<div class="ts-double-input flexify product-units">
								<div class="ts-form-group">
									<div class="input-container">
										<input
											type="number"
											v-model="addition.values.min"
											class="ts-filter"
											placeholder="Minimum"
											min="0"
										>
										<span class="input-suffix">Min units</span>
									</div>
								</div>
								<div class="ts-form-group">
									<div class="input-container">
										<input
											type="number"
											v-model="addition.values.max"
											class="ts-filter"
											placeholder="Maximum"
											min="0"
										>
										<span class="input-suffix">Max units</span>
									</div>
								</div>
							</div>
						</template>
						<template v-if="addition.type === 'select'">
							<template v-for="choice in addition.choices">
								<div class="ts-form-group">
									<label>{{ choice.label }}</label>
									<div class="switch-slider">
										<div class="onoffswitch">
											<input type="checkbox" class="onoffswitch-checkbox" v-model="addition.values.choices[choice.value].enabled">
											<label class="onoffswitch-label" @click.prevent="addition.values.choices[choice.value].enabled = !addition.values.choices[choice.value].enabled"></label>
										</div>
									</div>
								</div>
								<div v-if="addition.values.choices[choice.value].enabled" class="ts-form-group">
									<label>Price</label>
									<div class="input-container">
										<input type="number" v-model="addition.values.choices[ choice.value ].price" class="ts-filter" min="0">
										<span class="input-suffix"><?= \Voxel\get('settings.stripe.currency') ?></span>
									</div>
								</div>
							</template>
						</template>
					</div>
				</div>
			</template>

			<div v-if="field.props.notes && field.props.notes.enabled" class="ts-form-group ts-product-notes">
				<label>
					{{ field.props.notes.label }}
					<small>{{ field.props.notes.description }}</small>
				</label>
				<textarea v-model="notes" class="ts-filter min-scroll" :placeholder="field.props.notes.placeholder" rows="5"></textarea>
			</div>
		</template>
	</div>
</script>

<script type="text/html" id="create-post-product-timeslots">
	<label>Timeslots</label>
	<div class="ts-repeater-container">
		<div v-for="slotGroup, groupIndex in timeslots" class="ts-field-repeater">
			<div class="ts-repeater-head">
				<label>Time slot group</label>
				<div class="ts-repeater-controller">
					<a href="#" @click.prevent="removeGroup(slotGroup)" class="ts-repeater-remove">
						<i aria-hidden="true" class="las la-trash"></i>
					</a>
				</div>
			</div>

			<form-group
				:popup-key="groupKey(groupIndex)"
				:ref="groupKey(groupIndex)"
				class="ts-form-group"
				@save="saveDays(groupIndex)"
				@clear="clearDays(slotGroup)"
			>
				<template #trigger>
					<label>Choose days<small>Choose the days this time slot group applies to</small></label>
				
					<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = groupKey(groupIndex)">
						<span><i aria-hidden="true" class="las la-calendar-check"></i></span>
						<div class="ts-filter-text">{{ daysLabel(
							slotGroup,
							<?= wp_json_encode( _x( 'Choose day(s)', 'product field', 'voxel' ) ) ?>
						) }}</div>
					</div>
				</template>
				<template #popup>
					<div class="ts-term-dropdown ts-multilevel-dropdown">
						<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
							<li v-for="day_label, day_key in field.props.weekdays">
								<a
									href="#"
									class="flexify"
									v-if="isDayAvailable( day_key, slotGroup )"
									@click.prevent="toggleDay( day_key, slotGroup )"
								>
									<span><i aria-hidden="true" class="lar la-bookmark"></i></span>
									<p>{{ day_label }}</p>
									<div class="ts-checkbox-container">
										<label class="container-checkbox">
											<input type="checkbox" :value="day_key" :checked="isDayUsed( day_key, slotGroup )" disabled hidden>
											<span class="checkmark"></span>
										</label>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</form-group>

			<div class="ts-form-group">
				<label>Add time slots</label>
				<div class="ts-double-input flexify">
					<form-group
						save-label="Add"
						:show-clear="true"
						clear-label="Close"
						:popup-key="groupKey(groupIndex, 'add')"
						:ref="groupKey(groupIndex, 'add')"
						:default-class="false"
						@save="addSlot(slotGroup, groupIndex)"
						@clear="closeSlotPopup(groupIndex)"
					>
						<template #trigger>
							<a
								href="#"
								class="ts-btn ts-btn-3 ts-popup-target"
								@mousedown="$root.activePopup = groupKey(groupIndex, 'add')"
							>
								<i aria-hidden="true" class="las la-plus"></i>
								Add timeslot
							</a>
						</template>
						<template #popup>
							<div class="ts-form-group">
								<label>Time range</label>
								<div class="ts-double-input flexify">
									<input type="time" v-model="create.from" class="ts-filter">
									<input type="time" v-model="create.to" class="ts-filter">
								</div>
							</div>
						</template>
					</form-group>

					<form-group
						save-label="Generate"
						:show-clear="true"
						clear-label="Close"
						:popup-key="groupKey(groupIndex, 'generate')"
						:ref="groupKey(groupIndex, 'generate')"
						:default-class="false"
						@save="generateSlots(slotGroup, groupIndex)"
						@clear="closeGeneratePopup(groupIndex)"
					>
						<template #trigger>
							<a
								href="#"
								class="ts-btn ts-btn-3 ts-popup-target"
								@mousedown="$root.activePopup = groupKey(groupIndex, 'generate')"
							>
								<i aria-hidden="true" class="lar la-list-alt"></i>
								Generate timeslots
							</a>
						</template>
						<template #popup>
							<div class="ts-form-group elementor-col-100 elementor-column">
								<label>Time range</label>
								<div class="ts-double-input flexify">
									<input type="time" v-model="generate.from" class="ts-filter">
									<input type="time" v-model="generate.to" class="ts-filter">
								</div>
							</div>
							<div class="ts-form-group elementor-col-100 elementor-column">
								<label>Slot length (in minutes)</label>
								<input type="number" v-model="generate.length" class="ts-filter" min="5">
							</div>
						</template>
					</form-group>
				</div>
			</div>
			<div v-if="slotGroup.slots.length" class="ts-form-group elementor-col-100 elementor-column">
				<label>
					Available time slots
					<small>Time slots you created</small>
				</label>
				<ul class="timeslot-list simplify-ul">
					<li v-for="slot, slotIndex in slotGroup.slots">
						<a href="#" @click.prevent="removeSlot(slot, slotGroup)" class="delete-timeslot">
							<i aria-hidden="true" class="las la-minus-circle"></i>
						</a>
						<span>{{ displaySlot(slot) }}</span>
						
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a v-if="unusedDays.length" href="#" @click.prevent="addSlotGroup" class="ts-repeater-add ts-btn ts-btn-3">
		<i aria-hidden="true" class="las la-plus"></i>
		Add timeslot group
	</a>
</script>
