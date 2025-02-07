<?php

namespace extras\plugins\twocheckout\app\Helpers;

use App\Models\Country;
use App\Models\Scopes\ActiveScope;

class CoTools
{
	protected static int $cacheExpiration = 3600; // In minutes (e.g. 60 * 60 for 1h)
	
	/**
	 * Get Countries List
	 */
	public static function getCountries()
	{
		self::$cacheExpiration = (int)config('settings.optimization.cache_expiration', self::$cacheExpiration);
		
		$cacheId = 'twocheckout.iso3.countries.list';
		
		return cache()->remember($cacheId, self::$cacheExpiration, function () {
			return Country::query()
				->withoutGlobalScopes([ActiveScope::class])
				->orderBy('name')
				->get(['iso3', 'code', 'name']);
		});
	}
	
	/**
	 * @return string
	 */
	public static function countriesWhereAddrLine2IsRequired(): string
	{
		$countries = ['CHN', 'JPN', 'RUS'];
		
		return collect($countries)->toJson();
	}
	
	/**
	 * @return string
	 */
	public static function countriesWhereZipCodeIsRequired(): string
	{
		$countries = [
			'ARG', 'AUS', 'BGR', 'CAN', 'CHN', 'CYP', 'EGY', 'FRA', 'IND', 'IDN', 'ITA', 'JPN', 'MYS', 'MEX', 'NLD',
			'PAN', 'PHL', 'POL', 'ROU', 'RUS', 'SRB', 'SGP', 'ZAF', 'ESP', 'SWE', 'THA', 'TUR', 'GBR', 'USA'
		];
		
		return collect($countries)->toJson();
	}
}
