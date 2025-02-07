<?php

namespace extras\plugins\domainmapping\app\Models;

use App\Helpers\Common\Files\Storage\StorageDisk;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use App\Models\BaseModel;
use App\Models\Scopes\ActiveScope;
use extras\plugins\domainmapping\app\Observers\DomainSectionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ObservedBy([DomainSectionObserver::class])]
#[ScopedBy([ActiveScope::class])]
class DomainSection extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'domain_sections';
	
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
		'belongs_to',
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
	 * Available Sections Keys
	 *
	 * @var array<string, array>
	 */
	private static array $defaultEntriesKeys = [
		'laraclassifier' => [
			'search_form',
			'locations',
			'premium_listings',
			'categories',
			'latest_listings',
			'stats',
			'text_area',
			'top_ad',
			'bottom_ad',
		],
		'jobclass'       => [
			'search_form',
			'premium_listings',
			'latest_listings',
			'categories',
			'locations',
			'companies',
			'stats',
			'text_area',
			'top_ad',
			'bottom_ad',
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
		$appSlug = config('larapen.core.item.slug');
		$keyField = 'key';
		$countryCode = request()->segment(3);
		
		// Available Sections Keys
		$defaultEntriesKeys = collect(self::getDefaultEntriesKeys($appSlug))
			->map(fn ($item) => strtolower($countryCode) . '_' . $item)
			->toArray();
		
		// Check if domain sections exist
		$sections = self::where('country_code', '=', $countryCode)
			->whereIn($keyField, $defaultEntriesKeys)
			->get();
		
		if ($sections->count() > 0) {
			$sectionsKeys = $sections->keyBy($keyField)->keys()->toArray();
			
			// In case the entries are re-ordered,
			// and are no longer in the same order as the expected array's elements
			sort($sectionsKeys);
			sort($defaultEntriesKeys);
			
			if ($sectionsKeys == $defaultEntriesKeys) {
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
		$url = admin_url('domains/' . $countryCode . '/sections/generate');
		
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
		$url = admin_url('domains/' . $countryCode . '/sections/reset');
		
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
		$url = admin_url('domains/' . $countryCode . '/sections/' . $this->id . '/edit');
		
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
		return [];
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
				// Get the right Section
				$sectionClass = $this->getSectionClass();
				if (class_exists($sectionClass)) {
					if (method_exists($sectionClass, 'getFields')) {
						$value = $sectionClass::getFields($diskName);
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
		// Get 'value' field value
		$value = jsonToArray($value);
		
		// Handle 'value' field value
		// Get the right Section
		$sectionClass = $this->getSectionClass();
		if (class_exists($sectionClass)) {
			if (method_exists($sectionClass, 'getValues')) {
				$value = $sectionClass::getValues($value);
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
		
		$value = jsonToArray($value);
		
		// Handle 'value' field value
		// Get the right Section
		$sectionClass = $this->getSectionClass();
		if (class_exists($sectionClass)) {
			if (method_exists($sectionClass, 'setValues')) {
				$value = $sectionClass::setValues($value, $this);
			}
		}
		
		// Make sure that section array contains only string, numeric or null elements
		$value = settingArrayElements($value);
		
		return (!empty($value)) ? json_encode($value) : null;
	}
	
	/**
	 * Get the right Section class
	 *
	 * @return string
	 */
	private function getSectionClass(): string
	{
		$countryCode = $this->country_code ?? '';
		$belongsTo = $this->belongs_to ?? '';
		$classKey = $this->key ?? '';
		
		// Get valid section key
		$countryCode = str($countryCode)->lower()->append('_')->toString();
		$classKey = str($classKey)->replace($countryCode, '')->toString();
		
		// Get class name
		$belongsTo = !empty($belongsTo) ? str($belongsTo)->camel()->ucfirst()->finish('\\')->toString() : '';
		$className = str($classKey)->camel()->ucfirst()->append('Section');
		
		// Get class full qualified name (i.e. with namespace)
		$namespace = plugin_namespace('domainmapping') . '\app\Models\Section\\' . $belongsTo;
		$class = $className->prepend($namespace)->toString();
		
		// If the class doesn't exist in the core app, try to get it from add-ons
		if (!class_exists($class)) {
			$namespace = plugin_namespace($classKey) . '\app\Models\Section\\' . $belongsTo;
			$class = $className->prepend($namespace)->toString();
		}
		
		return $class;
	}
}
