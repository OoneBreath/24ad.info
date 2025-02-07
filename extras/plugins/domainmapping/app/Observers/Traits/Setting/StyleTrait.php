<?php

namespace extras\plugins\domainmapping\app\Observers\Traits\Setting;

use App\Helpers\Common\Files\Storage\StorageDisk;

trait StyleTrait
{
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 */
	public function styleUpdating($setting, $original)
	{
		// Storage Disk Init.
		$disk = StorageDisk::getDisk();
		
		$this->removeOldBodyBackgroundImage($setting, $original, $disk);
	}
	
	/**
	 * Remove old body_background_image from disk
	 *
	 * @param $setting
	 * @param $original
	 * @param $disk
	 */
	private function removeOldBodyBackgroundImage($setting, $original, $disk): void
	{
		$key = 'body_background_image_path';
		if (array_key_exists($key, $setting->value)) {
			if (
				is_array($original['value'])
				&& isset($original['value'][$key])
				&& $setting->value[$key] != $original['value'][$key]
				&& !str_contains($original['value'][$key], config('larapen.media.picture'))
				&& $disk->exists($original['value'][$key])
			) {
				$disk->delete($original['value'][$key]);
			}
		}
	}
}
