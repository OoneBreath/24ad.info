<?php

namespace extras\plugins\domainmapping\app\Observers\Traits\Setting;

use App\Helpers\Common\Cookie;

trait ListingsListTrait
{
	/**
	 * Saved
	 *
	 * @param $setting
	 */
	public function listingsListSaved($setting)
	{
		$this->saveTheDisplayModeInCookie($setting);
	}
	
	/**
	 * Save the new Display Mode in cookie
	 *
	 * @param $setting
	 */
	public function saveTheDisplayModeInCookie($setting): void
	{
		// If the Default List Mode is changed, then clear the 'list_display_mode' from the cookies
		// NOTE: The cookie has been set from JavaScript, so we have to provide the good path (maybe the good expire time)
		if (isset($setting->value['display_mode'])) {
			Cookie::forget('display_mode');
			
			$expire = 60 * 24 * 7; // 7 days
			Cookie::set('display_mode', $setting->value['display_mode'], $expire);
		}
	}
}
