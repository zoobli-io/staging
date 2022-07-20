<?php if ( $this->get_settings('ts_wrap_feed') === 'ts-feed-nowrap' ): ?>
	<ul class="simplify-ul flexify post-feed-nav">
		<li>
			<a href="#" class="ts-icon-btn prev-page">
				<?php \Voxel\render_icon( $this->get_settings('feed_prev_icon') ) ?>
			</a>
		</li>
		<li>
			<a href="#" class="ts-icon-btn next-page">
				<?php \Voxel\render_icon( $this->get_settings('feed_next_icon') ) ?>
			</a>
		</li>
	</ul>
<?php endif ?>
