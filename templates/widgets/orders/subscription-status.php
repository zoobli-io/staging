<script type="text/html" id="orders-subscription-status">
	<p v-if="subscription.cancel_at_period_end">
		Your subscription will be cancelled on {{ subscription.current_period_end }}.
		Click <a href="#" @click.prevent="order.doAction('customer.subscriptions.reactivate')">here</a> to reactivate
	</p>
	<p v-else-if="subscription.status === 'trialing'">
		Your trial ends on {{ subscription.trial_end }}
	</p>
	<p v-else-if="subscription.status === 'active'">
		Your subscription renews on {{ subscription.current_period_end }}
	</p>
	<p v-else-if="subscription.status === 'incomplete'">
		<a href="#" @click.prevent="order.doAction('customer.portal')">Update payment method</a>,
		then <a href="#" @click.prevent="order.doAction('customer.subscriptions.finalize_payment')">finalize payment</a>
		to activate your subscription.
	</p>
	<p v-else-if="subscription.status === 'incomplete_expired'">
		Subscription payment failed
	</p>
	<p v-else-if="subscription.status === 'past_due'">
		Subscription renewal failed. <a href="#" @click.prevent="order.doAction('customer.portal')">Update payment method</a>,
		then <a href="#" @click.prevent="order.doAction('customer.subscriptions.finalize_payment')">finalize payment</a>
		to reactivate your subscription.
	</p>
	<p v-else-if="subscription.status === 'canceled'">
		Subscription has been canceled
	</p>
	<p v-else-if="subscription.status === 'unpaid'">
		Subscription has been deactivated due to failed renewal payments.
		<a href="#" @click.prevent="order.doAction('customer.portal')">Update payment method</a>,
		then <a href="#" @click.prevent="order.doAction('customer.subscriptions.finalize_payment')">finalize payment</a>
		to reactivate your subscription.
	</p>
</script>
