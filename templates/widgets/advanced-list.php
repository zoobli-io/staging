<!-- Advanced list widget -->
<ul class="flexify simplify-ul ts-advanced-list ts-al-<?= $this->get_settings_for_display('ts_al_orientation') ?>">
	<?php foreach ($this->get_settings_for_display('ts_actions') as $action): ?>
			<li class="elementor-repeater-item-<?= $action['_id'] ?> flexify ts-action elementor-column <?= $this->get_settings_for_display('ts_al_columns_no') ?>">
				<?php if ($action['ts_action_type'] === 'none'): ?>
					<div class="ts-action-con">
						<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div><?= $action['ts_acw_initial_text'] ?>
					</div>
				<?php elseif ($action['ts_action_type'] === 'action_link'): ?>
					<a href="<?= esc_url( $action['ts_action_link']['url'] ?? null ) ?>" class="ts-action-con">
						<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
						<?= $action['ts_acw_initial_text'] ?>
					</a>
				<?php elseif ($action['ts_action_type'] === 'direct_message'): ?>
					<div class="ts-action-wrap ts-popup-component">
						<a href="#" ref="target" class="ts-action-con">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
							<?= $action['ts_acw_initial_text'] ?>
						</a>
						<popup v-cloak>
							<div class="ts-popup-head flexify hide-d">
								<div class="ts-popup-name flexify">
									<p>Direct message</p>
								</div>
								<ul class="flexify simplify-ul">
									<li class="flexify ts-popup-close">
										<a @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
											<i aria-hidden="true" class="las la-times"></i>
										</a>
									</li>
								</ul>
							</div>
							<div class="ts-empty-user-tab">
								<i aria-hidden="true" class="lab la-facebook-messenger"></i>
								<p>Not available on this build</p>
							</div>
						</popup>
					</div>
				<?php elseif ($action['ts_action_type'] === 'action_save'): ?>
					<div class="ts-action-wrap ts-popup-component">
						<a href="#" ref="target" class="ts-action-con">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
							<?= $action['ts_acw_initial_text'] ?>
						</a>
						<popup v-cloak>
							<div class="ts-popup-head flexify hide-d">
								<div class="ts-popup-name flexify">
									<p>Save post</p>
								</div>
								<ul class="flexify simplify-ul">
									<li class="flexify ts-popup-close">
										<a @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
											<i aria-hidden="true" class="las la-times"></i>
										</a>
									</li>
								</ul>
							</div>
							<div class="ts-empty-user-tab">
								<i aria-hidden="true" class="las la-check-square"></i>
								<p>Not available on this build</p>
							</div>
						</popup>
					</div>
				<?php elseif ($action['ts_action_type'] === 'edit_post'):
					$current_post = \Voxel\get_current_post( true );
					if ( ! ( $current_post && $current_post->is_editable_by_current_user() ) ) {
						continue;
					}

					$edit_steps = array_filter( $current_post->get_fields(), function( $field ) {
						return $field->get_type() === 'ui-step';
					} );
					?>

					<?php if ( count( $edit_steps ) > 1 ): ?>
						<div class="ts-action-wrap ts-popup-component">
							<a href="#" ref="target" class="ts-action-con">
								<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
								<?= $action['ts_acw_initial_text'] ?>
							</a>
							<popup v-cloak>
								<div class="ts-popup-head flexify hide-d">
									<div class="ts-popup-name flexify">
										<p>Edit post</p>
									</div>
									<ul class="flexify simplify-ul">
										<li class="flexify ts-popup-close">
											<a @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
												<i  aria-hidden="true" class="las la-times"></i>
											</a>
										</li>
									</ul>
								</div>
								<div class="ts-term-dropdown">
									<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
										<?php foreach ( $edit_steps as $field ): ?>
											<li>
												<a href="<?= esc_url( add_query_arg( 'step', $field->get_key(), $current_post->get_edit_link() ) ) ?>" class="flexify">
													<span><i class="las la-edit"></i></span>
													<p><?= $field->get_label() ?></p>
												</a>
											</li>
										<?php endforeach ?>
									</ul>
								</div>
							</popup>
						</div>
					<?php else: ?>
						<a href="<?= esc_url( $current_post->get_edit_link() ) ?>" class="ts-action-con">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
							<?= $action['ts_acw_initial_text'] ?>
						</a>
					<?php endif ?>
				<?php elseif ($action['ts_action_type'] === 'share_post'):
					$current_post = \Voxel\get_current_post( true );
					if ( ! $current_post ) {
						return;
					}
					?>
					<div class="ts-action-wrap ts-popup-component">
						<a href="#" ref="target" class="ts-action-con">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div>
							<?= $action['ts_acw_initial_text'] ?>
						</a>
						<popup v-cloak ref="popup">
							<div class="ts-popup-head flexify">
								<div class="ts-popup-name flexify">
									<?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?>
									<p>Share post</p>
								</div>
								<ul class="flexify simplify-ul">
									<li class="flexify ts-popup-close">
										<a @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
											<i  aria-hidden="true" class="las la-times"></i>
										</a>
									</li>
								</ul>
							</div>
							<div class="ts-term-dropdown">
								<ul class="simplify-ul ts-form-group ts-term-dropdown-list min-scroll">
									<li>
										<a href="#" @click.prevent class="flexify">
											<span><i class="lab la-facebook-messenger"></i></span>
											<p>Direct message</p>
										</a>
									</li>
									<li>
										<a
											href="#"
											v-if="navigator.clipboard"
											@click.prevent="Voxel.copy( <?= esc_attr( wp_json_encode( 'https://kelly.test/?test_share' ) ) ?> ); $refs.popup.blur();"
											class="flexify"
										>
											<span><i class="lar la-copy"></i></span>
											<p>Copy link</p>
										</a>
									</li>
									<li>
										<a href="#" class="flexify" v-if="navigator.share" @click.prevent="Voxel.share( <?= esc_attr( wp_json_encode( [
											'title' => $current_post->get_title(),
											'url' => $current_post->get_link(),
										] ) ) ?> ); $refs.popup.blur();">
											<span><i class="las la-share-square"></i></span>
											<p>Share via...</p>
										</a>
									</li>
								</ul>
							</div>
						</popup>
					</div>
				<?php elseif ($action['ts_action_type'] === 'action_follow'):
					$current_post = \Voxel\get_current_post( true );
					$author_id = $current_post ? $current_post->get_author_id() : null;
					$status = \Voxel\get_follow_status( $author_id, get_current_user_id() );
					$is_active = $status === \Voxel\FOLLOW_ACCEPTED;
					$is_intermediate = $status === \Voxel\FOLLOW_REQUESTED;
					?>
					<a
						href="<?= esc_url( add_query_arg( [
							'vx' => 1,
							'action' => 'user.follow_user',
							'user_id' => $author_id,
							'_wpnonce' => wp_create_nonce( 'vx_user_follow' ),
						], home_url( '/' ) ) ) ?>"
						class="ts-action-con ts-action-follow <?= $is_active ? 'active' : '' ?> <?= $is_intermediate ? 'intermediate' : '' ?>">
						<span class="ts-initial">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div><?= $action['ts_acw_initial_text'] ?>
						</span>

						<span class="ts-intermediate">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_intermediate_icon'] ) ?></div><?= $action['ts_acw_intermediate_text'] ?>
						</span>

						<!--Reveal span when action is clicked (active class is added to the li) -->
						<span class="ts-reveal">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_reveal_icon'] ) ?></div><?= $action['ts_acw_reveal_text'] ?>
						</span>
					</a>
				<?php elseif ($action['ts_action_type'] === 'action_follow_post'):
					$current_post = \Voxel\get_current_post( true );
					$status = \Voxel\get_post_follow_status( $current_post ? $current_post->get_id() : null, get_current_user_id() );
					$is_active = $status === \Voxel\FOLLOW_ACCEPTED;
					$is_intermediate = $status === \Voxel\FOLLOW_REQUESTED;
					?>
					<a
						href="<?= esc_url( add_query_arg( [
							'vx' => 1,
							'action' => 'user.follow_post',
							'post_id' => $current_post ? $current_post->get_id() : null,
							'_wpnonce' => wp_create_nonce( 'vx_user_follow' ),
						], home_url( '/' ) ) ) ?>"
						class="ts-action-con ts-action-follow <?= $is_active ? 'active' : '' ?> <?= $is_intermediate ? 'intermediate' : '' ?>">
						<span class="ts-initial">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_initial_icon'] ) ?></div><?= $action['ts_acw_initial_text'] ?>
						</span>

						<span class="ts-intermediate">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_intermediate_icon'] ) ?></div><?= $action['ts_acw_intermediate_text'] ?>
						</span>

						<!--Reveal span when action is clicked (active class is added to the li) -->
						<span class="ts-reveal">
							<div class="ts-action-icon"><?php \Voxel\render_icon( $action['ts_acw_reveal_icon'] ) ?></div><?= $action['ts_acw_reveal_text'] ?>
						</span>
					</a>
				<?php endif ?>
			</li>
	<?php endforeach ?>
</ul>


