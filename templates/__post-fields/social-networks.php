<!-- Social networks-->
<form-group class="ts-form-group" popup-key="add-social-link">
	<template #trigger>
	<label class="">Social networks</label>
	<small>Experience everything we have in store</small>
	<!-- repeater fields-->
	<div class="ts-repeater-container">
		<div class="ts-double-input flexify ts-auto-width">
		 	<div class="ts-filter" readonly @mousedown="$root.activePopup = 'add-social-link'">
					<i aria-hidden="true" class="las la-link"></i>
		 			<div class="ts-filter-text">Enter URL</div>
	 			</div>
	 		<div class="ts-repeater-controller">
			 		<a href="#" class="ts-icon-btn"><i aria-hidden="true" class="lar la-trash-alt"></i></a>
			 	</div>
		</div>
	</div>
	<!-- repeater button-->
 	<a href="#" class="ts-repeater-add ts-btn ts-btn-3"><i aria-hidden="true" class="las la-link"></i>Add social link</a>
 	</template>
 	<template #popup>
 		<div class="ts-form-group elementor-column elementor-col-100">
 			<div class="ts-input-icon flexify">
 				<i aria-hidden="true" class="las la-link"></i>
 				<input type="text" placeholder="Enter URL" class="autofocus">
 			</div>
 		</div>
		<div class="ts-term-dropdown">

		   <ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
		   	  <li class="ts-term-heading"><a href="#" class="flexify"><p>Choose social network</p></a></li>
		      <li>
		         <a href="#a" class="flexify">
		         	<i aria-hidden="true" class="las la-link"></i>
		            <p>Facebook</p>
		            <div class="ts-radio-container">
		            	<label class="container-radio"><input type="radio" value="restaurants">
		            	<span class="checkmark"></span>
		            </label></div>
		            <!--v-if-->
		         </a>
		      </li>
		      <li>
		         <a href="#a" class="flexify">
		            <i aria-hidden="true" class="las la-link"></i>
		            <p>Twitter</p>
		            <div class="ts-radio-container"><label class="container-radio"><input type="radio" value="restaurants"><span class="checkmark"></span></label></div>
		            <!--v-if-->
		         </a>
		      </li>
		      <li>
		         <a href="#a" class="flexify">
		            <i aria-hidden="true" class="las la-link"></i>
		            <p>Linkedin</p>
		            <div class="ts-radio-container"><label class="container-radio"><input type="radio" value="restaurants"><span class="checkmark"></span></label></div>
		            <!--v-if-->
		         </a>
		      </li>
		      <li>
		         <a href="#a" class="flexify">
		            <i aria-hidden="true" class="las la-link"></i>
		            <p>Youtube</p>
		             <div class="ts-radio-container"><label class="container-radio"><input type="radio" value="restaurants"><span class="checkmark"></span></label></div>
		            <!--v-if-->
		         </a>
		      </li>
		      <li>
		         <a href="#a" class="flexify">
		            <i aria-hidden="true" class="las la-link"></i>
		            <p>Patreon</p>
		             <div class="ts-radio-container"><label class="container-radio"><input type="radio" value="restaurants"><span class="checkmark"></span></label></div>
		            <!--v-if-->
		         </a>
		      </li>

		   </ul>
		</div>

		
 	</template>
</form-group>