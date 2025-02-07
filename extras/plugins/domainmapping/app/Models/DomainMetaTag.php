<?php

namespace extras\plugins\domainmapping\app\Models;

use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\SpatieTranslatable\HasTranslations;
use App\Models\BaseModel;
use App\Models\Scopes\ActiveScope;
use extras\plugins\domainmapping\app\Observers\DomainMetaTagObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;

#[ObservedBy([DomainMetaTagObserver::class])]
#[ScopedBy([ActiveScope::class])]
class DomainMetaTag extends BaseModel
{
	use Crud, HasTranslations;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'domain_meta_tags';
	
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
	protected $fillable = ['country_code', 'page', 'title', 'description', 'keywords', 'active'];
	
	/**
	 * @var array<int, string>
	 */
	public array $translatable = ['title', 'description', 'keywords'];
	
	/**
	 * Default Pages
	 *
	 * @var array<string, string>
	 */
	private static array $defaultPages = [
		'home'           => 'Homepage',
		'search'         => 'Search (Default)',
		'searchCategory' => 'Search (Category)',
		'searchLocation' => 'Search (Location)',
		'searchProfile'  => 'Search (Profile)',
		'searchTag'      => 'Search (Tag)',
		'listingDetails' => 'Listing Details',
		'register'       => 'Register',
		'login'          => 'Login',
		'create'         => 'Listings Creation',
		'countries'      => 'Countries',
		'contact'        => 'Contact',
		'sitemap'        => 'Sitemap',
		'password'       => 'Password',
		'pricing'        => 'Pricing',
		'staticPage'     => 'Page (Static)',
	];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public static function getDefaultPages(): array
	{
		$countryCode = request()->segment(3);
		
		return collect(self::$defaultPages)
			->mapWithKeys(function ($item, $key) use ($countryCode) {
				return [strtolower($countryCode) . '_' . $key => $item];
			})->toArray();
	}
	
	public static function getDefaultEntriesKeys(): array
	{
		return collect(self::getDefaultPages())->keys()->toArray();
	}
	
	public function defaultEntriesExist(): bool
	{
		$keyField = 'page';
		$defaultLangCode = config('appLang.code');
		$countryCode = request()->segment(3);
		
		// Available Settings Keys
		$defaultEntriesKeys = self::getDefaultEntriesKeys();
		
		// Check if domain settings exist
		$settings = self::where('country_code', '=', $countryCode)
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
		$url = admin_url('domains/' . $countryCode . '/meta_tags/generate');
		
		$msg = trans('domainmapping::messages.Generate meta tags entries to customize them for this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-info shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-gear"></i> ';
		$out .= trans('domainmapping::messages.Generate meta tags entries');
		$out .= '</a>';
		
		return $out;
	}
	
	public function resetDefaultEntriesBtn($xPanel = false): ?string
	{
		if (!$this->defaultEntriesExist()) {
			return null;
		}
		
		$countryCode = request()->segment(3);
		$url = admin_url('domains/' . $countryCode . '/meta_tags/reset');
		
		$msg = trans('domainmapping::messages.Remove the customized meta tags for this domain');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		// Button
		$out = '<a class="btn btn-danger shadow confirm-simple-action" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-xmark"></i> ';
		$out .= trans('domainmapping::messages.Remove the meta tags');
		$out .= '</a>';
		
		return $out;
	}
	
	public function getPageHtml()
	{
		$countryCode = request()->segment(3);
		
		$entries = self::getDefaultPages();
		
		// Get Page Name
		$out = $this->page;
		if (isset($entries[$this->page])) {
			$url = admin_url('domains/' . $countryCode . '/meta_tags/' . $this->id . '/edit');
			$out = '<a href="' . $url . '">' . $entries[$this->page] . '</a>';
		}
		
		return $out;
	}
	
	public function getActiveHtml(): ?string
	{
		if (!isset($this->active)) return null;
		
		return checkboxDisplay($this->active, $this->{$this->primaryKey});
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
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
}
