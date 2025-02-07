<?php

namespace extras\plugins\stripe\app\Helpers;

use App\Models\Country;
use App\Models\Scopes\ActiveScope;

class StripeTools
{
	protected static int $cacheExpiration = 3600; // In minutes (e.g. 60 * 60 for 1h)
	
	/**
	 * Amount intended to be collected by this payment.
	 * A positive integer representing how much to charge in the smallest currency unit
	 * More Info: https://stripe.com/docs/currencies#zero-decimal
	 * (e.g., 100 cents to charge $1.00 or 100 to charge ¥100, a zero-decimal currency).
	 *
	 * The minimum amount is $0.50 US or equivalent in charge currency.
	 * More Info: https://stripe.com/docs/currencies#minimum-and-maximum-charge-amounts
	 * The amount value supports up to eight digits
	 * (e.g., a value of 99999999 for a USD charge of $999,999.99).
	 *
	 * @param $amount
	 * @param string|null $currencyCode
	 * @return int
	 */
	public static function getAmount($amount, string $currencyCode = null): int
	{
		$exceptCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];
		
		if (!in_array($currencyCode, $exceptCurrencies)) {
			$amount = intval((float)$amount * 100);
		}
		
		return $amount;
	}
	
	/**
	 * Get Countries List
	 */
	public static function getCountries()
	{
		self::$cacheExpiration = (int)config('settings.optimization.cache_expiration', self::$cacheExpiration);
		
		$cacheId = 'stripe.countries.list';
		
		return cache()->remember($cacheId, self::$cacheExpiration, function () {
			return Country::query()
				->withoutGlobalScopes([ActiveScope::class])
				->orderBy('name')
				->get(['code', 'name']);
		});
	}
	
	/**
	 * @return string
	 */
	public static function countriesWhereAddrLine2IsRequired(): string
	{
		$countries = ['CN', 'JP', 'RU'];
		
		return collect($countries)->toJson();
	}
	
	/**
	 * @return string
	 */
	public static function countriesWhereZipCodeIsRequired(): string
	{
		$countries = [
			'AR', 'AU', 'BG', 'CA', 'CN', 'CY', 'EG', 'FR', 'IN', 'ID', 'IT', 'JP', 'MY', 'MX',
			'NL', 'PA', 'PH', 'PL', 'RO', 'RU', 'RS', 'SG', 'ZA', 'ES', 'SE', 'TH', 'TR', 'UK', 'US'
		];
		
		return collect($countries)->toJson();
	}
}
