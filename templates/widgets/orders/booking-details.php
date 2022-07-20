<script type="text/html" id="orders-booking-details">
	<template v-if="booking.type === 'date_range'">
		<div class="ts-order-card">
			<ul class="flexify simplify-ul">
				<li class="ts-card-icon">
					<i class="lar la-calendar"></i>
				</li>
				<li>
					<small>Check-in</small>
					<p>{{ booking.from }}</p>
				</li>
				
			</ul>
		</div>
		<div class="ts-order-card">
			<ul class="flexify simplify-ul">
				<li class="ts-card-icon">
					<i class="lar la-calendar"></i>
				</li>
				<li>
					<small>Check-out</small>
					<p>{{ booking.to }}</p>
				</li>
				
			</ul>
		</div>
	</template>
	<template v-else-if="booking.type === 'timeslot'">
		<div class="ts-order-card">
			<ul class="flexify simplify-ul">
				<li class="ts-card-icon">
					<i class="lar la-calendar"></i>
				</li>
				<li>
					<small>Date</small>
					<p>{{ booking.date }}</p>
				</li>
				
			</ul>
		</div>
		<div class="ts-order-card">
			<ul class="flexify simplify-ul">
				<li class="ts-card-icon">
					<i class="las la-clock"></i>
				</li>
				<li>
					<small>Timeslot</small>
					<p>{{ booking.from }} to {{ booking.to }}</p>
				</li>
				
			</ul>
		</div>
	</template>
	<template v-else>
		<div class="ts-order-card">
			<ul class="flexify simplify-ul">
				<li class="ts-card-icon">
					<i class="lar la-calendar"></i>
				</li>
				<li>
					<small>Date</small>
					<p>{{ booking.date }}</p>
				</li>
				
			</ul>
		</div>
	</template>
</script>
