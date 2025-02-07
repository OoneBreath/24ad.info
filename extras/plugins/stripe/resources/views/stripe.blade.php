@php
	$stripeIntegration = config('payment.stripe.integration');
@endphp
@if (in_array($stripeIntegration, ['checkout', 'payment_intent']))
	@if (view()->exists('payment::stripe.' . $stripeIntegration))
		@include('payment::stripe.' . $stripeIntegration)
	@endif
@endif