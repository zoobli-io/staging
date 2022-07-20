<script type="text/html" id="timeline-create-reply">
	<form-group
		:popup-key="popupKey"
		ref="popup"
		:save-label="reply ? 'Update' : 'Post comment'"
		clear-label="Cancel"
		@save="publish"
		@clear="cancel"
		class="ts-form"
		:wrapper-class="pending ? 'reply-pending' : ''"
	>
		<template #trigger>
			<div v-if="showTrigger" class="ts-filter ts-popup-target" @mousedown="$root.activePopup = popupKey">
				<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_comment_icon') ) ?>
				<div class="ts-filter-text">Post a comment</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-popup-head flexify">
				<div class="ts-popup-name flexify">
					<?php if ( is_user_logged_in() ): ?>
						<?= \Voxel\current_user()->get_avatar_markup() ?>
						<p><?= \Voxel\current_user()->get_display_name() ?></p>
					<?php endif ?>
				</div>
			</div>
			<div class="ts-compose-textarea">
				<textarea
					v-model="message"
					placeholder="Your comment"
					class="autofocus min-scroll"
					:maxlength="$root.config.replySubmission.maxlength"
				></textarea>
			</div>
		</template>
	</form-group>
</script>
