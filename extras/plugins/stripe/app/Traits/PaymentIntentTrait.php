<?php

namespace extras\plugins\stripe\app\Traits;

use App\Helpers\Common\Num;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use extras\plugins\stripe\app\Helpers\StripeTools;
use Illuminate\Http\Request;

trait PaymentIntentTrait
{
	/**
	 * @param \Stripe\StripeClient $stripe
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param \App\Models\Package $package
	 * @return \Stripe\PaymentIntent|void
	 */
	public static function sendPaymentThroughPaymentIntent(
		Request              $request,
		\Stripe\StripeClient $stripe,
		Post|User            $payable,
		Package              $package
	)
	{
		$isPromoting = ($package->type == 'promotion');
		$isSubscripting = ($package->type == 'subscription');
		
		// Get the amount
		$amount = Num::toFloat($package->price);
		$amount = StripeTools::getAmount($amount, $package->currency_code);
		
		// Card authentication and 3D Secure
		// https://stripe.com/docs/payments/3d-secure
		
		// Get values from form
		$cardNumber = str_replace(' ', '', $request->input('stripeCardNumber'));
		$cardHolderName = $request->input('stripeCardFirstName') . ' ' . $request->input('stripeCardLastName');
		$cardExpiry = $request->input('stripeCardExpiry');
		$cardExpiryArray = preg_split('#(/|-|\|)#', $cardExpiry);
		$cardExpiryMm = (int)($cardExpiryArray[0] ?? null);
		$cardExpiryYy = $cardExpiryArray[1] ?? null;
		$cardExpiryYy = substr(trim($cardExpiryYy), -2);
		$cardCvv = $request->input('stripeCardCVC');
		
		$description = $package->name;
		$description .= $isPromoting ? ' (' . $payable->title . ')' : '';
		$description .= $isSubscripting ? ' (' . $payable->name . ')' : '';
		
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
			
			// Create a PaymentMethod
			// https://stripe.com/docs/api/payment_methods/create
			/*
			 * PaymentMethod 'type' possible enum values:
			 * acss_debit, afterpay_clearpay, alipay, au_becs_debit, bacs_debit, bancontact, boleto
			 * card, eps, fpx, giropay, grabpay, ideal, klarna, oxxo, p24, sepa_debit, sofort, wechat_pay
			 *
			 * If this is a card PaymentMethod, this hash contains the user’s card details.
			 * For backwards compatibility, you can alternatively provide a Stripe token
			 * (e.g., for Apple Pay, Amex Express Checkout, or legacy Checkout) into the card hash with format card: {token: "tok_visa"}.
			 * When providing a card number, you must meet the requirements for PCI compliance (https://stripe.com/docs/security/guide).
			 * We strongly recommend using Stripe.js instead of interacting with this API directly. <= @todo soon
			 */
			$paymentMethod = [
				'type' => 'card', // required
				'card' => [
					'number'    => $cardNumber,   // required
					'exp_month' => $cardExpiryMm, // required - eg. 1
					'exp_year'  => $cardExpiryYy, // required - eg. 2023
					'cvc'       => $cardCvv,      // usually required
				],
			];
			$paymentMethodResponse = $stripe->paymentMethods->create($paymentMethod);
			
			// Get the payment callback URL
			$paymentReturnUrl = parent::$uri['paymentReturnUrl'];
			$paymentReturnUrl .= str_contains($paymentReturnUrl, '?') ? '&' : '?';
			$paymentReturnUrl .= 'sessionId=' . session()->getId();
			
			// Create a PaymentIntent
			// https://stripe.com/docs/api/payment_intents/create
			$paymentIntent = [
				'amount'                 => $amount, // required
				'currency'               => $package->currency_code, // required
				'description'            => $description,
				'payment_method_types'   => ['card'],
				'customer'               => $customerResponse->id,
				'payment_method'         => $paymentMethodResponse->id,
				'confirm'                => true,
				/*
				 * Indicates that you intend to make future payments with this PaymentIntent’s payment method.
				 * Possible enum values: on_session, off_session
				 * - Use 'on_session' if you intend to only reuse the payment method when your customer is present in your checkout flow.
				 * - Use 'off_session' if your customer may or may not be present in your checkout flow
				 */
				'setup_future_usage'     => 'on_session',
				/*
				 * This parameter can only be used with confirm=true
				 */
				'return_url'             => $paymentReturnUrl,
				/*
				 * Card authentication and 3D Secure
				 * https://stripe.com/docs/payments/3d-secure
				 */
				'payment_method_options' => [
					'card' => [
						'request_three_d_secure' => 'automatic',
					],
				],
			];
			
			return $stripe->paymentIntents->create($paymentIntent);
		} catch (\Throwable $e) {
			abort(400, $e->getMessage());
		}
	}
	
	/**
	 * @param \Stripe\StripeClient $stripe
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param array $params
	 * @return \Stripe\PaymentIntent|void
	 */
	public static function paymentConfirmationThroughPaymentIntent(\Stripe\StripeClient $stripe, Post|User $payable, array $params)
	{
		// Get the PaymentIntents ID
		$paymentIntentId = $params['transaction_id'] ?? null;
		
		try {
			// Retrieve the PaymentIntent
			return $stripe->paymentIntents->retrieve($paymentIntentId, []);
		} catch (\Throwable $e) {
			abort(400, $e->getMessage());
		}
	}
}
