<div class="ts-work-hours <?= $this->get_settings('ts_wh_collapse') ?>">
	<div class="ts-hours-today flexify">

		<?php if ( $is_open_now ): ?>
			<div class="flexify ts-open-status open">
				<?php \Voxel\render_icon( $this->get_settings('ts_wh_open_icon') ); ?>
				<p><?= $this->get_settings('ts_wh_open_text') ?></p>
			</div>
		<?php elseif ( ( $schedule[ $today ]['status'] ?? null ) === 'closed' ): ?>
			<div class="flexify ts-open-status closed">
				<?php \Voxel\render_icon( $this->get_settings('ts_wh_closed_icon') ); ?>
				<p><?= $this->get_settings('ts_wh_closed_text') ?></p>
			</div>
		<?php elseif ( ( $schedule[ $today ]['status'] ?? null ) === 'appointments_only' ): ?>
			<div class="flexify ts-open-status appt-only">
				<?php \Voxel\render_icon( $this->get_settings('ts_wh_appt_icon') ); ?>
				<p><?= $this->get_settings('ts_wh_appt_text') ?></p>
			</div>
		<?php else: ?>
			<div class="flexify ts-open-status not-available">
				<?php \Voxel\render_icon( $this->get_settings('ts_wh_closed_icon') ); ?>
				<p>Not available</p>
			</div>
		<?php endif ?>
		<!--
		<div class="flexify ts-open-status closing-soon">
			<div class="wh-icon-con flexify">
			<?php \Voxel\render_icon( $this->get_settings('ts_wh_closing_icon') ); ?>
			</div>
			<p><?= $this->get_settings('ts_wh_closing_text') ?></p>
		</div>
		<div class="flexify ts-open-status opening-soon">
			<div class="wh-icon-con flexify">
			<?php \Voxel\render_icon( $this->get_settings('ts_wh_opening_icon') ); ?>
			</div>
			<p><?= $this->get_settings('ts_wh_opening_text') ?></p>
		</div>
		 -->
		<p class="ts-current-period">
			<?= $weekdays[ $today ] ?>:
			<?php if ( ! isset( $schedule[ $today ] ) ): ?>
				<span>Not available</span>
			<?php elseif ( $schedule[ $today ]['status'] === 'open' ): ?>
				<span>Open all day</span>
			<?php elseif ( $schedule[ $today ]['status'] === 'closed' ): ?>
				<span>Closed all day</span>
			<?php elseif ( $schedule[ $today ]['status'] === 'appointments_only' ): ?>
				<span>Appointments only</span>
			<?php else: ?>
				<?php foreach ( $schedule[ $today ]['hours'] as $hours ): ?>
					<span><?= sprintf(
						'%s - %s',
						\Voxel\time_format( strtotime( $hours['from'] ) ),
						\Voxel\time_format( strtotime( $hours['to'] ) ) )
					?></span>
				<?php endforeach ?>
			<?php endif ?>
		</p>
		<a href="#" class="ts-expand-hours ts-icon-btn ts-smaller">
			<i aria-hidden="true" class="ts-has-children-icon las la-arrow-down"></i>
		</a>
	</div>
	<div class="ts-work-hours-list">
		<ul class="simplify-ul flexify">
			<?php foreach ( $weekdays as $key => $label ): ?>
				<li>
					<p class="ts-day"><?= $label ?></p>
					<small class="ts-hours">
						<?php if ( ! isset( $schedule[ $key ] ) ): ?>
							<span>Not available</span>
						<?php elseif ( $schedule[ $key ]['status'] === 'open' ): ?>
							<span>Open all day</span>
						<?php elseif ( $schedule[ $key ]['status'] === 'closed' ): ?>
							<span>Closed all day</span>
						<?php elseif ( $schedule[ $key ]['status'] === 'appointments_only' ): ?>
							<span>Appointments only</span>
						<?php else: ?>
							<?php foreach ( $schedule[ $key ]['hours'] as $hours ): ?>
								<span><?= sprintf(
									'%s - %s',
									\Voxel\time_format( strtotime( $hours['from'] ) ),
									\Voxel\time_format( strtotime( $hours['to'] ) ) )
								?></span>
							<?php endforeach ?>
						<?php endif ?>
					</small>
				</li>
			<?php endforeach ?>
		   <li>
		   		<p class="ts-timezone">Timezone: <?= $timezone->getName() ?></p>
		   		<small><?= sprintf( '%s local time', \Voxel\datetime_format( $local_time->getTimestamp() ) ) ?></small>
		   </li>
		</ul>
	</div>
</div>
