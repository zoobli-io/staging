<?php $value = $this->parse_value( $this->get_value() ) ?>

<div v-if="false" class="<?= $args['wrapper_class'] ?>">
	<?php if ( ! empty( $args['show_labels'] ) ): ?>
		<label><?= $this->get_label() ?></label>
	<?php endif ?>
	<?php if ( $this->props['input_mode'] === 'single-date' ): ?>
		<div class="ts-filter ts-popup-target <?= $value ? 'ts-filled' : '' ?>">
			<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
			<div class="ts-filter-text">
				<?= $value
					? \Voxel\date_format( strtotime( $value['start'] ) )
					: _x( 'Choose date', 'availability filter', 'voxel' ) ?>
			</div>
		</div>
	<?php else: ?>
		<div class="ts-double-input flexify">
			<div class="ts-filter ts-popup-target <?= $value ? 'ts-filled' : '' ?>">
				<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
				<div class="ts-filter-text">
					<?= $value
						? \Voxel\date_format( strtotime( $value['start'] ) )
						: _x( 'Check-in', 'availability filter', 'voxel' ) ?>
				</div>
			</div>
			<div class="ts-filter ts-popup-target <?= $value ? 'ts-filled' : '' ?>">
				<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
				<div class="ts-filter-text">
					<?= $value
						? \Voxel\date_format( strtotime( $value['end'] ) )
						: _x( 'Check-out', 'availability filter', 'voxel' ) ?>
				</div>
			</div>
		</div>
	<?php endif ?>
</div>
