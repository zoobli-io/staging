<script type="text/html" id="search-form-terms-filter">
	<form-group :popup-key="filter.id" ref="formGroup" @save="onSave" @clear="onClear">
		<template #trigger>
			<label v-if="$root.config.showLabels" class="">{{ filter.label }}</label>
	 		<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = filter.id" :class="{'ts-filled': filter.value !== null}">
				<span v-html="filter.icon"></span>
	 			<div class="ts-filter-text">
	 				<template v-if="filter.value">
	 					{{ firstLabel }}
	 					<span v-if="remainingCount > 0" class="term-count">
	 						+{{ remainingCount.toLocaleString() }}
	 					</span>
	 				</template>
	 				<template v-else>{{ filter.label }}</template>
	 			</div>
	 		</div>
	 	</template>
		<template #popup>
			<div class="ts-form-group">
				<!-- <label>{{ filter.label }}</label>
				<small v-if="filter.description">{{ filter.description }}</small> -->
				<div class="ts-input-icon flexify">
					<i aria-hidden="true" class="las la-search"></i>
					<input v-model="search" ref="searchInput" type="text" placeholder="Search categories" class="autofocus">
				</div>
			</div>

			<div v-if="searchResults" class="ts-term-dropdown ts-multilevel-dropdown">
				<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
					<li v-for="term in searchResults">
						<a href="#" class="flexify" @click.prevent="selectTerm( term )">
							<span v-html="term.icon || filter.props.default_icon"></span>
							<p>{{ term.label }}</p>

							<div class="ts-checkbox-container">
								<label class="container-checkbox">
									<input
										type="checkbox"
										:value="term.slug"
										:checked="value[ term.slug ]"
										disabled
										hidden
									>
									<span class="checkmark"></span>
								</label>
							</div>
						</a>
					</li>
					<li v-if="!searchResults.length">
						<a href="#" class="flexify" @click.prevent>
							<p><?= __( 'No terms found.', 'voxel' ) ?></p>
						</a>
					</li>
				</ul>
			</div>
			<div v-else class="ts-term-dropdown ts-multilevel-dropdown">
				<term-list :terms="terms" list-key="toplevel" key="toplevel"></term-list>
			</div>

			<!-- <pre debug>{{ { active_list, slide_from, selected, value } }}</pre> -->
		</template>
	</form-group>
</script>

<script type="text/html" id="search-form-terms-filter-list">
	<transition :name="'slide-from-'+termsFilter.slide_from" @beforeEnter="beforeEnter">
		<ul
			v-if="termsFilter.active_list === listKey"
			:key="listKey"
			class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll"
			ref="list"
		>
			<li v-if="termsFilter.active_list !== 'toplevel'" class="term-dropdown-back">
				<a href="#" class="flexify" @click.prevent="goBack">
					<i aria-hidden="true" class="las la-angle-left"></i>
					<p>Go back</p>
				</a>
			</li>
			<li v-if="parentTerm" class="ts-parent-item">
				<a href="#" class="flexify" @click.prevent="termsFilter.selectTerm( parentTerm )">
					<span v-html="parentTerm.icon || termsFilter.filter.props.default_icon"></span>
					<p>{{ parentTerm.label }}</p>
					<div class="ts-checkbox-container">
						<label class="container-checkbox">
							<input
								type="checkbox"
								:value="parentTerm.slug"
								:checked="termsFilter.value[ parentTerm.slug ]"
								disabled
								hidden
							>
							<span class="checkmark"></span>
						</label>
					</div>
				</a>
			</li>

			<li v-for="term in terms">
				<a href="#" class="flexify" @click.prevent="selectTerm( term )">
					<span v-html="term.icon || termsFilter.filter.props.default_icon"></span>
					<p>{{ term.label }}</p>

					<div v-if="!(term.children && term.children.length)" class="ts-checkbox-container">
						<label class="container-checkbox">
							<input
								type="checkbox"
								:value="term.slug"
								:checked="termsFilter.value[ term.slug ]"
								disabled
								hidden
							>
							<span class="checkmark"></span>
						</label>
					</div>

					<span
						v-if="term.children && term.children.length"
						aria-hidden="true"
						class="las la-angle-right ts-has-children-icon"
					></span>
				</a>
			</li>
		</ul>
	</transition>
	<term-list
		v-for="term in termsWithChildren"
		:terms="term.children"
		:parent-term="term"
		:previous-list="listKey"
		:list-key="'terms_'+term.id"
		:key="'terms_'+term.id"
	></term-list>
</script>
