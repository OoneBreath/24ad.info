<?php

namespace extras\plugins\domainmapping\app\Http\Requests;

use App\Http\Requests\Admin\Request;

class DomainRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		$notRegex = '#http[^:]*://#ui';
		
		$rules = [
			'country_code' => ['required', 'min:2', 'max:2'],
			'host'         => ['required', 'not_regex:' . $notRegex],
		];
		
		if (in_array($this->method(), ['POST', 'CREATE'])) {
			$rules['country_code'][] = 'unique:domains,country_code';
			$rules['host'][] = 'unique:domains,host';
		}
		
		if (in_array($this->method(), ['PUT', 'PATCH'])) {
			if ($this->filled('id')) {
				$rules['country_code'][] = 'unique:domains,country_code,' . $this->input('id');
				$rules['host'][] = 'unique:domains,host,' . $this->input('id');
			}
		}
		
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function messages(): array
	{
		return [
			'country_code.required' => trans('domainmapping::messages.validation.country_code.required'),
			'country_code.min'      => trans('domainmapping::messages.validation.country_code.min'),
			'country_code.max'      => trans('domainmapping::messages.validation.country_code.max'),
			'country_code.unique'   => trans('domainmapping::messages.validation.country_code.unique'),
			'host.required'         => trans('domainmapping::messages.validation.host.required'),
			'host.regex'            => trans('domainmapping::messages.validation.host.regex'),
			'host.unique'           => trans('domainmapping::messages.validation.host.unique'),
		];
	}
}
