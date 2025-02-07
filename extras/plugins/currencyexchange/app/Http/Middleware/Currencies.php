<?php

namespace extras\plugins\currencyexchange\app\Http\Middleware;

use App\Models\Currency;
use Closure;

class Currencies
{
	/**
	 * Get the Currency List in which you would like your users to do conversions
	 *
	 * @param $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!config('settings.currencyexchange.activation')) {
			return $next($request);
		}
		
		$currenciesCodes = config('settings.currencyexchange.currencies');
		if (config('country.currencies')) {
			$currenciesCodes = config('country.currencies');
		}
		
		// Ensure that '$currenciesCodes' is an array
		$currenciesCodes = !is_array($currenciesCodes)
			? (is_string($currenciesCodes) ? explode(',', $currenciesCodes) : [])
			: $currenciesCodes;
		
		$currenciesCodes = collect($currenciesCodes)
			->map(fn ($value) => trim($value))
			->filter(fn ($value) => (!empty($value) && $value != config('country.currency')))
			->push(config('country.currency'))
			->sort()
			->toArray();
		
		$currencies = [];
		foreach ($currenciesCodes as $currencyCode) {
			$currency = Currency::find($currencyCode);
			if (!empty($currency)) {
				$currencies[$currency->code] = collect([
					'code'   => $currency->code,
					'symbol' => $currency->symbol,
				]);
			}
		}
		
		config()->set('currencies', $currencies);
		if (!isFromApi()) {
			view()->share('currencies', $currencies);
		}
		
		return $next($request);
	}
}
