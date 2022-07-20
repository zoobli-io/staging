<div class="ts-panel active-plan">
	<div class="ac-head">
	   <i aria-hidden="true" class="las la-parachute-box"></i>
	   <p><?= $membership->plan->get_label() ?></p>
	</div>

	<?php if ( $membership->get_type() === 'subscription' ): ?>
		
		<div class="ac-body">
			<!-- <span class="ac-plan-name"><?= $membership->plan->get_label() ?></span> -->
			
			<div class="ac-plan-pricing">
				<span class="ac-plan-price">
					<?= \Voxel\currency_format( $membership->get_amount(), $membership->get_currency() ) ?>
				</span>
				<div class="ac-price-period">
					/ <?= \Voxel\interval_format( $membership->get_interval(), $membership->get_interval_count() ) ?>
				</div>
			</div>
			<?php if ( $membership->will_cancel_at_period_end() ): ?>
				<p>
					Your subscription will be cancelled on <?= \Voxel\date_format( $membership->get_current_period_end() ) ?>.
					Click <a href="<?= esc_url( $reactivate_url ) ?>" vx-action>here</a> to reactivate.
				</p>
			<?php elseif ( $membership->get_status() === 'trialing' ): ?>
				<p>
					Your trial ends on <?= \Voxel\date_format( $membership->get_trial_end() ) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'active' ): ?>
				<p>
					Your plan renews on <?= \Voxel\date_format( $membership->get_current_period_end() ) ?>
				</p>
			<?php elseif ( $membership->get_status() === 'incomplete' ): ?>
				<p>
					<a href="<?= esc_url( $portal_url ) ?>" target="_blank">Update payment method</a>, then
					<a href="<?= esc_url( $retry_payment_url ) ?>" vx-action>finalize payment</a> to activate your subscription.
				</p>
			<?php elseif ( $membership->get_status() === 'incomplete_expired' ): ?>
				<p>
					Subscription payment failed. Click <a href="<?= esc_url( $switch_url ) ?>">here</a> to pick a new plan.
				</p>
			<?php elseif ( $membership->get_status() === 'past_due' ): ?>
				<p>
					Subscription renewal failed. <a href="<?= esc_url( $portal_url ) ?>" target="_blank">Update payment method</a>, then
					<a href="<?= esc_url( $retry_payment_url ) ?>" vx-action>finalize payment</a> to reactivate your subscription.
				</p>
			<?php elseif ( $membership->get_status() === 'canceled' ): ?>
				<p>Subscription has been canceled. Click <a href="<?= esc_url( $switch_url ) ?>">here</a> to pick a new plan.</p>
			<?php elseif ( $membership->get_status() === 'unpaid' ): ?>
				<p>
					Subscription has been deactivated due to failed renewal payments.
					<a href="<?= esc_url( $portal_url ) ?>" target="_blank">Update payment method</a>, then
					<a href="<?= esc_url( $retry_payment_url ) ?>" vx-action>finalize payment</a> to reactivate your subscription.
				</p>
			<?php endif ?>
			<div class="ac-bottom">
				<ul class="simplify-ul current-plan-btn">
					<li>
						<a href="<?= esc_url( $switch_url ) ?>" class="ts-btn ts-btn-1">
							<i class="las la-exchange-alt"></i> Switch
						</a>
					</li>
					<?php if ( ! in_array( $membership->get_status(), [ 'canceled', 'incomplete_expired' ], true ) ): ?>
						<li>
							<a href="<?= esc_url( $cancel_url ) ?>" vx-action class="ts-btn ts-btn-1">
								<i class="las la-power-off"></i> Cancel
							</a>
						</li>
					<?php endif ?>
					<li>
						<a href="<?= esc_url( $portal_url ) ?>" target="_blank" class="ts-btn ts-btn-1">
							<i class="lab la-stripe-s"></i> Stripe portal
						</a>
					</li>
				</ul>
			</div>
		</div>

		
	<?php endif ?>

	<?php if ( $membership->get_type() === 'default' ): ?>
		<div class="ac-body">
			<p>You do not have an active membership plan. </p>
			<div class="ac-bottom">
				<a href="<?= esc_url( $switch_url ) ?>" class="ts-btn ts-btn-1">
					<i aria-hidden="true" class="las la-parachute-box"></i> Select plan
				</a>
			</div>
		</div>
	<?php endif ?>

</div>
