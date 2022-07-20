<li class="ts-popup-component elementor-repeater-item-<?= $component['_id'] ?>">
	<a ref="target" href="#">
		<div class="ts-comp-icon flexify">
			<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			<?php if ( is_user_logged_in() ): ?>
				<span class="unread-indicator"></span>
			<?php endif ?>
		</div>
		<p class="ts_comp_label" ><?= $component['notifications_title'] ?></p>
	</a>
	<popup v-cloak>
		<div class="ts-popup-head flexify">
			<div class="ts-popup-name flexify">
				<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				<p><?= $component['notifications_title'] ?></p>
			</div>

			<ul class="flexify simplify-ul">
				<li class="flexify">
					<a href="#" class="ts-icon-btn">
						<i  aria-hidden="true" class="lar la-trash-alt"></i>
							
					</a>
				</li>
				<li class="flexify ts-popup-close">
					<a  @click.prevent="$root.active = false" href="#" class="ts-icon-btn">
						<i  aria-hidden="true" class="las la-times"></i>
					</a>
				</li>
			</ul>
		</div>
		<div class="ts-form-group min-scroll ts-list-container">
			<div class="ts-empty-user-tab">
				<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				<p>Not available on this build</p>
			</div>
			<!-- <ul class="ts-notification-list simplify-ul">
				<li class="ts-new-notification">
					<a href="#">
						<div class="notification-image">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<div class="notification-details">
							<p>Albion requested to book Modern Condo</p>
							<span>2 hours ago</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="notification-image">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<div class="notification-details">
							<p>Albion requested to book Modern Condo</p>
							<span>2 hours ago</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="notification-image">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<div class="notification-details">
							<p>Albion requested to book Modern Condo</p>
							<span>2 hours ago</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="notification-image">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<div class="notification-details">
							<p>Albion requested to book Modern Condo</p>
							<span>2 hours ago</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="notification-image">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<div class="notification-details">
							<p>Albion requested to book Modern Condo</p>
							<span>2 hours ago</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="notification-image">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<div class="notification-details">
							<p>Albion requested to book Modern Condo</p>
							<span>2 hours ago</span>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="notification-image">
							<?php \Elementor\Icons_Manager::render_icon( $component['choose_component_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						</div>
						<div class="notification-details">
							<p>Albion requested to book Modern Condo</p>
							<span>2 hours ago</span>
						</div>
					</a>
				</li>
			</ul> -->
			
	</popup>
</li>