<script type="text/html" id="create-post-media-popup">
	

	<a @click.prevent href="#" ref="popupTarget" @mousedown="openLibrary" class="ts-btn ts-btn-4 create-btn">
		<i aria-hidden="true" class="las la-photo-video"></i>
		<p><?= _x( 'Media library', 'media popup', 'voxel' ) ?></p>
	</a>
	<teleport to="body">
		<transition name="form-popup">
			<form-popup
				ref="popup"
				v-if="active"
				class="ts-media-library"
				:target="$refs.popupTarget"
				@blur="$emit('blur'); active = false; selected = {};"
				@save="save"
				@clear="clear"
			>
				<div class="ts-form-group min-scroll ts-list-container">
					<div class="ts-file-list">
						<div
							v-for="file in files"
							class="ts-file"
							:style="getStyle(file)"
							:class="{selected: selected[ file.id ], 'ts-file-img': isImage(file)}"
							@click="selectFile(file)"
						>
							<div class="ts-file-info">
								<i class="las la-cloud-upload-alt"></i><code>{{ file.name }}</code>
							</div>
							<div class="ts-remove-file ts-select-file">
								<i class="las la-check" aria-hidden="true"></i>
							</div>
						</div>
					</div>

					<div v-if="!loading && !files.length" class="ts-form-group">
						<label><?= _x( 'You have no files in your media library.', 'media popup', 'voxel' ) ?></label>
					</div>
					<div v-else>
						<a v-if="loading" href="#" class="ts-btn ts-btn-4 load-more-btn">
							<i aria-hidden="true" class="las la-sync-alt"></i>
							<?= _x( 'Loading...', 'media popup', 'voxel' ) ?>
						</a>
						<a
							v-else-if="hasMore && !loading"
							@click.prevent="loadMore"
							href="#"
							class="ts-btn ts-btn-4"
						>	
							<i aria-hidden="true" class="las la-sync-alt"></i>
							<?= _x( 'Load more', 'media popup', 'voxel' ) ?>
						</a>
					</div>
				</div>
			</form-popup>
		</transition>
	</teleport>
</script>
