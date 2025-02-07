<?php

namespace extras\plugins\currencyexchange\app\Http\Middleware;

use App\Models\Currency;
use App\Models\Permission;
use Closure;
use extras\plugins\currencyexchange\app\Helpers\CurrencyConverter;
use Illuminate\Http\Request;
use Throwable;

class CurrencyExchange
{
	/**
	 * Get the Currency Exchange Rate between the country default currency and the selected currency
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		// Get country's default currency code
		$currencyCode = config('country.currency');
		
		// Get the country's currency object as array
		$selectedCurrency = config('currency');
		$selectedCurrency['rate'] = 1;
		
		// Get selected currency code
		if (isFromApi()) {
			$headerKey = 'X-CURR';
			
			if (request()->hasHeader($headerKey) || request()->headers->has($headerKey)) {
				$currencyCode = request()->headers->get($headerKey);
				$currencyCode = request()->header($headerKey, $currencyCode);
			} else {
				if ($request->hasHeader($headerKey) || $request->headers->has($headerKey)) {
					$currencyCode = $request->headers->get($headerKey);
					$currencyCode = $request->header($headerKey, $currencyCode);
				}
			}
		} else {
			$sessionKey = 'curr';
			$inputKey = 'curr';
			
			// Get the selected currency code from session (if exists)
			if (session()->has($sessionKey)) {
				$currencyCode = session($sessionKey);
			}
			// Get the selected currency code from input (if exists, then save it in session)
			if (request()->has($inputKey)) {
				$currencyCode = request()->input($inputKey);
				session()->put($sessionKey, $currencyCode);
			}
		}
		
		if (
			!(config('settings.currencyexchange.activation') == '1')
			|| empty($currencyCode)
		) {
			config()->set('selectedCurrency', $selectedCurrency);
			
			return $next($request);
		}
		
		$currency = Currency::find($currencyCode);
		if (empty($currency)) {
			config()->set('selectedCurrency', $selectedCurrency);
			
			return $next($request);
		}
		
		if ($currency->code == config('country.currency')) {
			config()->set('selectedCurrency', $selectedCurrency);
			
			return $next($request);
		}
		
		try {
			$currencyTo = $currency->toArray();
			$rate = CurrencyConverter::getRate($selectedCurrency, $currencyTo);
			
			// If $rate = null, then don't apply the conversion (ie skip the current currency)
			if (is_null($rate)) {
				$guard = getAuthGuard();
				$authUser = auth($guard)->check() ? auth($guard)->user() : null;
				
				if (!empty($authUser) && doesUserHavePermission($authUser, Permission::getStaffPermissions())) {
					$driverName = config(
						'currencyexchange.drivers.' . config('currencyexchange.default') . '.label',
						config('currencyexchange.default')
					);
					$message = trans('currencyexchange::messages.no_exchange_rate_found_admin', [
						'code'   => $currency->code,
						'driver' => $driverName,
					]);
				} else {
					$message = trans('currencyexchange::messages.no_exchange_rate_found', ['code' => $currency->code]);
				}
				
				if (!isFromApi()) {
					notification($message, 'warning');
				}
				
				// Restoring null rate to 1 to prevent zero (0) price issue
				$selectedCurrency['rate'] = 1;
				config()->set('selectedCurrency', $selectedCurrency);
				
				return $next($request);
			}
			
			// Update the selected currency data (after API call is done)
			$selectedCurrency = array_merge($selectedCurrency, $currencyTo);
			$selectedCurrency['rate'] = $rate;
			
		} catch (Throwable $e) {
			$message = $e->getMessage();
			if (!empty($message)) {
				
				if (!isFromApi()) {
					notification($message, 'error');
				}
				
			}
		}
		
		config()->set('selectedCurrency', $selectedCurrency);
		
		return $next($request);
	}
}
