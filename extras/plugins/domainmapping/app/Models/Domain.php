<?php

namespace extras\plugins\domainmapping\app\Models;

use App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\Crud;
use App\Models\BaseModel;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\LocalizedScope;
use extras\plugins\domainmapping\app\Observers\DomainObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ObservedBy([DomainObserver::class])]
#[ScopedBy([ActiveScope::class, LocalizedScope::class])]
class Domain extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'domains';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	
	/**
	 * @var array<int, string>
	 */
	protected $appends = ['url'];
	
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
	protected $fillable = ['country_code', 'host', 'https', 'active'];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public function bulkCountriesSubDomainButton($xPanel = false): string
	{
		$url = admin_url('domains/create_bulk_countries_sub_domains');
		
		$msg = trans('domainmapping::messages.Create bulk sub-domains based on countries codes.');
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-success shadow" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-asterisk"></i> ';
		$out .= mb_ucfirst(trans('domainmapping::messages.Create Bulk Sub-Domains'));
		$out .= '</a>';
		
		return $out;
	}
	
	public function getCountryHtml()
	{
		$country = $this->country ?? null;
		$countryCode = $country->code ?? $this->country_code ?? null;
		$countryFlagUrl = $country->flag_url ?? $this->country_flag_url ?? null;
		
		if (!empty($countryFlagUrl)) {
			$out = '<a href="' . dmUrl($countryCode, '/', true, true) . '" target="_blank">';
			$out .= '<img src="' . $countryFlagUrl . '" data-bs-toggle="tooltip" title="' . $countryCode . '">';
			$out .= '</a>';
			
			return $out;
		} else {
			return $countryCode;
		}
	}
	
	public function getDomainHtml(): string
	{
		return '<a href="' . $this->url . '" target="_blank">' . $this->url . '</a>';
	}
	
	public function getHttpsHtml(): string
	{
		if ($this->https == 1) {
			return '<i class="admin-single-icon fa-solid fa-toggle-on" aria-hidden="true"></i>';
		} else {
			return '<i class="admin-single-icon fa-solid fa-toggle-off" aria-hidden="true"></i>';
		}
	}
	
	public function getActiveHtml(): ?string
	{
		if ($this->active == 1) {
			return '<i class="admin-single-icon fa-solid fa-toggle-on" aria-hidden="true"></i>';
		} else {
			return '<i class="admin-single-icon fa-solid fa-toggle-off" aria-hidden="true"></i>';
		}
	}
	
	public function settingsButton($xPanel = false): string
	{
		$url = admin_url('domains/' . $this->country_code . '/settings');
		
		$msg = trans('domainmapping::messages.Settings of host', ['host' => $this->host]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-light" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-gear"></i> ';
		$out .= mb_ucfirst(trans('domainmapping::messages.Settings'));
		$out .= '</a>';
		
		return $out;
	}
	
	public function sectionsButton($xPanel = false): string
	{
		$url = admin_url('domains/' . $this->country_code . '/homepage');
		
		$msg = trans('domainmapping::messages.Homepage of host', ['host' => $this->host]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-light" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-solid fa-house"></i> ';
		$out .= mb_ucfirst(trans('domainmapping::messages.Homepage'));
		$out .= '</a>';
		
		return $out;
	}
	
	public function metaTagsButton($xPanel = false)
	{
		$url = admin_url('domains/' . $this->country_code . '/meta_tags');
		
		$msg = trans('domainmapping::messages.Meta tags of host', ['host' => $this->host]);
		$tooltip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-light" href="' . $url . '"' . $tooltip . '>';
		$out .= '<i class="fa-regular fa-bookmark"></i> ';
		$out .= mb_ucfirst(trans('domainmapping::messages.Meta Tags'));
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
	public function scopeActive(Builder $builder): Builder
	{
		return $builder->where('active', 1);
	}
	
	/*
	|--------------------------------------------------------------------------
	| ACCESSORS | MUTATORS
	|--------------------------------------------------------------------------
	*/
	protected function url(): Attribute
	{
		return Attribute::make(
			get: function ($value) {
				// Remove current protocol
				$value = preg_replace('#http[^:]*://#ui', '', $this->host);
				
				// Get the right protocol
				$protocol = ($this->https == 1) ? 'https' : 'http';
				
				// Use the right protocol instead
				return $protocol . '://' . $value;
			},
		);
	}
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
}
