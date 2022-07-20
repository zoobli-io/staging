<?php
/**
 * Pricing plans widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<ul class="ts-plan-tabs simplify-ul flexify ts-generic-tabs">
	<?php foreach ( $groups as $group ): ?>
		<li class="<?= $group['_id'] === $default_group ? 'ts-tab-active' : '' ?>">
			<a href="#" data-id="<?= esc_attr( $group['_id'] ) ?>"><?= $group['group_label'] ?></a>
		</li>
	<?php endforeach ?>
</ul>
<div class="ts-plans-list">
	<?php foreach ( $prices as $price ): ?>
		<div class="ts-plan-container <?= $price['group'] !== $default_group ? 'hidden' : '' ?>" data-group="<?= esc_attr( $price['group'] ) ?>">
			<div class="ts-plan-image flexify">
				<?= $price['image'] ?>
			</div>
			<div class="ts-plan-body">
				<div class="ts-plan-details">
					<span class="ts-plan-name"><?= $price['label'] ?></span>
				</div>
				<div class="ts-plan-pricing">
					<span class="ts-plan-price"><?= $price['amount'] ?></span>
					<?php if ( $price['period'] ): ?>
						<div class="ts-price-period">/ <?= $price['period'] ?></div>
					<?php endif ?>
				</div>
				<?php if ( ! empty( $price['features'] ) ): ?>
					<div class="ts-plan-features">
						<ul class="simplify-ul">
							<?php foreach ( $price['features'] as $feature ): ?>
								<li>
									<?php \Voxel\render_icon( $this->get_settings_for_display('plan_list_icon') ) ?>
									<span><?= $feature['text'] ?></span>
								</li>
							<?php endforeach ?>
						</ul>
					</div>
				<?php endif ?>
				<div class="ts-plan-footer">
					<?php if ( $current_price_key === $price['key'] ): ?>
						<a href="<?= esc_url( $price['link'] ) ?>" vx-action class="ts-btn ts-btn-1 ts-btn-large btn-disabled">
							Current plan
						</a>
					<?php else: ?>
						<a href="<?= esc_url( $price['link'] ) ?>" vx-action class="ts-btn ts-btn-2 ts-btn-large">
							<?php if ( $membership->get_type() === 'default' ): ?>
								Pick plan
								<i aria-hidden="true" class="las la-angle-right"></i>
							<?php else: ?>
								Switch to plan
								<i aria-hidden="true" class="las la-angle-right"></i>
							<?php endif ?>
						</a>
					<?php endif ?>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>
