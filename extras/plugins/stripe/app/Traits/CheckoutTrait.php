<?php

namespace extras\plugins\stripe\app\Traits;

use App\Helpers\Common\Num;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use extras\plugins\stripe\app\Helpers\StripeTools;
use Illuminate\Http\Request;

trait CheckoutTrait
{
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \Stripe\StripeClient $stripe
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param \App\Models\Package $package
	 * @return \Stripe\Checkout\Session|void
	 */
	public static function sendPaymentThroughCheckout(
		Request              $request,
		\Stripe\StripeClient $stripe,
		Post|User            $payable,
		Package              $package
	)
	{
		// QuickStart: https://stripe.com/docs/checkout/quickstart
		// Samples: https://github.com/stripe-samples/accept-a-payment
		
		$isPromoting = ($package->type == 'promotion');
		$isSubscripting = ($package->type == 'subscription');
		
		// Get the amount
		$amount = Num::toFloat($package->price);
		$amount = StripeTools::getAmount($amount, $package->currency_code);
		
		// Get values from form
		$cardHolderName = $request->input('stripeCardFirstName') . ' ' . $request->input('stripeCardLastName');
		$productName = $package->name;
		$productName .= $isPromoting ? ' (' . $payable->title . ')' : '';
		$productName .= $isSubscripting ? ' (' . $payable->name . ')' : '';
		
		// Get the current session ID
		$sessionId = session()->getId();
		
		// Get the payment callback URL
		$paymentReturnUrl = parent::$uri['paymentReturnUrl'];
		$paymentReturnUrl .= str_contains($paymentReturnUrl, '?') ? '&' : '?';
		$paymentReturnUrl .= 'sessionId=' . $sessionId;
		
		// Get the payment cancel callback URL
		$paymentCancelUrl = parent::$uri['paymentCancelUrl'];
		$paymentCancelUrl .= str_contains($paymentCancelUrl, '?') ? '&' : '?';
		$paymentCancelUrl .= 'sessionId=' . $sessionId;
		
		try {
			// Create a Customer
			// https://stripe.com/docs/api/customers/create
			$customer = [
				'name'    => $cardHolderName,
				'email'   => $payable->email ?? '',
				'address' => [
					'line1'       => $request->input('stripeBillingAddress1'),
					'line2'       => $request->input('stripeBillingAddress2'),
					'postal_code' => $request->input('stripeBillingZipCode'),
					'city'        => $request->input('stripeBillingCity'),
					'state'       => $request->input('stripeBillingState'),
					'country'     => $request->input('stripeBillingCountry'),
				],
			];
			$customerResponse = $stripe->customers->create($customer);
			
			// Create a Payment
			// https://stripe.com/docs/payments/accept-a-payment?integration=checkout
			$payment = [
				'customer'    => $customerResponse->id,
				'line_items'  => [
					[
						'price_data' => [
							'currency'     => $package->currency_code,
							'product_data' => [
								'name' => $productName,
							],
							'unit_amount'  => $amount,
						],
						'quantity'   => 1,
					],
				],
				'mode'        => 'payment',
				'success_url' => $paymentReturnUrl,
				'cancel_url'  => $paymentCancelUrl,
			];
			
			return $stripe->checkout->sessions->create($payment);
		} catch (\Throwable $e) {
			abort(400, $e->getMessage());
		}
	}
	
	/**
	 * @param \Stripe\StripeClient $stripe
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param array $params
	 * @return \Stripe\Checkout\Session|void
	 */
	public static function paymentConfirmationThroughCheckout(\Stripe\StripeClient $stripe, Post|User $payable, array $params)
	{
		// Get the Payment ID
		$paymentId = $params['transaction_id'] ?? null;
		
		try {
			// Retrieve the Payment
			return $stripe->checkout->sessions->retrieve($paymentId, []);
		} catch (\Throwable $e) {
			abort(400, $e->getMessage());
		}
	}
}
