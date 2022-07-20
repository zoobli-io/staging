<div class="ts-user-area">
	<ul class="flexify simplify-ul user-area-menu">

		<?php foreach ( $this->get_settings('ts_userbar_items') as $component ): ?>

			<?php if ( is_user_logged_in() && $component['ts_component_type'] === 'notifications'): ?>

				<?php require locate_template('templates/notifications/notifications.php') ?>

			<?php elseif ( is_user_logged_in() && $component['ts_component_type'] === 'messages'): ?>

				<?php require locate_template('templates/messages/messages.php') ?>

			<?php elseif (is_user_logged_in() && $component['ts_component_type'] === 'user_menu'):
				$user = \Voxel\current_user(); ?>

					<li class="ts-popup-component ts-user-area-avatar elementor-repeater-item-<?= $component['_id'] ?>">
						<a ref="target" href="#">
							<div class="ts-comp-icon flexify">
								
								<?= $user->get_avatar_markup() ?>
							</div>
							<p class="ts_comp_label"><?= esc_html( $user->get_display_name() ) ?></p>
							<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_component_chevron'), [ 'aria-hidden' => 'true', 'class' => 'ts-has-children-icon' ], 'span' ); ?>
						</a>

						<?php if ( isset( get_nav_menu_locations()[ $component['ts_choose_menu'] ] ) ): ?>
							<popup v-cloak>
								<div class="ts-popup-head flexify">
									<div class="ts-popup-name flexify">
										<?= $user->get_avatar_markup() ?>
										<p><?= esc_html( $user->get_display_name() ) ?></p>
									</div>

									<ul class="flexify simplify-ul">
										<li class="flexify ts-popup-close">
											<a  @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
												<i  aria-hidden="true" class="las la-times"></i>
											</a>
										</li>
									</ul>
								</div>
								<div class="ts-term-dropdown ts-multilevel-dropdown">
									<transition-group :name="'slide-from-'+slide_from">
										<?php wp_nav_menu( [
											'echo' => true,
											'theme_location' => $component['ts_choose_menu'],
											'container' => false,
											'items_wrap' => '%3$s',
											'walker' => new \Voxel\Utils\Popup_Menu_Walker,
											'_arrow_right' => [ 'library' => 'la-solid', 'value' => 'las la-angle-right' ],
											'_arrow_left' => [ 'library' => 'la-solid', 'value' => 'las la-angle-left' ],
										] ) ?>
									</transition-group>
								</div>
							</popup>
						<?php endif ?>
					</li>

			<?php elseif ($component['ts_component_type'] === 'select_wp_menu'): ?>

					<li class="ts-popup-component elementor-repeater-item-<?= $component['_id'] ?>">
						<a ref="target" href="#">
							<div class="ts-comp-icon flexify">
								<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<p class="ts_comp_label" ><?= $component['wp_menu_title'] ?></p>
						</a>

						<?php if ( isset( get_nav_menu_locations()[ $component['ts_choose_menu'] ] ) ): ?>
							<popup v-cloak>
								<div class="ts-popup-head flexify">
									<div class="ts-popup-name flexify">
										<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										<p><?= $component['wp_menu_title'] ?></p>
									</div>

									<ul class="flexify simplify-ul">
										<li class="flexify ts-popup-close">
											<a  @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
												<i  aria-hidden="true" class="las la-times"></i>
											</a>
										</li>
									</ul>
								</div>
								<div class="ts-term-dropdown ts-multilevel-dropdown">
									<transition-group :name="'slide-from-'+slide_from">
										<?php wp_nav_menu( [
											'echo' => true,
											'theme_location' => $component['ts_choose_menu'],
											'container' => false,
											'items_wrap' => '%3$s',
											'walker' => new \Voxel\Utils\Popup_Menu_Walker,
											'_arrow_right' => [ 'library' => 'la-solid', 'value' => 'las la-angle-right' ],
											'_arrow_left' => [ 'library' => 'la-solid', 'value' => 'las la-angle-left' ],
										] ) ?>
									</transition-group>
								</div>
							</popup>
						<?php endif ?>
					</li>

			<?php elseif ($component['ts_component_type'] === 'link'): ?>

				<li class="elementor-repeater-item-<?= $component['_id'] ?>">
					<?php
						$url = $component['component_url']['url'];
						$target = $component['component_url']['is_external'] ? ' target="_blank"' : '';
						$nofollow = $component['component_url']['nofollow'] ? ' rel="nofollow"' : '';
					 ?>
					<a <?= $target, $nofollow ?> href="<?=  $url ?>">
						<div class="ts-comp-icon flexify">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<p class="ts_comp_label"><?= $component['component_title'] ?></p>
					</a>
				</li>

			<?php endif ?>

		<?php endforeach ?>

	</ul>
</div>
