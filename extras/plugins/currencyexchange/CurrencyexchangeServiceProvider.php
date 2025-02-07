<?php

namespace extras\plugins\currencyexchange;

use App\Providers\AppService\ConfigTrait\CurrencyexchangeConfig;
use extras\plugins\currencyexchange\app\Http\Middleware\Currencies;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CurrencyexchangeServiceProvider extends ServiceProvider
{
	use CurrencyexchangeConfig;
	
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		// Merge plugin config
		$this->mergeConfigFrom(realpath(__DIR__ . '/config.php'), 'currencyexchange');
		
		// Load plugin views
		$this->loadViewsFrom(realpath(__DIR__ . '/resources/views'), 'currencyexchange');
		
		// Load plugin languages files
		$this->loadTranslationsFrom(realpath(__DIR__ . '/lang'), 'currencyexchange');
		
		$this->registerMiddlewares($this->app->router);
		
		// CurrencyExchange Config
		$this->updateCurrencyexchangeConfig(config('settings.currencyexchange'));
	}
	
	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register(): void
	{
		$this->app->bind('currencyexchange', fn () => new Currencyexchange());
	}
	
	public function registerMiddlewares(Router $router): void
	{
		Route::aliasMiddleware('currencies', Currencies::class);
		Route::aliasMiddleware('currencyExchange', \extras\plugins\currencyexchange\app\Http\Middleware\CurrencyExchange::class);
	}
}
