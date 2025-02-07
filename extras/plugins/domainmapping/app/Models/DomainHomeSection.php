<?php

namespace extras\plugins\domainmapping\app\Models;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\BaseModel;
use App\Models\Scopes\ActiveScope;
use extras\plugins\domainmapping\app\Observers\DomainHomeSectionObserver;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ObservedBy([DomainHomeSectionObserver::class])]
#[ScopedBy([ActiveScope::class])]
class DomainHomeSection extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'domain_home_sections';
	
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
	protected $fillable = ['country_code', 'method', 'name', 'value', 'view', 'field', 'parent_id', 'lft', 'rgt', 'depth', 'active'];
	
	/**
	 * Available Settings Keys
	 *
	 * @var array<string, array>
	 */
	private static array $defaultEntriesKeys = [
		'laraclassifier' => [
			'getLocations',
			'getPremiumListings',
			'getLatestListings',
			'getCategories',
			'getStats',
			'getTextArea',
			'getBottomAdvertising',
			'getSearchForm',
			'getTopAdvertising',
		],
		'jobclass' => [
			'getLocations',
			'getPremiumListings',
			'getLatestListings',
			'getCategories',
			'getStats',
			'getTextArea',
			'getBottomAdvertising',
			'getCompanies',
			'getSearchForm',
			'getTopAdvertising',
		],
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
	
	public static function getDefaultEntriesKeys($appSlug): array
	{
		return self::$defaultEntriesKeys[$appSlug] ?? [];
	}
	
	public function defaultEntriesExist(): bool
	{
		$appSlug = config('larapen.core.itemSlug');
		$keyField = 'method';
		$countryCode = request()->segment(3);
		
		// Available Settings Keys
		$defaultEntriesKeys = collect(self::getDefaultEntriesKeys($appSlug))
			->map(function ($item, $key) use ($countryCode) {
				return strtolower($countryCode) . '_' . $item;
			})->toArray();
		
		// Check if domain settings exist
		$settings = self::where('country_code', $countryCode)->whereIn($keyField, $defaultEntriesKeys)->get();
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
		$url = admin_url('domains/' . $countryCode . '/homepage/generate');
		
		$msg = trans('domainmapping::messages.Use custom homepage sections for this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-info shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-gear"></i> ';
		$out .= trans('domainmapping::messages.Generate customization entries');
		$out .= '</a>';
		
		return $out;
	}
	
	public function resetDefaultEntriesBtn($xPanel = false): ?string
	{
		if (!$this->defaultEntriesExist()) {
			return null;
		}
		
		$countryCode = request()->segment(3);
		$url = admin_url('domains/' . $countryCode . '/homepage/reset');
		
		$msg = trans('domainmapping::messages.Remove the homepage sections customization for this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-danger shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-xmark"></i> ';
		$out .= trans('domainmapping::messages.Remove the homepage sections customization');
		$out .= '</a>';
		
		return $out;
	}
	
	public function getNameHtml(): string
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$url = $currentUrl . '/' . $this->getKey() . '/edit';
		
		return '<a href="' . $url . '">' . $this->name . '</a>';
	}
	
	public function getActiveHtml(): ?string
	{
		if (!isset($this->active)) return null;
		
		return checkboxDisplay($this->active, $this->{$this->primaryKey});
	}
	
	public function configureButton($xPanel = false): string
	{
		$countryCode = request()->segment(3);
		$url = admin_url('domains/' . $countryCode . '/homepage/' . $this->id . '/edit');
		
		$msg = trans('admin.configure_entity', ['entity' => $this->name]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-primary" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-gear"></i> ';
		$out .= mb_ucfirst(trans('admin.Configure'));
		$out .= '</a>';
		
		return $out;
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
	public function scopeActive($builder)
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
					. mb_ucwords(trans('admin.setup')) . ' &rarr; '
					. mb_ucwords(trans('admin.homepage')) . ' &rarr; ';
				
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
				$sectionMethod = str_replace(strtolower($this->country_code) . '_', '', $this->method);
				$settingClassName = str($sectionMethod)->camel()->ucfirst();
				$settingNamespace = plugin_namespace('domainmapping') . '\app\Models\HomeSection\\';
				$settingClass = $settingNamespace . $settingClassName;
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
			get: function ($value) {
				// Get 'value' field value
				$value = jsonToArray($value);
				
				// Handle 'value' field value
				// Get the right Setting
				$sectionMethod = str_replace(strtolower($this->country_code) . '_', '', $this->method);
				$settingClassName = str($sectionMethod)->camel()->ucfirst();
				$settingNamespace = plugin_namespace('domainmapping') . '\app\Models\HomeSection\\';
				$settingClass = $settingNamespace . $settingClassName;
				if (class_exists($settingClass)) {
					if (method_exists($settingClass, 'getValues')) {
						$value = $settingClass::getValues($value);
					}
				}
				
				return $value;
			},
			set: function ($value) {
				if (is_null($value)) {
					return null;
				}
				
				$value = jsonToArray($value);
				
				// Handle 'value' field value
				// Get the right Setting
				$sectionMethod = str_replace(strtolower($this->country_code) . '_', '', $this->method);
				$settingClassName = str($sectionMethod)->camel()->ucfirst();
				$settingNamespace = plugin_namespace('domainmapping') . '\app\Models\HomeSection\\';
				$settingClass = $settingNamespace . $settingClassName;
				if (class_exists($settingClass)) {
					if (method_exists($settingClass, 'setValues')) {
						$value = $settingClass::setValues($value, $this);
					}
				}
				
				// Make sure that setting array contains only string, numeric or null elements
				$value = settingArrayElements($value);
				
				return (!empty($value)) ? json_encode($value) : null;
			},
		);
	}
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
}
