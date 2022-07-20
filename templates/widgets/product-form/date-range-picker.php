<script type="text/html" id="product-form-date-range-picker">
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
				<span v-if="startDate"> &mdash; </span>
				<a
					href="#"
					v-if="startDate"
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
