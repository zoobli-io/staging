<?php
$value = $this->parse_value( $this->get_value() );
$key = $value['key'] ?? null;
$choices = $this->_get_selected_choices();
$label = isset( $choices[ $key ] ) ? $choices[ $key ]['label'] : null;
?>

<?php if ( ( $this->elementor_config['display_as'] ?? null ) === 'buttons' ): ?>
	<?php foreach ( $choices as $choice ): ?>
		<div v-if="false" class="<?= $args['wrapper_class'] ?>">
			<?php if ( ! empty( $args['show_labels'] ) ): ?>
				<label><?= $choice['label'] ?></label>
			<?php endif ?>
			<div class="ts-filter <?= $key === $choice['key'] ? 'ts-filled' : '' ?>">
				<span><?= $choice['icon'] ?></span>
				<div class="ts-filter-text">
					<span><?= $choice['label'] ?></span>
				</div>
			</div>
		</div>
	<?php endforeach ?>
<?php else: ?>
	<div v-if="false" class="<?= $args['wrapper_class'] ?>">
		<?php if ( ! empty( $args['show_labels'] ) ): ?>
			<label><?= $this->get_label() ?></label>
		<?php endif ?>
		<div class="ts-filter ts-popup-target <?= $key ? 'ts-filled' : '' ?>">
			<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
			<div class="ts-filter-text"><?= $key ? $label : $this->get_label() ?></div>
		</div>
	</div>
<?php endif;