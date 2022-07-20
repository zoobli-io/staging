 
<?php if ($this->get_settings('navbar_choose_source') === 'add_links_manually'): ?>
	<div class="ts-nav-menu ts-custom-links flexify">
		<ul class="ts-nav ts-nav-<?= $this->get_settings('ts_navbar_orientation') ?> flexify simplify-ul min-scroll min-scroll-h">
			<?php foreach ($this->get_settings('ts_navbar_items') as $action): ?>
				<li class="menu-item <?= $action['navbar_item__active'] ?>">
					<a href="<?= $action['ts_navbar_item_link']['url'] ?>" class="ts-item-link">
						<div class="ts-item-icon flexify">
							<?php \Elementor\Icons_Manager::render_icon( $action['ts_navbar_item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<p><?= $action['ts_navbar_item_text'] ?></p>
					</a>
				</li>
			<?php endforeach ?>
		</ul>
	</div>
<?php else: ?>
	<div class="ts-nav-menu ts-wp-menu">
		<?php if ( isset( get_nav_menu_locations()[ $this->get_settings( 'ts_choose_menu' ) ] ) ): ?>
			<?php wp_nav_menu( [
				'echo' => true,
				'theme_location' => $this->get_settings( 'ts_choose_menu' ),
				'container' => false,
				'menu_class' => sprintf( 'ts-nav ts-nav-%s flexify simplify-ul', $this->get_settings('ts_navbar_orientation') ),
				'walker' => new \Voxel\Utils\Nav_Menu_Walker,
				'_widget' => $this,
				'_arrow_down' => $this->get_settings( 'ts_nav_dropdown_icon' ),
				'_arrow_right' => [ 'library' => 'la-solid', 'value' => 'las la-angle-right' ],
				'_arrow_left' => [ 'library' => 'la-solid', 'value' => 'las la-angle-left' ],
				'_icon_mobile' => $this->get_settings( 'ts_mobile_menu_icon' ),
				'_icon_close'  => [ 'library' => 'la-solid', 'value' => 'las la-times' ],
			] ) ?>
		<?php endif ?>
	</div>
<?php endif ?>
 