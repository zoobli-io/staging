<script type="text/html" id="create-post-taxonomy-field">
	<form-group :popup-key="field.key" ref="formGroup" @blur="onBlur" @save="onSave" @clear="onClear">
		<template #trigger>
			<label>
				{{ field.label }}
				<small>{{ field.description }}</small>
			</label>
	 		<div class="ts-filter ts-popup-target" :class="{'ts-filled': field.value !== null}" @mousedown="$root.activePopup = field.key">
				<span><i aria-hidden="true" class="las la-list-alt"></i></span>
	 			<div class="ts-filter-text">
	 				<span v-if="field.value !== null">{{ displayValue }}</span>
	 				<span v-else>{{ field.props.placeholder }}</span>
	 			</div>
	 		</div>
	 	</template>
		<template #popup>
			<div class="ts-form-group elementor-column elementor-col-100">
				<div class="ts-input-icon flexify">
					<i aria-hidden="true" class="las la-search"></i>
					<input v-model="search" ref="searchInput" type="text" placeholder="Search categories" class="autofocus">
				</div>
			</div>

			<div v-if="searchResults" class="ts-term-dropdown ts-multilevel-dropdown">
				<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
					<li v-for="term in searchResults">
						<a href="#" class="flexify" @click.prevent="selectTerm( term )">
							<span v-html="term.icon || field.props.default_icon"></span>
							<p>{{ term.label }}</p>

							<div class="ts-checkbox-container">
								<label :class="field.props.multiple ? 'container-checkbox' : 'container-radio'">
									<input
										:type="field.props.multiple ? 'checkbox' : 'radio'"
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
		</template>
	</form-group>
</script>

<script type="text/html" id="create-post-term-list">
	<transition :name="'slide-from-'+taxonomyField.slide_from" @beforeEnter="beforeEnter">
		<ul
			v-if="taxonomyField.active_list === listKey"
			:key="listKey"
			class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll"
			ref="list"
		>
			<li v-if="taxonomyField.active_list !== 'toplevel'" class="term-dropdown-back">
				<a href="#" class="flexify" @click.prevent="goBack">
					<i aria-hidden="true" class="las la-angle-left"></i>
					<p>Go back</p>
				</a>
			</li>
			<li v-if="parentTerm" class="ts-parent-item">
				<a href="#" class="flexify" @click.prevent="taxonomyField.selectTerm( parentTerm )">
					<span v-html="parentTerm.icon || taxonomyField.field.props.default_icon"></span>
					<p>{{ parentTerm.label }}</p>
					<div class="ts-checkbox-container">
						<label :class="taxonomyField.field.props.multiple ? 'container-checkbox' : 'container-radio'">
							<input
								:type="taxonomyField.field.props.multiple ? 'checkbox' : 'radio'"
								:value="parentTerm.slug"
								:checked="taxonomyField.value[ parentTerm.slug ]"
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
					<span v-html="term.icon || taxonomyField.field.props.default_icon"></span>
					<p>{{ term.label }}</p>

					<div v-if="!(term.children && term.children.length)" class="ts-checkbox-container">
						<label :class="taxonomyField.field.props.multiple ? 'container-checkbox' : 'container-radio'">
							<input
								:type="taxonomyField.field.props.multiple ? 'checkbox' : 'radio'"
								:value="term.slug"
								:checked="taxonomyField.value[ term.slug ]"
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
