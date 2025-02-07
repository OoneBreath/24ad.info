<?php

namespace extras\plugins\stripe;

use App\Models\Post;
use App\Models\User;
use extras\plugins\stripe\app\Traits\InstallTrait;
use extras\plugins\stripe\app\Traits\CheckoutTrait;
use extras\plugins\stripe\app\Traits\PaymentIntentTrait;
use Illuminate\Http\Request;
use App\Helpers\Services\Payment;
use App\Models\Package;

class Stripe extends Payment
{
	use InstallTrait, CheckoutTrait, PaymentIntentTrait;
	
	private static string $integrationError = 'The "integration" parameter is not set.';
	
	/**
	 * Send Payment
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param array $resData
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public static function sendPayment(Request $request, Post|User $payable, array $resData = [])
	{
		// Set the right URLs
		parent::setRightUrls($resData);
		
		// Get the Package
		$package = Package::find($request->input('package_id'));
		
		// Don't make a payment if 'price' = 0 or null
		if (empty($package) || $package->price <= 0) {
			return redirect(parent::$uri['previousUrl'] . '?error=package')->withInput();
		}
		
		// Don't make payment if selected Package is not compatible with payable (Post|User)
		if (!parent::isPayableCompatibleWithPackage($payable, $package)) {
			return redirect(parent::$uri['previousUrl'] . '?error=packageType')->withInput();
		}
		
		// API Parameters
		// Check out the ./app/Traits/ directory
		
		// Local Parameters
		$localParams = parent::getLocalParameters($request, $payable, $package);
		
		// Try to make the Payment
		try {
			// Include Stripe PHP library
			require_once(__DIR__ . '/app/Helpers/stripe-php/init.php');
			
			// Set up your tweaked Curl client
			$curlParams = [];
			if (!empty(config('payment.stripe.curlSslVersion'))) {
				$curlParams[CURLOPT_SSLVERSION] = config('payment.stripe.curlSslVersion');
			}
			if (!empty(config('payment.stripe.curlProxy'))) {
				$curlParams[CURLOPT_PROXY] = config('payment.stripe.curlProxy');
			}
			$curl = new \Stripe\HttpClient\CurlClient($curlParams);
			
			// Tell Stripe to use the tweaked client
			\Stripe\ApiRequestor::setHttpClient($curl);
			
			$stripe = new \Stripe\StripeClient(config('payment.stripe.secret'));
			
			// Make the Payment
			if (config('payment.stripe.integration') == 'checkout') {
				$response = self::sendPaymentThroughCheckout($request, $stripe, $payable, $package);
			} else if (config('payment.stripe.integration') == 'payment_intent') {
				$response = self::sendPaymentThroughPaymentIntent($request, $stripe, $payable, $package);
			} else {
				return parent::paymentFailureActions($payable, self::$integrationError);
			}
			
			// Save the Transaction ID at the Provider
			if (isset($response->id)) {
				$localParams['transaction_id'] = $response->id;
			}
			
			// For 'checkout' integration only
			if (config('payment.stripe.integration') == 'checkout') {
				$isValidPaymentUrl = (
					!empty($response->url)
					&& is_string($response->url)
					&& str_starts_with(strtolower($response->url), 'http')
				);
				if ($isValidPaymentUrl) {
					// Save local parameters into session
					session()->put('params', $localParams);
					session()->save(); // If redirection to an external URL will be done using PHP header() function
					
					// Redirect the customer to Stripe Checkout
					redirectUrl($response->url, 303);
				} else {
					$errorMessage = trans('stripe::messages.payment_page_url_not_found');
					
					// Apply actions when Payment failed
					return parent::paymentFailureActions($payable, $errorMessage);
				}
			}
			
			// For 'payment_intent' integration only
			/*
			 * Card authentication and 3D Secure
			 * https://stripe.com/docs/payments/3d-secure
			 *
			 * Redirect to the bank website
			 * To redirect your customer to the 3DS authentication page, pass a return_url to the PaymentIntent when confirming on the server or on the client.
			 * You can also set return_url when creating the PaymentIntent.
			 *
			 * After confirmation, if a PaymentIntent has a requires_action status, inspect the PaymentIntent’s next_action.
			 * If it’s redirect_to_url, that means 3DS is required.
			 */
			if (config('payment.stripe.integration') == 'payment_intent') {
				$isValidPaymentUrl = (
					!empty($response->next_action->redirect_to_url->url)
					&& is_string($response->next_action->redirect_to_url->url)
					&& str_starts_with(strtolower($response->next_action->redirect_to_url->url), 'http')
				);
				if ($isValidPaymentUrl) {
					// Save local parameters into session
					session()->put('params', $localParams);
					session()->save(); // If redirection to an external URL will be done using PHP header() function
					
					// Redirect the customer to in order to authenticate the payment
					redirectUrl($response->next_action->redirect_to_url->url);
				}
			}
			
