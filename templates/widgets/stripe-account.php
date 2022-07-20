<?php
/**
 * Stripe Account widget template.
 *
 * @since 1.0
 */
if ( ! defined('ABSPATH') ) {
	exit;
} ?>
<div class="ts-panel">
	<div class="ac-head">
	   <i class="lab la-stripe-s"></i>
	   <p>Stripe Connect</p>
	</div>
	<div class="ac-body">
		<?php if ( $account->charges_enabled ): ?>
			<p><?= _x( 'Your account is ready to accept payments.', 'stripe', 'voxel' ) ?></p>
		<?php elseif ( $account->details_submitted ): ?>
			<p><?= _x( 'Your account is pending verification.', 'stripe', 'voxel' ) ?></p>
		<?php else: ?>
			<p><?= _x( 'Setup your Stripe account in order to accept payments.', 'stripe', 'voxel' ) ?></p>
		<?php endif ?>
		<div class="ac-bottom">
			<ul class="simplify-ul">
				<?php if ( ! $account->exists ): ?>
					<li>
						<a href="<?= esc_url( $onboard_link ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
							<i class="lab la-stripe-s"></i>
							Start setup
						</a>
					</li>
				<?php elseif ( ! $account->details_submitted ): ?>
					<li>
						<a href="<?= esc_url( $onboard_link ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
							<i class="lab la-stripe-s"></i>
							Submit required information
						</a>
					</li>
				<?php else: ?>
					<li>
						<a href="<?= esc_url( $onboard_link ) ?>" class="ts-btn ts-btn-1 ts-btn-large">
							<i class="lab la-stripe-s"></i>
							Update information
						</a>
					</li>
					<li>
						<a href="<?= esc_url( $dashboard_link ) ?>" target="_blank" class="ts-btn ts-btn-1 ts-btn-large">
							<i class="lab la-stripe-s"></i>
							Stripe dashboard
						</a>
					</li>
				<?php endif ?>
			</ul>
		</div>
	</div>
	
</div>
