<?php

namespace extras\plugins\domainmapping\app\Http\Controllers\Web\Admin;

use App\Helpers\Common\DBTool;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Requests\Admin\MetaTagRequest as StoreRequest;
use App\Http\Requests\Admin\MetaTagRequest as UpdateRequest;
use App\Models\MetaTag;
use extras\plugins\domainmapping\app\Models\Domain;
use extras\plugins\domainmapping\app\Models\DomainMetaTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class DomainMetaTagController extends PanelController
{
	public ?string $countryCode = null;
	
	public function setup()
	{
		// Get the Country Code
		$this->countryCode = getAsStringOrNull(request()->segment(3));
		
		// Get the Country's name
		$domain = Domain::where('country_code', '=', $this->countryCode)->firstOrFail();
		
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel(DomainMetaTag::class);
		$this->xPanel->setRoute(admin_uri('domains/' . $this->countryCode . '/meta_tags'));
		$this->xPanel->setEntityNameStrings(
			mb_strtolower(trans('domainmapping::messages.Meta Tag')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>',
			mb_strtolower(trans('domainmapping::messages.Meta Tags')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>'
		);
		
		$this->xPanel->enableParentEntity();
		$this->xPanel->addClause('where', 'country_code', '=', $this->countryCode);
		$this->xPanel->setParentRoute(admin_uri('domains'));
		$this->xPanel->setParentEntityNameStrings(
			mb_strtolower(trans('domainmapping::messages.Domain')),
			mb_strtolower(trans('domainmapping::messages.Domains'))
		);
		$this->xPanel->allowAccess(['parent']);
		
		$this->xPanel->addButtonFromModelFunction('top', 'generate_default_entries', 'generateDefaultEntriesBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'reset_default_entries', 'resetDefaultEntriesBtn', 'end');
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'          => 'page',
			'label'         => trans('admin.Page'),
			'type'          => 'model_function',
			'function_name' => 'getPageHtml',
		]);
		$this->xPanel->addColumn([
			'name'  => 'title',
			'label' => mb_ucfirst(trans('admin.title')),
		]);
		$this->xPanel->addColumn([
			'name'  => 'description',
			'label' => trans('admin.Description'),
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans('admin.Active'),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
			'on_display'    => 'checkbox',
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'  => 'country_code',
			'type'  => 'hidden',
			'value' => $this->countryCode,
		], 'create');
		
		$this->xPanel->addField([
			'name'        => 'page',
			'label'       => trans('admin.Page'),
			'type'        => 'select2_from_array',
			'options'     => MetaTag::getDefaultPages(),
			'allows_null' => false,
		], 'create');
		$this->xPanel->addField([
			'name'        => 'page',
			'label'       => trans('admin.Page'),
			'type'        => 'select2_from_array',
			'options'     => MetaTag::getDefaultPages(),
			'allows_null' => false,
			'attributes'  => [
				'disabled' => true,
			],
		], 'update');
		
		$this->xPanel->addField([
			'name'  => 'dynamic_variables_full_hint',
			'type'  => 'custom_html',
			'value' => trans('admin.dynamic_variables_full_hint'),
		]);
		
		$this->xPanel->addField([
			'name'       => 'title',
			'label'      => mb_ucfirst(trans('admin.title')),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => mb_ucfirst(trans('admin.title')),
			],
			'hint'       => trans('admin.seo_title_hint'),
		]);
		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin.Description'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin.Description'),
			],
			'hint'       => trans('admin.seo_description_hint'),
		]);
		$this->xPanel->addField([
			'name'       => 'keywords',
			'label'      => trans('admin.Keywords'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin.Keywords'),
			],
			'hint'       => trans('admin.comma_separated_hint') . ' ' . trans('admin.seo_keywords_hint'),
		]);
		$this->xPanel->addField([
			'name'  => 'active',
			'label' => trans('admin.Active'),
			'type'  => 'checkbox_switch',
		]);
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
	
	/**
	 * Generate Domain Settings
	 *
	 * @param $countryCode
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function generate($countryCode): RedirectResponse
	{
		try {
			$keyField = 'page';
			$defaultLangCode = config('appLang.code');
			$defaultEntriesKeys = collect(DomainMetaTag::getDefaultEntriesKeys())
				->map(fn ($item) => str_replace(strtolower($countryCode) . '_', '', $item))
				->toArray();
			
			// Remove all current settings
			$entries = DomainMetaTag::where('country_code', '=', $countryCode)->get();
			$entries->each(fn ($item) => $item->delete());
			
			// Reset permissions table ID auto-increment
			$tableName = DomainMetaTag::query()->getModel()->getTable();
			DB::statement('ALTER TABLE ' . DBTool::table($tableName) . ' AUTO_INCREMENT = 1;');
			
			// Get the main settings
			$mainEntries = MetaTag::query()->get();
			
			// Copy the main settings
			$entriesKeys = [];
			$mainEntries->each(function ($item) use ($keyField, &$entriesKeys, $countryCode, $defaultEntriesKeys) {
				if (in_array($item->$keyField, $defaultEntriesKeys)) {
					// Clear the settings elements
					$item = collect($item)
						->put($keyField, strtolower($countryCode) . '_' . $item->$keyField)
						->put('title', null)
						->put('description', null)
						->put('keywords', null)
						->put('country_code', $countryCode)
						->reject(fn ($v, $k) => ($k == 'id'))
						->toArray();
					
					// Generate settings for the domain
					$entries = DomainMetaTag::create($item);
					if (!empty($entries)) {
						$entryKey = str_replace(strtolower($countryCode) . '_', '', $entries->$keyField);
						$entriesKeys[] = $entryKey;
					}
				}
			});
			
			// In case the entries are re-ordered,
			// and are no longer in the same order as the expected array's elements
			sort($entriesKeys);
			sort($defaultEntriesKeys);
			
			if ($entriesKeys == $defaultEntriesKeys) {
				$message = trans("domainmapping::messages.The meta tags entries were been generated successfully for this domain");
				notification($message, 'success');
			} else {
				$message = trans("domainmapping::messages.no_action_performed");
				notification($message, 'warning');
			}
		} catch (Throwable $e) {
			notification($e->getMessage(), 'error');
		}
		
		return redirect()->back();
	}
	
	/**
	 * Reset Domain Settings
	 *
	 * @param $countryCode
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reset($countryCode): RedirectResponse
	{
		try {
			$entries = DomainMetaTag::where('country_code', '=', $countryCode)->get();
			if ($entries->count() > 0) {
				foreach ($entries as $entry) {
					$entry->delete();
				}
				
				cache()->flush();
				
				$message = trans("domainmapping::messages.The settings were been reset successfully for this domain");
				notification($message, 'success');
			} else {
				$message = trans("domainmapping::messages.no_action_performed");
				notification($message, 'warning');
			}
		} catch (Throwable $e) {
			notification($e->getMessage(), 'error');
		}
		
		return redirect()->back();
	}
}
