<script type="text/html" id="timeline-file-field">
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
