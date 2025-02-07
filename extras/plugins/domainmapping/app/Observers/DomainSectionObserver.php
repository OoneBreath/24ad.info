<?php

namespace extras\plugins\domainmapping\app\Observers;

use App\Helpers\Common\Files\Storage\StorageDisk;
use extras\plugins\domainmapping\app\Models\DomainSection;
use Illuminate\Support\Facades\Cache;

class DomainSectionObserver
{
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param DomainSection $section
	 * @return void
	 */
	public function updating(DomainSection $section)
	{
		if (isset($section->key) && isset($section->value)) {
			// Get the original object values
			$original = $section->getOriginal();
			
			// Storage Disk Init.
			$disk = StorageDisk::getDisk();
			
			if (is_array($original) && array_key_exists('value', $original)) {
				$original['value'] = jsonToArray($original['value']);
				
				// Remove old background_image from disk
				$key = 'background_image_path';
				if (array_key_exists($key, $section->value)) {
					if (
						is_array($original['value'])
						&& isset($original['value'][$key])
						&& !empty($original['value'][$key])
						&& $section->value[$key] != $original['value'][$key]
						&& $disk->exists($original['value'][$key])
					) {
						$disk->delete($original['value'][$key]);
					}
				}
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param DomainSection $section
	 * @return void
	 */
	public function updated(DomainSection $section)
	{
		//...
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param DomainSection $section
	 * @return void
	 */
	public function saved(DomainSection $section)
	{
		// Removing Entries from the Cache
		$this->clearCache($section);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param DomainSection $section
	 * @return void
	 */
	public function deleted(DomainSection $section)
	{
		// Removing Entries from the Cache
		$this->clearCache($section);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $section
	 * @return void
	 */
	private function clearCache($section): void
	{
		Cache::flush();
	}
}
