<script type="text/html" id="create-post-location-field">
	<div class="ts-location-field">
		<div class="ts-form-group">
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
			<div class="ts-input-icon flexify">
				<i aria-hidden="true" class="las la-map-marker"></i>
				<input
					ref="addressInput"
					:value="field.value.address"
					:placeholder="field.props.placeholder"
					type="text"
					placeholder="Type an address"
					class="ts-filter"
				>
			</div>
		</div>
	
		<a href="#" class="ts-btn ts-btn-4 create-btn" @click.prevent="geolocate">
			<i aria-hidden="true" class="las la-location-arrow"></i>
			<p>Geolocate my address</p>
		</a>
		<div class="ts-form-group">
			<label>Pick the location manually?</label>
			<div class="switch-slider">
				<div class="onoffswitch">
					<input v-model="field.value.map_picker" type="checkbox" class="onoffswitch-checkbox">
					<label class="onoffswitch-label" @click.prevent="field.value.map_picker = !field.value.map_picker"></label>
				</div>
			</div>
		</div>
		<div class="ts-form-group" v-show="field.value.map_picker">
			<label>Pick on the map</label>
			<div class="location-field-map" ref="mapDiv"></div>
		</div>
		<div class="ts-form-group" v-show="field.value.map_picker">
			<div class="ts-double-input flexify">
				<div class="ts-input-icon flexify">
					<i aria-hidden="true" class="las la-map-marker"></i>
					<input v-model="field.value.latitude" type="number" max="90" min="-90" placeholder="Latitude" class="ts-filter">
				</div>
				<div class="ts-input-icon flexify">
					<i aria-hidden="true" class="las la-map-marker"></i>
					<input v-model="field.value.longitude" type="number" max="180" min="-180" placeholder="Longitude" class="ts-filter">
				</div>
			 </div>
		</div>
	</div>
</script>
