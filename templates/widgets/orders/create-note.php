<script type="text/html" id="orders-create-note">
	<form-group
		popup-key="commentForm"
		ref="commentForm"
		class="ts-no-padding"
		save-label="Post Comment"
		clear-label="Cancel"
		prevent-blur=".ts-media-library"
		@save="postComment"
		@clear="cancelComment"
	>
		<template #trigger>
			<div class="ts-filter ts-popup-target" @mousedown="$root.activePopup = 'commentForm'">
				<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_order_comment'), [ 'aria-hidden' => 'true' ] ); ?>
				<div class="ts-filter-text">Post a comment</div>
			</div>
		</template>
		<template #popup>
			<div class="ts-popup-head flexify">
			   <div class="ts-popup-name flexify">
				  <?= \Voxel\current_user()->get_avatar_markup() ?>
				  <p><?= \Voxel\current_user()->get_display_name() ?></p>
			   </div>
			</div>
			<div class="ts-compose-textarea">
				<textarea v-model="message" placeholder="What's on your mind?" rows="3" class="autofocus min-scroll"></textarea>
			</div>
			<field-file
				:field="files"
				:sortable="false"
				ref="commentFiles"
				class="ts-status-files"
			></field-file>
		</template>
	</form-group>
</script>

<script type="text/html" id="orders-file-field">
	<div class="ts-form-group ts-file-upload">
		<label>{{ field.label }}</label>
		<div class="ts-file-list" ref="fileList" v-pre>
			<div class="pick-file-input">
				<a href="#">
					<i class="las la-cloud-upload-alt"></i>
					<?= _x( 'Upload', 'file field', 'voxel' ) ?>
				</a>
			</div>
		</div>
		<media-popup @save="onMediaPopupSave"></media-popup>
		<input ref="input" type="file" class="hidden" :multiple="field.props.maxCount > 1" :accept="accepts">
	</div>
</script>
