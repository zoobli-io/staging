<?php if ( $this->get_settings('ts_source') === 'search-form' ): ?>
	<?php if ( $pagination === 'prev_next' ): ?>
		<div class="feed-pagination flexify">
			<a href="#" class="ts-btn ts-btn-1 ts-btn-large ts-load-prev <?= ! $results['has_prev'] ? 'disabled' : '' ?>">
				<?php \Voxel\render_icon( $this->get_settings('fpag_prev_icon') ) ?>
				Previous
			</a>
			<a href="#" class="ts-btn ts-btn-1 ts-btn-large btn-icon-right ts-load-next" :class="{'vx-disabled': !hasMore}" @click.prevent="page += 1; getOrders();">
				Next
				<?php \Voxel\render_icon( $this->get_settings('fpag_next_icon') ) ?>
			</a>
		</div>
	<?php elseif ( $pagination === 'load_more' ): ?>
		<?php if ( $results['has_next'] ): ?>
			<div class="feed-pagination flexify">
				<a href="#" class="ts-btn ts-btn-1 ts-btn-large ts-load-more">
					<?php \Voxel\render_icon( $this->get_settings('fpag_load_icon') ) ?>
					Show more results
				</a>
			</div>
		<?php endif ?>
	<?php endif ?>
<?php endif ?>