<script type="text/html" id="timeline-create-status">
	<form-group
		:popup-key="popupKey"
		ref="popup"
		:save-label="status ? 'Update' : 'Publish'"
		clear-label="Cancel"
		prevent-blur=".ts-media-library"
		@save="publish"
		@clear="cancel"
		:wrapper-class="pending ? 'status-pending' : ''"
	>
		<template #trigger>
			<div v-if="!status" class="ts-filter ts-popup-target" @mousedown="$root.activePopup = popupKey">
				<?php \Voxel\render_icon( $this->get_settings('ts_create_icon') ) ?>
				<div class="ts-filter-text"><?= $this->get_settings_for_display('add_status_text') ?></div>
			</div>
		</template>
		<template #popup>
			<div class="ts-popup-head flexify">
				<div class="ts-popup-name flexify">
					<span v-html="$root.config.user.avatar"></span>
					<p>{{ $root.config.user.name }}</p>
				</div>
			</div>
			<div class="ts-compose-textarea">
				<textarea
					v-model="message"
					placeholder="What's on your mind?"
					class="autofocus min-scroll"
					:maxlength="$root.config.postSubmission.maxlength"
				></textarea>
			</div>
			<field-file
				v-show="$root.config.postSubmission.gallery"
				:field="files"
				:sortable="false"
				ref="files"
				class="ts-status-files"
			></field-file>
			<div v-if="$root.mode === 'post_reviews'" class="ts-term-dropdown ts-form-group ts-review-field">
				<label>Your rating</label>
				<ul class="simplify-ul flexify">
					<li v-for="level in $root.ratingLevels" :class="[rating === level.score && 'rating-selected', level.key]">
						<a href="#" @click.prevent="toggleRating(level)" class="flexify">
							<span v-html="level.icon"></span>
							<p>{{ level.label }}</p>
						</a>
					</li>
				</ul>
			</div>
		</template>
	</form-group>
</script>