			// For 'charge' & 'payment_intent' integrations only
			if ($response->status == 'succeeded') {
				
				// Save local parameters into session
				session()->put('params', $localParams);
				session()->save(); // If redirection to an external URL will be done using PHP header() function
				
				// Apply actions after successful Payment
				return self::paymentConfirmationActions($payable, $localParams);
				
			} else {
				
				// Apply actions when Payment failed
				return parent::paymentFailureActions($payable);
				
			}
		} catch (\Throwable $e) {
			
			// Apply actions when API failed
			return parent::paymentApiErrorActions($payable, $e);
			
		}
	}
	
	/**
	 * NOTE: Not managed by a route.
	 * Check the method: \App\Http\Controllers\Api\Payment\MakePayment::paymentConfirmation()
	 *
	 * @param \App\Models\Post|\App\Models\User $payable
	 * @param array $params
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public static function paymentConfirmation(Post|User $payable, array $params)
	{
		// Replace patterns in URLs
		parent::$uri = parent::replacePatternsInUrls($payable, parent::$uri);
		
		// Get Charge ID
		$chargeId = $params['transaction_id'] ?? null;
		
		// Include Stripe PHP library
		require_once(__DIR__ . '/app/Helpers/stripe-php/init.php');
		
		try {
			
			// Set up your tweaked Curl client
			$curlParams = [];
			if (!empty(config('payment.stripe.curlSslVersion'))) {
				$curlParams[CURLOPT_SSLVERSION] = config('payment.stripe.curlSslVersion');
			}
			if (!empty(config('payment.stripe.curlProxy'))) {
				$curlParams[CURLOPT_PROXY] = config('payment.stripe.curlProxy');
			}
			$curl = new \Stripe\HttpClient\CurlClient($curlParams);
			
			// Tell Stripe to use the tweaked client
			\Stripe\ApiRequestor::setHttpClient($curl);
			
			$stripe = new \Stripe\StripeClient(config('payment.stripe.secret'));
			
			// Retrieve the Payment Info
			if (config('payment.stripe.integration') == 'checkout') {
				$response = self::paymentConfirmationThroughCheckout($stripe, $payable, $params);
			} else if (config('payment.stripe.integration') == 'payment_intent') {
				$response = self::paymentConfirmationThroughPaymentIntent($stripe, $payable, $params);
			} else {
				return parent::paymentFailureActions($payable, self::$integrationError);
			}
			
			if (
				(
					in_array(config('payment.stripe.integration'), ['charge', 'payment_intent'])
					&& $response->status == 'succeeded'
				)
				|| (
					config('payment.stripe.integration') == 'checkout'
					&& $response->status == 'complete'
					&& $response->payment_status == 'paid'
				)
			) {
				
				// Apply actions after successful Payment
				return parent::paymentConfirmationActions($payable, $params);
				
			} else {
				
				// Apply actions when Payment failed
				return parent::paymentFailureActions($payable);
				
			}
			
		} catch (\Throwable $e) {
			
			// Apply actions when API failed
			return parent::paymentApiErrorActions($payable, $e);
			
		}
	}
}
