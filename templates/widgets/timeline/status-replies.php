<script type="text/html" id="timeline-status-replies">
	<ul class="status-comments-list simplify-ul">
		<template v-if="!replies.list.length">
			
			<div class="ts-no-posts" v-if="parent">
				<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_comment_icon') ) ?>
				<p>	{{ replies.loading ? 'Loading replies...' : 'No replies on this comment yet.' }}</p>
			</div>
			<div class="ts-no-posts" v-else>
				<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_comment_icon') ) ?>
				<p>{{ replies.loading ? 'Loading comments...' : 'No comments on this post yet.' }}</p>
			</div>
		</template>

		<template v-else>
			<li v-for="reply, index in replies.list" :key="reply.key" class="ts-reply flexify" :class="{'vx-pending': reply._pending, 'highlighted': reply.highlighted}">
				<a :href="reply.user.link" class="ts-user-avatar" v-html="reply.user.avatar"></a>
				<div class="comment-body">
					<div class="ts-status-head flexify">
						<div>
							<a :href="reply.user.link">{{ reply.user.name }}</a>
							<a :href="reply.link">
								<span class="ts-status-time">{{ reply.time }}</span>
							</a>
							<span v-if="reply.edit_time" :title="'Edited on '+reply.edit_time">(edited)</span>
						</div>
					</div>
					<div class="ts-status-body">
						<p v-html="reply.content"></p>
					</div>
					<div class="ts-status-footer">
						<ul class="simplify-ul flexify">
							<li>
								<a href="#" @click.prevent="likeReply(reply, index)" :class="{'ts-liked': reply.liked_by_user}" :ref="reply.key+':likeBtn'">
									<template v-if="reply.liked_by_user">
										<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_liked_icon') ) ?>
									</template>
									<template v-else>
										<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_like_icon') ) ?>
									</template>
									<span v-if="reply.like_count">{{ reply.like_count }}</span>
								</a>
							</li>
							<li>
								<a href="#" @click.prevent="reply.replies.visible = !reply.replies.visible">
									<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_comment_icon') ) ?>
									<span v-if="reply.reply_count">{{ reply.reply_count }}</span>
								</a>
							</li>
							<li>
								<a href="#" @click.prevent="showReplyBox(reply, status)">
									<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_reply_icon') ) ?>
								</a>
							</li>
							<li v-if="reply.user_can_edit || reply.user_can_moderate">
								<form-group
									:popup-key="'mod-reply:'+status.id+'-'+(parent?parent.id:0)+'-'+reply.key"
									:default-class="false"
									:show-save="false"
									:show-clear="false"
								>
									<template #trigger>
										<a href="#" @mousedown="$root.activePopup = 'mod-reply:'+status.id+'-'+(parent?parent.id:0)+'-'+reply.key">
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
												<li v-if="reply.user_can_edit && $root.config.replySubmission.editable">
													<a href="#" class="flexify" @mousedown="$root.activePopup = 'reply:'+status.id+'-'+(parent?parent.id:0)+'-'+reply.key">
														<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_edit_icon') ) ?>
														<p>Edit reply</p>
													</a>
												</li>
												<li v-if="reply.user_can_edit || reply.user_can_moderate">
													<a href="#" class="flexify" @click.prevent="deleteReply(reply, index)">
														<?php \Voxel\render_icon( $this->get_settings('ts_post_footer_delete_icon') ) ?>
														<p>Remove reply</p>
													</a>
												</li>
											</ul>
										</div>
									</template>
								</form-group>
							</li>
						</ul>

						<!-- edit comment -->
						<create-reply :show-trigger="false" :status="status" :index="index" :reply="reply" :parent="parent"></create-reply>

						<!-- reply to comment -->
						<create-reply :show-trigger="false" :status="status" :parent="reply" :index="index"></create-reply>
					</div>
				</div>
				<status-replies
					v-if="reply.replies.visible"
					:replies="reply.replies"
					:status="status"
					:parent="reply"
				></status-replies>
			</li>
			<li v-if="replies.hasMore">
				<a
					href="#"
					v-if="replies.hasMore"
					@click.prevent="replies.page++; getReplies();"
					class="ts-load-more-comments ts-btn ts-btn-4"
					:class="{'vx-pending': replies.loading}"
				>
					<?php \Voxel\render_icon( $this->get_settings('ts_comments_load_icon') ) ?>
					Load more comments
				</a>
			</li>
		</template>
	</ul>
</script>
