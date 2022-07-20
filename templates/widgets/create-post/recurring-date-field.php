<script type="text/html" id="create-post-recurring-date-field">
	<div class="ts-form-group">
		<label>
			{{ field.label }}
			<small>{{ field.description }}</small>
		</label>

		<template v-for="date, index in field.value">
			<div class="ts-repeater-container">
				

				<div v-if="field.props.allow_recurrence" class="ts-field-repeater">
					<div class="ts-repeater-head">
						<!-- <label><i class="lar la-calendar"></i>Date</label> -->
						<div class="ts-repeater-controller">
							<a href="#" @click.prevent="remove(date)" class="ts-repeater-remove">
								<i aria-hidden="true" class="las la-trash"></i>
							</a>
						</div>
					</div>
					<div class="elementor-row">
						<form-group
							:popup-key="id(index,'from')"
							:ref="id(index,'from')"
							class="elementor-column elementor-col-100"
							wrapper-class="ts-availability-wrapper"
							@mousedown="$root.activePopup = id(index,'from')"
							@save="$refs[id(index,'from')].blur()"
							@clear="clearDate(date)"
						>
							<template #trigger>
								<div class="ts-double-input flexify">
									<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value !== null}">
										<i aria-hidden="true" class="las la-calendar-minus"></i>
										<div class="ts-filter-text">
										{{ ! getStartDate(date)
											? 'From'
											: ( field.props.enable_timepicker
												? format( getStartDate( date ) )
												: formatDate( getStartDate( date ) )
											) }}
										</div>
									</div>

									<div class="ts-filter" :class="{'vx-disabled': !getStartDate(date), 'ts-filled': field.value !== null}">
										<i aria-hidden="true" class="las la-calendar-minus"></i>
										<div class="ts-filter-text">
										{{ ! getEndDate(date)
											? 'From'
											: ( field.props.enable_timepicker
												? format( getEndDate( date ) )
												: formatDate( getEndDate( date ) )
											) }}
										</div>
									</div>
								</div>
							</template>
							<template #popup>
								<date-range-picker ref="rangePicker" v-model:start="date.startDate" v-model:end="date.endDate"></date-range-picker>
								<div v-if="field.props.enable_timepicker" class="ts-double-input flexify">
									<div class="ts-form-group">
										<label>Start time</label>
										<input type="time" v-model="date.startTime" class="ts-filter">
									</div>

									<div class="ts-form-group">
										<label>End time</label>
										<input type="time" v-model="date.endTime" class="ts-filter">
									</div>
								</div>
							</template>
						</form-group>

						<div class="ts-form-group inner-form-group">
							<label>Enable recurrence?</label>
							<div class="switch-slider">
								<div class="onoffswitch">
									<input type="checkbox" v-model="date.repeat" class="onoffswitch-checkbox">
									<label class="onoffswitch-label" @click.prevent="date.repeat=!date.repeat"></label>
								</div>
							</div>
						</div>

						<template v-if="date.repeat">
							<div class="ts-form-group elementor-column elementor-col-100">
								<label>Repeat every</label>
								<div class="ts-double-input flexify">
									<input v-model="date.frequency" type="number" class="ts-filter">
									<form-group
										:popup-key="id(index,'unit')"
										:ref="id(index,'unit')"
										class="ts-filter"
										:default-class="false"
										@mousedown="$root.activePopup = id(index,'unit')"
										:show-clear="false"
										:show-save="false"
									>
										<template #trigger>
											<i aria-hidden="true" class="las la-calendar-check"></i>
											<div class="ts-filter-text">{{ field.props.units[ date.unit ] }}</div>
										</template>
										<template #popup>
											<div class="ts-term-dropdown">
												<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
													<li v-for="unit_label, unit in field.props.units">
														<a href="#" class="flexify" @click.prevent="date.unit = unit; $refs[id(index,'unit')].blur()">
															<p>{{ unit_label }}</p>
															<div class="ts-checkbox-container">
																<label class="container-radio">
																	<input type="radio" :value="unit" :checked="date.unit === unit" disabled hidden>
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
							<form-group
								:popup-key="id(index,'until')"
								:ref="id(index,'until')"
								class="elementor-column elementor-col-100"
								@clear="date.until = null"
								@save="$refs[id(index,'until')].blur()"
							>
								<template #trigger>
									<label>Until</label>
									<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = id(index,'until')">
										<i aria-hidden="true" class="las la-calendar-check"></i>
										<div class="ts-filter-text">
											{{ getUntilDate(date) ? formatDate(getUntilDate(date)) : 'Choose date' }}
										</div>
									</div>
								</template>
								<template #popup>
									<date-picker v-model="date.until"></date-picker>
								</template>
							</form-group>
						</template>
					</div>
				</div>
			</div>
		</template>

		<a
			href="#"
			v-if="field.value.length < field.props.max_date_count"
			@click.prevent="add"
			class="ts-repeater-add ts-btn ts-btn-3"
		>
			<i class="las la-plus"></i>
			Add date
		</a>

		<!-- <pre debug>{{ field.value }}</pre> -->
	</div>
</script>

<script type="text/html" id="recurring-date-picker">
	<div class="ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>

<script type="text/html" id="recurring-date-range-picker">
	<div class="ts-popup-head flexify">
		<div class="ts-popup-name flexify">
			<i aria-hidden="true" class="las la-calendar"></i>
			<p>
				<a
					href="#"
					:class="{chosen: activePicker === 'start'}"
					@click.prevent="activePicker = 'start'"
				>
					{{ startLabel }}
				</a>
				<span v-if="value.start"> &mdash; </span>
				<a
					href="#"
					v-if="value.start"
					:class="{chosen: activePicker === 'end'}"
					@click.prevent="activePicker = 'end'"
				>
					{{ endLabel }}
				</a>
			</p>
		</div>
	</div>
	<div class="ts-booking-date ts-booking-date-range ts-form-group" ref="calendar">
		<input type="hidden" ref="input">
	</div>
</script>
