<?php

return [
	
	'stripe' => [
		/*
		 * Integration can be: checkout, payment_intent
		 * - In order to use 'checkout', you must set an account or business name at https://dashboard.stripe.com/account.
		 * - In order to use 'payment_intent', you must comply the PCI requirements: https://stripe.com/docs/security#pci-dss-guidelines
		 */
		'integration'    => env('STRIPE_INTEGRATION', 'checkout'),
		'key'            => env('STRIPE_KEY', ''),
		'secret'         => env('STRIPE_SECRET', ''),
		
		// cURL options
		'curlProxy'      => env('STRIPE_CURL_PROXY', ''),      // Host & Port - e.g. proxy.local:80
		'curlSslVersion' => env('STRIPE_CURL_SSLVERSION', ''), // CURL_SSLVERSION_TLSv1 or CURL_SSLVERSION_TLSv1_2
		
		/*
		 * Referrers' hosts
		 * Used to allow HTTP POST requests from this gateway to the core app when the CSRF protection is activated
		 */
		'referrersHosts' => ['stripe.com'],
	],

];
