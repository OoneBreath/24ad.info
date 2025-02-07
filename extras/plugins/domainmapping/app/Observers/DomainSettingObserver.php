<?php

namespace extras\plugins\domainmapping\app\Observers;

use Exception;
use extras\plugins\domainmapping\app\Models\DomainSetting;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\AppTrait;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\ListingFormTrait;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\ListingsListTrait;
use extras\plugins\domainmapping\app\Observers\Traits\Setting\StyleTrait;

class DomainSettingObserver
{
	use AppTrait, ListingFormTrait, ListingsListTrait, StyleTrait;
	
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param DomainSetting $setting
	 * @return void
	 */
	public function updating(DomainSetting $setting)
	{
		if (isset($setting->key) && isset($setting->value)) {
			// Get the original object values
			$original = $setting->getOriginal();
			
			if (is_array($original) && array_key_exists('value', $original)) {
				$original['value'] = jsonToArray($original['value']);
				
				$settingMethodName = $this->getSettingMethod($setting, __FUNCTION__);
				if (method_exists($this, $settingMethodName)) {
					$this->$settingMethodName($setting, $original);
				}
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param DomainSetting $setting
	 * @return void
	 */
	public function saved(DomainSetting $setting)
	{
		$settingMethodName = $this->getSettingMethod($setting, __FUNCTION__);
		if (method_exists($this, $settingMethodName)) {
			$this->$settingMethodName($setting);
		}
		
		// Removing Entries from the Cache
		$this->clearCache($setting);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param DomainSetting $setting
	 * @return void
	 */
	public function deleted(DomainSetting $setting)
	{
		// Removing Entries from the Cache
		$this->clearCache($setting);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $setting
	 */
	private function clearCache($setting)
	{
		try {
			cache()->flush();
		} catch (Exception $e) {
		}
	}
	
	/**
	 * Get Setting class's method name
	 *
	 * @param DomainSetting $setting
	 * @param string $suffix
	 * @return string
	 */
	private function getSettingMethod(DomainSetting $setting, string $suffix = ''): string
	{
		$countryCode = $setting->country_code ?? '';
		$classKey = $setting->key ?? '';
		
		// Get valid setting key
		$countryCode = str($countryCode)->lower()->append('_')->toString();
		$classKey = str($classKey)->replaceFirst($countryCode, '')->toString();
		$suffix = str($suffix)->ucfirst()->toString();
		
		return str($classKey)
			->camel()
			->append($suffix)
			->toString();
	}
}
