<?php

namespace extras\plugins\currencyexchange\app\Http\Requests\Admin\SettingRequest;

use App\Http\Requests\Admin\Request;
use App\Providers\AppService\ConfigTrait\CurrencyexchangeConfig;
use App\Rules\AlphaPlusRule;

/*
 * Use request() instead of $this since this form request can be called from another
 */

class CurrencyexchangeRequest extends Request
{
	use CurrencyexchangeConfig;
	
	private ?string $validDriverParamsRequiredMessage = null;
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		$request = request();
		
		$rules = [
			'currencies' => ['nullable', new AlphaPlusRule(', ')],
			'driver'     => ['nullable', 'string'],
			'cache_ttl'  => ['nullable', 'integer'],
		];
		
		// Is Currency Exchange driver need to be validated?
		$isDriverTestEnabled = ($request->input('driver_test') == '1');
		
		// Get selected GeoIP driver
		$exDriver = $request->input('driver');
		if (empty($exDriver)) {
			return $rules;
		}
		
		// Currency Exchange driver's rules
		if ($exDriver == 'currencylayer') {
			$rules = array_merge($rules, [
				'currencylayer_access_key' => ['nullable'],
			]);
			if ($request->input('currencylayer_pro') == '1') {
				$rules['currencylayer_access_key'] = ['required'];
			}
		}
		
		if ($exDriver == 'exchangerate_api') {
			$rules = array_merge($rules, [
				'exchangerate_api_api_key' => ['required'],
			]);
		}
		
		if ($exDriver == 'exchangeratesapi_io') {
			$rules = array_merge($rules, [
				'exchangeratesapi_io_access_key' => ['nullable'],
			]);
			if ($request->input('exchangeratesapi_io_pro') == '1') {
				$rules['exchangeratesapi_io_access_key'] = ['required'];
			}
		}
		
		if ($exDriver == 'openexchangerates') {
			$rules = array_merge($rules, [
				'openexchangerates_app_id' => ['required'],
			]);
		}
		
		if ($exDriver == 'fixer_io') {
			$rules = array_merge($rules, [
				'fixer_io_access_key' => ['nullable'],
			]);
			if ($request->input('fixer_io_pro') == '1') {
				$rules['fixer_io_access_key'] = ['required'];
			}
		}
		
		if ($exDriver == 'ecb') {
			//...
		}
		
		if ($exDriver == 'cbr') {
			//...
		}
		
		if ($exDriver == 'tcmb') {
			//...
		}
		
		if ($exDriver == 'nbu') {
			//...
		}
		
		if ($exDriver == 'cnb') {
			//...
		}
		
		if ($exDriver == 'bnr') {
			//...
		}
		
		// Get the required fields, then check if required fields are not empty in the request
		$emptyRequiredFields = collect($rules)
			->filter(function ($rule) {
				if (is_array($rule)) {
					return in_array('required', $rule);
				} else if (is_string($rule)) {
					return str_contains($rule, 'required');
				}
				
				return false;
			})->filter(fn ($rule, $field) => empty($request->input($field)));
		
		// Check Currency Exchange fetching parameters
		if ($isDriverTestEnabled && $emptyRequiredFields->isEmpty()) {
			$settings = $request->all();
			
			$errorMessage = $this->testCurrencyexchangeConfig(true, $settings);
			if (!empty($errorMessage)) {
				$rules = array_merge($rules, [
					'valid_driver_params' => 'required',
				]);
				$this->validDriverParamsRequiredMessage = $errorMessage;
			}
		}
		
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function messages(): array
	{
		$messages = [];
		
		if (!empty($this->validDriverParamsRequiredMessage)) {
			$messages['valid_driver_params.required'] = $this->validDriverParamsRequiredMessage;
		}
		
		return array_merge(parent::messages(), $messages);
	}
	
	/**
	 * @return array
	 */
	public function attributes(): array
	{
		$attributes = [
			'driver'                         => trans('currencyexchange::messages.service_label'),
			'driver_test'                    => trans('currencyexchange::messages.driver_test_label'),
			'currencylayer_pro'              => trans('currencyexchange::messages.service_pro_label'),
			'currencylayer_access_key'       => 'Access Key',
			'exchangerate_api_api_key'       => 'API Key',
			'exchangeratesapi_io_pro'        => trans('currencyexchange::messages.service_pro_label'),
			'exchangeratesapi_io_access_key' => 'Access Key',
			'openexchangerates_app_id'       => 'App ID',
			'fixer_io_pro'                   => trans('currencyexchange::messages.service_pro_label'),
			'fixer_io_access_key'            => 'Access Key',
			'cache_ttl'                      => trans('currencyexchange::messages.Cache TTL'),
		];
		
		return array_merge(parent::attributes(), $attributes);
	}
}
