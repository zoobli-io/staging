<?php $value = $this->_get_selected_terms() ?>

<div v-if="false" class="<?= $args['wrapper_class'] ?>">
	<?php if ( ! empty( $args['show_labels'] ) ): ?>
		<label><?= $this->get_label() ?></label>
	<?php endif ?>
	<div class="ts-filter ts-popup-target <?= $value ? 'ts-filled' : '' ?>">
		<span><?= \Voxel\get_icon_markup( $this->get_icon() ) ?></span>
		<div class="ts-filter-text">
			<?= $value ? array_values( $value )[0]['label'] : $this->get_label() ?>
			<?php if ( $value && count( $value ) > 1 ): ?>
				<span class="term-count">+<?= number_format_i18n( count( $value ) - 1 ) ?></span>
			<?php endif ?>
		</div>
	</div>
</div>
