<script type="text/html" id="search-form-post-types-filter">
	<form-group class="choose-cpt-filter" popup-key="cpt-dropdown" ref="formGroup" @blur="onBlur" @save="onSave" :show-clear="false">
		<template #trigger>
			<label v-if="$root.config.showLabels"><?= _x( 'Post type', 'search form widget', 'voxel' ) ?></label>
	 		<div class="ts-filter ts-popup-target ts-filled" @mousedown="$root.activePopup = 'cpt-dropdown'">
				<span v-html="$root.post_type.icon"></span>
	 			<div class="ts-filter-text">{{ $root.post_type.label }}</div>
	 		</div>
	 	</template>
		<template #popup>
			<div class="ts-term-dropdown">
				<div class="ts-form-group">
					<div class="ts-input-icon flexify">
						<i aria-hidden="true" class="las la-search"></i>
						<input v-model="search" type="text" placeholder="Search post types" class="autofocus">
					</div>
				</div>
				<transition name="dropdown-popup" mode="out-in">
					<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
						<li v-for="post_type in postTypes">
							<a href="#" class="flexify" @click.prevent="selected = post_type.key">
								<span v-html="post_type.icon"></span>
								<p>{{ post_type.label }}</p>
								<div class="ts-radio-container">
									<label class="container-radio">
										<input type="radio" :checked="selected === post_type.key" disabled hidden>
										<span class="checkmark"></span>
									</label>
								</div>
							</a>
						</li>
						<li v-if="!postTypes.length">
							<a href="#" class="flexify" @click.prevent>
								<p><?= __( 'No post types found.', 'voxel' ) ?></p>
							</a>
						</li>
					</ul>
				</transition>
			</div>
		</template>
	</form-group>
</script>
