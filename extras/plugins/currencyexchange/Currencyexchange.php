<?php

namespace extras\plugins\currencyexchange;

use App\Models\Setting;
use extras\plugins\currencyexchange\database\MigrationsTrait;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Currencyexchange
{
	use MigrationsTrait;
	
	/**
	 * @return array
	 */
	public static function getOptions(): array
	{
		$options = [];
		$setting = Setting::active()->where('key', 'currencyexchange')->first();
		if (!empty($setting)) {
			$options[] = (object)[
				'name'     => mb_ucfirst(trans('admin.settings')),
				'url'      => admin_url('settings/' . $setting->id . '/edit'),
				'btnClass' => 'btn-info',
			];
		}
		
		return $options;
	}
	
	/**
	 * @return bool
	 */
	public static function installed(): bool
	{
		$cacheExpiration = 86400; // Cache for 1 day (60 * 60 * 24)
		
		return cache()->remember('plugins.currencyexchange.installed', $cacheExpiration, function () {
			$setting = Setting::active()->where('key', 'currencyexchange')->first();
			if (!empty($setting)) {
				if (Schema::hasColumn('countries', 'currencies') && Schema::hasColumn('currencies', 'rate')) {
					return true;
				}
			}
			
			return false;
		});
	}
	
	/**
	 * @return bool
	 */
	public static function install(): bool
	{
		// Uninstall the plugin
		if (Schema::hasColumn('countries', 'currencies') || Schema::hasColumn('currencies', 'rate')) {
			self::uninstall();
		}
		
		try {
			// Run the plugin's install migration
			self::migrationsInstall();
			
			// Create plugin setting
			$pluginSetting = [
				'key'         => 'currencyexchange',
				'name'        => 'Currency Exchange',
				'description' => 'Currency Exchange Plugin',
			];
			
			return createPluginSetting($pluginSetting);
		} catch (Throwable $e) {
			return false;
		}
	}
	
	/**
	 * @return bool
	 */
	public static function uninstall(): bool
	{
		try {
			cache()->forget('plugins.currencyexchange.installed');
		} catch (Throwable $e) {
		}
		
		try {
			// Remove plugin session
			if (session()->has('curr')) {
				session()->forget('curr');
			}
			
			// Run the plugin's uninstall migration
			self::migrationsUninstall();
			
			// Remove the plugin setting
			dropPluginSetting('currencyexchange');
			
			return true;
		} catch (Throwable $e) {
			$msg = 'ERROR: ' . $e->getMessage();
			notification($msg, 'error');
		}
		
		return false;
	}
}
