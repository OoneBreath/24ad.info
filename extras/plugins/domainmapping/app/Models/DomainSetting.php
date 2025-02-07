<?php

namespace extras\plugins\domainmapping\app\Models;

use App\Helpers\Common\Files\Storage\StorageDisk;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use App\Models\BaseModel;
use extras\plugins\domainmapping\app\Observers\DomainSettingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ObservedBy([DomainSettingObserver::class])]
class DomainSetting extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'domain_settings';
	
	/**
	 * @var array<int, string>
	 */
	protected $fakeColumns = ['value'];
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;
	
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $guarded = ['id'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'id',
		'country_code',
		'key',
		'name',
		'field',
		'value',
		'description',
		'parent_id',
		'lft',
		'rgt',
		'depth',
		'active',
	];
	
	/**
	 * Available Settings Keys
	 *
	 * @var array<int, string>
	 */
	private static array $defaultEntriesKeys = [
		'app',
		'style',
		'listing_form',
		'listings_list',
		'mail',
		'sms',
		'seo',
		'pagination',
		'localization',
		'security',
		'social_auth',
		'social_link',
		'other',
		'footer',
	];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'value' => 'array',
		];
	}
	
	public static function getDefaultEntriesKeys(): array
	{
		return self::$defaultEntriesKeys;
	}
	
	public function defaultEntriesExist(): bool
	{
		$keyField = 'key';
		$countryCode = request()->segment(3);
		
		// Available Settings Keys
		$defaultEntriesKeys = collect(self::getDefaultEntriesKeys())
			->map(fn ($item) => strtolower($countryCode) . '_' . $item)
			->toArray();
		
		// Check if domain settings exist
		$settings = self::where('country_code', '=', $countryCode)
			->where('active', 1)
			->whereIn($keyField, $defaultEntriesKeys)
			->get();
		
		if ($settings->count() > 0) {
			$settingsKeys = $settings->keyBy($keyField)->keys()->toArray();
			
			// In case the entries are re-ordered,
			// and are no longer in the same order as the expected array's elements
			sort($settingsKeys);
			sort($defaultEntriesKeys);
			
			if ($settingsKeys == $defaultEntriesKeys) {
				return true;
			}
		}
		
		return false;
	}
	
	public function generateDefaultEntriesBtn($xPanel = false): ?string
	{
		if ($this->defaultEntriesExist()) {
			return null;
		}
		
		$countryCode = request()->segment(3);
		$url = admin_url('domains/' . $countryCode . '/settings/generate');
		
		$msg = trans('domainmapping::messages.Generate settings entries to customize this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-info shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-gear"></i> ';
		$out .= trans('domainmapping::messages.Generate settings entries');
		$out .= '</a>';
		
		return $out;
	}
	
	public function resetDefaultEntriesBtn($xPanel = false): ?string
	{
		if (!$this->defaultEntriesExist()) {
			return null;
		}
		
		$countryCode = request()->segment(3);
		$url = admin_url('domains/' . $countryCode . '/settings/reset');
		
		$msg = trans('domainmapping::messages.Remove the settings & customizations for this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-danger shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-xmark"></i> ';
		$out .= trans('domainmapping::messages.Remove the settings');
		$out .= '</a>';
		
		return $out;
	}
	
	public function getNameHtml(): string
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$url = $currentUrl . '/' . $this->getKey() . '/edit';
		
		return '<a href="' . $url . '">' . $this->name . '</a>';
	}
	
	public function configureButton($xPanel = false): string
	{
		$url = admin_url('domains/' . $this->country_code . '/settings/' . $this->id . '/edit');
		
		$msg = trans('admin.configure_entity', ['entity' => $this->name]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-primary" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-gear"></i> ';
		$out .= mb_ucfirst(trans('admin.Configure'));
		$out .= '</a>';
		
		return $out;
	}
	
	public static function optionsThatNeedToBeHidden(): array
	{
		return [
			'purchase_code',
			'email',
			'phone_number',
			'smtp_username',
			'smtp_password',
			'mailgun_secret',
			'mailgun_username',
			'mailgun_password',
			'postmark_token',
			'postmark_username',
			'postmark_password',
			'ses_key',
			'ses_secret',
			'ses_username',
			'ses_password',
			'mandrill_secret',
			'mandrill_username',
			'mandrill_password',
			'sparkpost_secret',
			'sparkpost_username',
			'sparkpost_password',
			'sendmail_username',
			'sendmail_password',
			'mailersend_api_key',
			'vonage_key',
			'vonage_secret',
			'vonage_application_id',
			'vonage_from',
			'twilio_username',
			'twilio_password',
			'twilio_account_sid',
			'twilio_auth_token',
			'twilio_from',
			'twilio_alpha_sender',
			'twilio_sms_service_sid',
			'twilio_debug_to',
			'ipinfo_token',
			'dbip_api_key',
			'ipbase_api_key',
			'ip2location_api_key',
			'ipgeolocation_api_key',
			'iplocation_api_key',
			'ipstack_api_key', // old
			'ipstack_access_key',
			'maxmind_api_account_id',
			'maxmind_api_license_key',
			'maxmind_database_license_key',
			'recaptcha_v2_site_key',
			'recaptcha_v2_secret_key',
			'recaptcha_v3_site_key',
			'recaptcha_v3_secret_key',
			'recaptcha_site_key',
			'recaptcha_secret_key',
			'stripe_secret',
			'paypal_username',
			'paypal_password',
			'paypal_signature',
			'facebook_client_id',
			'facebook_client_secret',
			'linkedin_client_id',
			'linkedin_client_secret',
			'twitter_client_id',
			'twitter_client_secret',
			'google_client_id',
			'google_client_secret',
			'google_maps_key',
			'facebook_app_id',
			'fixer_access_key',
			'currency_layer_access_key',
			'open_exchange_rates_app_id',
			'currency_data_feed_api_key',
			'forge_api_key',
			'xignite_token',
		];
	}
	
	/*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
	
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeActive(Builder $builder): Builder
	{
		return $builder->where('active', 1);
	}
	
	/*
	|--------------------------------------------------------------------------
	| ACCESSORS | MUTATORS
	|--------------------------------------------------------------------------
	*/
	protected function field(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				$diskName = StorageDisk::getDiskName();
				
				// Get 'field' field value
				$value = jsonToArray($value);
				
				$breadcrumb = trans('admin.Admin panel') . ' &rarr; '
					. mb_ucwords(trans('domainmapping::messages.Domains')) . ' &rarr; '
					. mb_ucwords(trans('domainmapping::messages.Settings')) . ' &rarr; ';
				
				$formTitle = [
					[
						'name'  => 'group_name',
						'type'  => 'custom_html',
						'value' => '<h2 class="setting-group-name">' . $this->name . '</h2>',
					],
					[
						'name'  => 'group_breadcrumb',
						'type'  => 'custom_html',
						'value' => '<p class="setting-group-breadcrumb">' . $breadcrumb . $this->name . '</p>',
					],
				];
				
				// Handle 'field' field value
				// Get the right Setting
				$settingClass = $this->getSettingClass();
				if (class_exists($settingClass)) {
					if (method_exists($settingClass, 'getFields')) {
						$value = $settingClass::getFields($diskName);
					}
				}
				
				return array_merge($formTitle, $value);
			},
		);
	}
	
	protected function value(): Attribute
	{
		return Attribute::make(
			get: fn ($value) => $this->getValue($value),
			set: fn ($value) => $this->setValue($value),
		);
	}
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
	private function getValue($value)
	{
		$disk = StorageDisk::getDisk();
		
		// Get 'value' field value
		$value = jsonToArray($value);
		
		// Handle 'value' field value
		// Get the right Setting
		$settingClass = $this->getSettingClass();
		if (class_exists($settingClass)) {
			if (method_exists($settingClass, 'getValues')) {
				$value = $settingClass::getValues($value, $disk);
			}
		}
		
		// Demo: Secure some Data (Applied for all Entries)
		if (isAdminPanel() && isDemoDomain()) {
			$isPostOrPutMethod = (in_array(strtolower(request()->method()), ['post', 'put']));
			$isNotFromAuthForm = (!in_array(request()->segment(2), ['password', 'login']));
			$value = collect($value)
				->mapWithKeys(function ($v, $k) use ($isPostOrPutMethod, $isNotFromAuthForm) {
					$isOptionNeedToBeHidden = (
						!$isPostOrPutMethod
						&& $isNotFromAuthForm
						&& in_array($k, self::optionsThatNeedToBeHidden())
					);
					if ($isOptionNeedToBeHidden) {
						$v = '************************';
					}
					
					return [$k => $v];
				})->toArray();
		}
		
		return $value;
	}
	
	private function setValue($value)
	{
		if (is_null($value)) return null;
		
		// Get value
		$value = jsonToArray($value);
		
		// Handle 'value' field value
		// Get the right Setting
		$settingClass = $this->getSettingClass();
		if (class_exists($settingClass)) {
			if (method_exists($settingClass, 'setValues')) {
				$value = $settingClass::setValues($value, $this);
			}
		}
		
		// Make sure that setting array contains only string, numeric or null elements
		$value = settingArrayElements($value);
		
		return (!empty($value)) ? json_encode($value) : null;
	}
	
	/**
	 * Get the right Setting class
	 *
	 * @return string
	 */
	private function getSettingClass(): string
	{
		$countryCode = $this->country_code ?? '';
		$classKey = $this->key ?? '';
		
		// Get valid setting key
		$countryCode = str($countryCode)->lower()->append('_')->toString();
		$classKey = str($classKey)->replace($countryCode, '')->toString();
		
		// Get class name
		$className = str($classKey)->camel()->ucfirst()->append('Setting');
		
		// Get class full qualified name (i.e. with namespace)
		$namespace = plugin_namespace('domainmapping') . '\app\Models\Setting\\';
		$class = $className->prepend($namespace)->toString();
		
		// If the class doesn't exist in the core app, try to get it from add-ons
		if (!class_exists($class)) {
			$namespace = plugin_namespace($classKey) . '\app\Models\Setting\\';
			$class = $className->prepend($namespace)->toString();
		}
		
		return $class;
	}
}
