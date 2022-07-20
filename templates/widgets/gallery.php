
<ul class="ts-gallery flexify simplify-ul">
	<div class="ts-gallery-grid <?= $this->get_settings('ts_gl_mobile_behaviour') ?>">
		<?php foreach ( $visible as $image ): ?>
			<li>
				<a
					href="<?= esc_url( $image['src_lightbox'] ) ?>"
					data-elementor-open-lightbox="yes"
					<?= $is_slideshow ? sprintf( 'data-elementor-lightbox-slideshow="%s"', $this->get_id() ) : '' ?>
					data-elementor-lightbox-description="<?= esc_attr( $image['alt'] ?: $image['description'] ) ?>"
				>
					<div class="ts-image-overlay"></div>
					<img src="<?= esc_url( $image['src_display'] ) ?>" alt="<?= esc_attr( $image['alt'] ?: $image['description'] ) ?>">
				</a>
			</li>
		<?php endforeach ?>

		<?php if ( count( $hidden ) ): ?>
			<li class="ts-gallery-last-item">
				<a
					href="<?= esc_url( $hidden[0]['src_lightbox'] ) ?>"
					data-elementor-open-lightbox="yes"
					<?= $is_slideshow ? sprintf( 'data-elementor-lightbox-slideshow="%s"', $this->get_id() ) : '' ?>
					data-elementor-lightbox-description="<?= esc_attr( $hidden[0]['alt'] ?: $hidden[0]['description'] ) ?>"
				>
					<div class="ts-image-overlay">
						<?php \Elementor\Icons_Manager::render_icon( $this->get_settings('ts_gl_general_view_icon'), [ 'aria-hidden' => 'true' ] ); ?>
						<p><?= sprintf( '+%d', count( $hidden ) ) ?></p>
					</div>
					<img src="<?= esc_url( $hidden[0]['src_display'] ) ?>" alt="<?= esc_attr( $hidden[0]['alt'] ?: $hidden[0]['description'] ) ?>">
				</a>

				<div class="hidden">
					<?php foreach ( $hidden as $index => $image ): ?>
						<?php if ( $index === 0 ) continue; ?>
						<a
							href="<?= esc_url( $image['src_lightbox'] ) ?>"
							data-elementor-open-lightbox="yes"
							data-elementor-lightbox-slideshow="<?= $this->get_id() ?>"
							data-elementor-lightbox-description="<?= esc_attr( $image['alt'] ?: $image['description'] ) ?>"
						></a>
					<?php endforeach ?>
				</div>
			</li>
		<?php endif ?>

		<?php if ( $filler_count >= 1 ): ?>
			<?php while ( $filler_count >= 1 ): $filler_count--; ?>
				<li class="ts-empty-item">
					<div></div>
				</li>
			<?php endwhile ?>
		<?php endif ?>
	</div>
</ul>

