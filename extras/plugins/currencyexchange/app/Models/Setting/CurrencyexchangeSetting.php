<?php

namespace extras\plugins\currencyexchange\app\Models\Setting;

use App\Models\Currency;

class CurrencyexchangeSetting
{
	public static function getValues($value, $disk)
	{
		$activation = '1';
		$currencies = 'USD,EUR';
		$driver = 'ecb';
		$currencylayerBase = config('currencyexchange.drivers.currencylayer.currencyBase', 'USD');
		$exchangerateApiBase = config('currencyexchange.drivers.exchangerate_api.currencyBase', 'USD');
		$exchangeratesapiIoBase = config('currencyexchange.drivers.exchangeratesapi_io.currencyBase', 'USD');
		$openexchangeratesBase = config('currencyexchange.drivers.openexchangerates.currencyBase', 'USD');
		$fixerIoBase = config('currencyexchange.drivers.fixer_io.currencyBase', 'EUR');
		$cacheTtl = '86400';
		
		if (empty($value)) {
			
			$value['activation'] = $activation;
			$value['currencies'] = $currencies;
			$value['driver'] = $driver;
			$value['currencylayer_base'] = $currencylayerBase;
			$value['exchangerate_api_base'] = $exchangerateApiBase;
			$value['exchangeratesapi_io_base'] = $exchangeratesapiIoBase;
			$value['openexchangerates_base'] = $openexchangeratesBase;
			$value['fixer_io_base'] = $fixerIoBase;
			$value['cache_ttl'] = $cacheTtl;
			
		} else {
			
			if (!array_key_exists('activation', $value)) {
				$value['activation'] = $activation;
			}
			if (!array_key_exists('currencies', $value)) {
				$value['currencies'] = $currencies;
			}
			if (!array_key_exists('driver', $value)) {
				$value['driver'] = $driver;
			}
			if (!array_key_exists('currencylayer_base', $value)) {
				$value['currencylayer_base'] = $currencylayerBase;
			}
			if (!array_key_exists('exchangerate_api_base', $value)) {
				$value['exchangerate_api_base'] = $exchangerateApiBase;
			}
			if (!array_key_exists('exchangeratesapi_io_base', $value)) {
				$value['exchangeratesapi_io_base'] = $exchangeratesapiIoBase;
			}
			if (!array_key_exists('openexchangerates_base', $value)) {
				$value['openexchangerates_base'] = $openexchangeratesBase;
			}
			if (!array_key_exists('fixer_io_base', $value)) {
				$value['fixer_io_base'] = $fixerIoBase;
			}
			if (!array_key_exists('cache_ttl', $value)) {
				$value['cache_ttl'] = $cacheTtl;
			}
			
		}
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		// Get Currencies Codes
		$currencies = Currency::query()->get(['code']);
		$currencies = ($currencies->count() > 0) ? $currencies->keyBy('code') : collect();
		$currenciesCodes = $currencies->keys()->toArray();
		$currenciesCodesAssoc = $currencies->mapWithKeys(fn ($item, $key) => [$key => $key])->toArray();
		
		// Get Drivers List
		$currencyexchangeDrivers = collect(config('currencyexchange.drivers'))
			->mapWithKeys(function ($item, $key) {
				return [$key => ($item['label'] ?? $key)];
			})->toArray();
		
		// Get the drivers selectors list as JS objects
		$currencyexchangeDriversSelectorsJson = collect($currencyexchangeDrivers)
			->keys()
			->mapWithKeys(fn ($item) => [$item => '.' . $item])
			->toJson();
		
		$fields = [
			[
				'name'  => 'activation',
				'label' => trans('currencyexchange::messages.Enable the Currency Exchange Option'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('currencyexchange::messages.Enable/Disable the Currency Exchange Option.'),
			],
			[
				'name'              => 'currencies',
				'label'             => trans("currencyexchange::messages.Currencies"),
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => trans('currencyexchange::messages.eg_currencies_field'),
				],
				'hint'              => trans('currencyexchange::messages.currencies_codes_list_menu_hint', ['url' => admin_url('currencies')])
					. '<br>' . trans('currencyexchange::messages.Use the codes below')
					. '<br>' . implode(', ', $currenciesCodes)
					. '<br>---<br>'
					. trans('currencyexchange::messages.currencies_codes_list_menu_hint_note'),
				'wrapper' => [
					'class' => 'col-md-12 ex-enabled',
				],
			],
			
			[
				'name'              => 'service_title',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.service_title'),
				'wrapper' => [
					'class' => 'col-md-12 ex-enabled',
				],
			],
			[
				'name'              => 'driver',
				'label'             => trans('currencyexchange::messages.service_label'),
				'type'              => 'select2_from_array',
				'options'           => $currencyexchangeDrivers,
				'hint'              => trans('currencyexchange::messages.service_hint'),
				'wrapper' => [
					'class' => 'col-md-6 ex-enabled',
				],
			],
			[
				'name'              => 'driver_test',
				'label'             => trans('currencyexchange::messages.driver_test_label'),
				'type'              => 'checkbox_switch',
				'hint'              => trans('currencyexchange::messages.driver_test_hint'),
				'wrapper' => [
					'class' => 'col-md-6 mt-4 ex-enabled',
				],
			],
		];
		
		// currencylayer.com
		if (array_key_exists('currencylayer', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'currencylayer_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.currencylayer_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled currencylayer',
					],
				],
				[
					'name'              => 'currencylayer_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.currencylayer_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled currencylayer',
					],
				],
				[
					'name'              => 'currencylayer_base',
					'label'             => trans('currencyexchange::messages.currency_base_label'),
					'type'              => 'select2_from_array',
					'options'           => $currenciesCodesAssoc,
					'hint'              => trans('currencyexchange::messages.currency_base_hint'),
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled currencylayer',
					],
				],
				[
					'name'              => 'currencylayer_pro',
					'label'             => trans('currencyexchange::messages.service_pro_label'),
					'type'              => 'checkbox_switch',
					'wrapper' => [
						'class' => 'col-md-6 mt-4 ex-enabled currencylayer',
					],
				],
				[
					'name'              => 'currencylayer_access_key',
					'label'             => 'Access Key',
					'type'              => 'text',
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled currencylayer',
					],
				],
			]);
		}
		
		// exchangerate-api.com
		if (array_key_exists('exchangerate_api', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'exchangerate_api_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.exchangerate_api_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled exchangerate_api',
					],
				],
				[
					'name'              => 'exchangerate_api_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.exchangerate_api_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled exchangerate_api',
					],
				],
				[
					'name'              => 'exchangerate_api_base',
					'label'             => trans('currencyexchange::messages.currency_base_label'),
					'type'              => 'select2_from_array',
					'options'           => $currenciesCodesAssoc,
					'hint'              => trans('currencyexchange::messages.currency_base_hint'),
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled exchangerate_api',
					],
				],
				[
					'name'              => 'exchangerate_api_api_key',
					'label'             => 'API Key',
					'type'              => 'text',
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled exchangerate_api',
					],
				],
			]);
		}
		
		// exchangeratesapi.io
		if (array_key_exists('exchangeratesapi_io', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'exchangeratesapi_io_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.exchangeratesapi_io_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled exchangeratesapi_io',
					],
				],
				[
					'name'              => 'exchangeratesapi_io_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.exchangeratesapi_io_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled exchangeratesapi_io',
					],
				],
				[
					'name'              => 'exchangeratesapi_io_base',
					'label'             => trans('currencyexchange::messages.currency_base_label'),
					'type'              => 'select2_from_array',
					'options'           => $currenciesCodesAssoc,
					'hint'              => trans('currencyexchange::messages.currency_base_hint'),
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled exchangeratesapi_io',
					],
				],
				[
					'name'              => 'exchangeratesapi_io_pro',
					'label'             => trans('currencyexchange::messages.service_pro_label'),
					'type'              => 'checkbox_switch',
					'wrapper' => [
						'class' => 'col-md-6 mt-4 ex-enabled exchangeratesapi_io',
					],
				],
				[
					'name'              => 'exchangeratesapi_io_access_key',
					'label'             => 'Access Key',
					'type'              => 'text',
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled exchangeratesapi_io',
					],
				],
			]);
		}
		
		// openexchangerates.org
		if (array_key_exists('openexchangerates', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'openexchangerates_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.openexchangerates_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled openexchangerates',
					],
				],
				[
					'name'              => 'openexchangerates_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.openexchangerates_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled openexchangerates',
					],
				],
				[
					'name'              => 'openexchangerates_base',
					'label'             => trans('currencyexchange::messages.currency_base_label'),
					'type'              => 'select2_from_array',
					'options'           => $currenciesCodesAssoc,
					'hint'              => trans('currencyexchange::messages.currency_base_hint'),
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled openexchangerates',
					],
				],
				[
					'name'              => 'openexchangerates_app_id',
					'label'             => 'App ID',
					'type'              => 'text',
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled openexchangerates',
					],
				],
			]);
		}
		
		// fixer.io
		if (array_key_exists('fixer_io', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'fixer_io_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.fixer_io_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled fixer_io',
					],
				],
				[
					'name'              => 'fixer_io_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.fixer_io_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled fixer_io',
					],
				],
				[
					'name'              => 'fixer_io_base',
					'label'             => trans('currencyexchange::messages.currency_base_label'),
					'type'              => 'select2_from_array',
					'options'           => $currenciesCodesAssoc,
					'hint'              => trans('currencyexchange::messages.currency_base_hint'),
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled fixer_io',
					],
				],
				[
					'name'              => 'fixer_io_pro',
					'label'             => trans('currencyexchange::messages.service_pro_label'),
					'type'              => 'checkbox_switch',
					'wrapper' => [
						'class' => 'col-md-6 mt-4 ex-enabled fixer_io',
					],
				],
				[
					'name'              => 'fixer_io_access_key',
					'label'             => 'Access Key',
					'type'              => 'text',
					'wrapper' => [
						'class' => 'col-md-6 ex-enabled fixer_io',
					],
				],
			]);
		}
		
		// ecb (European Central Bank)
		if (array_key_exists('ecb', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'ecb_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.ecb_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled ecb',
					],
				],
				[
					'name'              => 'ecb_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.ecb_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled ecb',
					],
				],
			]);
		}
		
		// cbr (Russian Central Bank)
		if (array_key_exists('cbr', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'cbr_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.cbr_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled cbr',
					],
				],
				[
					'name'              => 'cbr_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.cbr_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled cbr',
					],
				],
			]);
		}
		
		// tcmb (Central Bank of Turkey)
		if (array_key_exists('tcmb', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'tcmb_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.tcmb_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled tcmb',
					],
				],
				[
					'name'              => 'tcmb_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.tcmb_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled tcmb',
					],
				],
			]);
		}
		
		// nbu (National Bank of Ukraine)
		if (array_key_exists('nbu', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'nbu_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.nbu_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled nbu',
					],
				],
				[
					'name'              => 'nbu_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.nbu_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled nbu',
					],
				],
			]);
		}
		
		// cnb (Central Bank of the Czech Republic)
		if (array_key_exists('cnb', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'cnb_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.cnb_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled cnb',
					],
				],
				[
					'name'              => 'cnb_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.cnb_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled cnb',
					],
				],
			]);
		}
		
		// bnr (National Bank of Romania)
		if (array_key_exists('bnr', $currencyexchangeDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'bnr_title',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.bnr_title'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled bnr',
					],
				],
				[
					'name'              => 'bnr_info',
					'type'              => 'custom_html',
					'value'             => trans('currencyexchange::messages.bnr_info'),
					'wrapper' => [
						'class' => 'col-md-12 ex-enabled bnr',
					],
				],
			]);
		}
		
		// Other Options
		$fields = array_merge($fields, [
			[
				'name'              => 'options_title',
				'type'              => 'custom_html',
				'value'             => trans('currencyexchange::messages.options_title'),
				'wrapper' => [
					'class' => 'col-md-12 ex-enabled',
				],
			],
			[
				'name'              => 'cache_ttl',
				'label'             => trans('currencyexchange::messages.Cache TTL'),
				'type'              => 'number',
				'attributes'        => [
					'min'  => 1,
					'step' => '1',
				],
				'hint'              => trans('currencyexchange::messages.The cache ttl in seconds.'),
				'wrapper' => [
					'class' => 'col-md-6 ex-enabled',
				],
			],
		]);
		
		return addOptionsGroupJavaScript(__NAMESPACE__, __CLASS__, $fields, [
			'currencyexchangeDriversSelectorsJson' => $currencyexchangeDriversSelectorsJson,
		]);
	}
}
