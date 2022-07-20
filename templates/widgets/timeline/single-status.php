<script type="text/html" id="timeline-single-status">
	<div :key="status.key" class="ts-status" :class="{'vx-pending': status._pending}">
		<div v-if="status.publisher.exists" class="ts-status-head flexify ts-parent">
			<a :href="status.publisher.link" v-html="status.publisher.avatar"></a>
			<div>
				<a :href="status.publisher.link">{{ status.publisher.name }}</a>
				<span>posted an update</span>
				<a :href="status.link">
					<span class="ts-status-time">{{ status.time }}</span>
				</a>
				<span v-if="status.edit_time" :title="'Edited on '+status.edit_time">(edited)</span>
			</div>
		</div>
		<div v-else class="ts-status-head flexify ts-parent">
			<a :href="status.user.link" v-html="status.user.avatar"></a>
			<div>
				<a :href="status.user.link">{{ status.user.name }}</a>
				<template v-if="status.post.exists">
					<span>{{ status.is_review ? 'reviewed' : 'posted on' }}</span>
					<a :href="status.post.link">{{ status.post.title }}</a>
				</template>
				<template v-else>
				    <span>posted an update</span>
				</template>
				<a :href="status.link">
					<span class="ts-status-time">{{ status.time }}</span>
				</a>
				<span v-if="status.edit_time" :title="'Edited on '+status.edit_time">(edited)</span>
			</div>
		</div>
		<div class="ts-status-body ts-parent">
			<review-score v-if="status.review_score !== null" :score="status.review_score"></review-score>
			<p v-if="status.content" v-html="status.content"></p>
			<ul v-if="status.files" class="ts-status-gallery simplify-ul flexify">
				<li v-for="file in status.files">
					<a :href="file.url" data-elementor-open-lightbox="yes" :data-elementor-lightbox-slideshow="status.files.length > 1 ? status.key : null">
						<img :src="file.url" :alt="file.alt">
					</a>
				</li>
			</ul>
		</div>
		<div class="ts-status-footer ts-parent">
			<ul class="simplify-ul flexify">
				<li>
					<a href="#" @click.prevent="likeStatus" :class="{'ts-liked': status.liked_by_user}" ref="likeBtn">
						<template v-if="status.liked_by_user">
							<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_liked_icon') ) ?>
						</template>
						<template v-else>
							<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_like_icon') ) ?>
						</template>
						<span v-if="status.like_count">{{ status.like_count }}</span>
					</a>
				</li>
				<li>
					<a href="#" @click.prevent="status.replies.visible = !status.replies.visible">
						<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_comment_icon') ); ?>
						<span v-if="status.reply_count">{{ status.reply_count }}</span>
					</a>
				</li>
				<li v-if="status.user_can_edit || status.user_can_moderate">
					<form-group
						:popup-key="'mod-status-'+status.id"
						:default-class="false"
						:show-save="false"
						:show-clear="false"
					>
						<template #trigger>
							<a href="#" @mousedown="$root.activePopup = 'mod-status-'+status.id">
								<i class="las la-ellipsis-h"></i>
							</a>
						</template>
						<template #popup>
							<div class="ts-popup-head hide-d flexify">
								<div class="ts-popup-name flexify">
									<p>Actions</p>
								</div>
								<ul class="flexify simplify-ul">
									<li class="flexify ts-popup-close">
										<a @click.prevent="$root.activePopup = null" href="#" class="ts-icon-btn">
											<i aria-hidden="true" class="las la-times"></i>
										</a>
									</li>
								</ul>
							</div>
							<div class="ts-term-dropdown">
								<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
									<li v-if="status.user_can_edit && $root.config.postSubmission.editable">
										<a href="#" class="flexify" @mousedown="$root.activePopup = 'create-status-'+status.id">
											<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_edit_icon') ) ?>
											<p>Edit post</p>
										</a>
									</li>
									<li v-if="status.user_can_edit || status.user_can_moderate">
										<a href="#" class="flexify" @click.prevent="deleteStatus">
											<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_delete_icon') ) ?>
											<p>Remove post</p>
										</a>
									</li>
								</ul>
							</div>
						</template>
					</form-group>
				</li>
			</ul>
			<create-status :status="status" :index="index" class="ts-edit-status"></create-status>
		</div>
		<div v-if="status.replies.visible" class="ts-status-comments">
			<status-replies :replies="status.replies" :status="status"></status-replies>
			<create-reply :status="status"></create-reply>
		</div>
		<div v-if="status.highlightedReplies && status.highlightedReplies.list.length && !status.replies.requested" class="ts-status-comments ts-single-thread">
			<a href="#" @click.prevent="status.replies.visible = !status.replies.visible" class="ts-load-more-comments ts-btn ts-btn-4">
				<span>View all comments</span>
			</a>
			<status-replies :replies="status.highlightedReplies" :status="status"></status-replies>
		</div>
	</div>
</script>
