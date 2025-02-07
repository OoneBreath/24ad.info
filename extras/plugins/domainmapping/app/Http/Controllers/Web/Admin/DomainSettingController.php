<?php

namespace extras\plugins\domainmapping\app\Http\Controllers\Web\Admin;

/*
------------------------------------------------------------------------------------
The "field" field value for "settings" table
------------------------------------------------------------------------------------
text            => {"name":"value","label":"Value","type":"text"}
textarea        => {"name":"value","label":"Value","type":"textarea"}
checkbox        => {"name":"value","label":"Activation","type":"checkbox"}
upload (image)  => {"name":"value","label":"Value","type":"image","upload":"true","disk":"uploads","default":"images/logo@2x.png"}
selectbox       => {"name":"value","label":"Value","type":"select_from_array","options":OPTIONS}
                => {"default":"Default","blue":"Blue","yellow":"Yellow","green":"Green","red":"Red"}
                => {"smtp":"SMTP","mailgun":"Mailgun","ses":"Amazon SES","mail":"PHP Mail","sendmail":"Sendmail"}
                => {"sandbox":"sandbox","live":"live"}
------------------------------------------------------------------------------------
*/

use App\Helpers\Common\DBTool;
use App\Http\Controllers\Web\Admin\Panel\PanelController;
use App\Http\Controllers\Web\Admin\Traits\SettingsTrait;
use App\Http\Requests\Admin\SettingRequest as StoreRequest;
use App\Http\Requests\Admin\SettingRequest as UpdateRequest;
use App\Models\Setting;
use extras\plugins\domainmapping\app\Models\Domain;
use extras\plugins\domainmapping\app\Models\DomainSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class DomainSettingController extends PanelController
{
	use SettingsTrait;
	
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
		$this->xPanel->setModel(DomainSetting::class);
		$this->xPanel->setRoute(admin_uri('domains/' . $this->countryCode . '/settings'));
		$this->xPanel->setEntityNameStrings(
			mb_strtolower(trans('domainmapping::messages.Setting')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>',
			mb_strtolower(trans('domainmapping::messages.Settings')) . ' &rarr; ' . '<strong>' . $domain->host . '</strong>'
		);
		
		$this->xPanel->enableParentEntity();
		$this->xPanel->setParentKeyField('country_code');
		$this->xPanel->addClause('where', 'country_code', '=', $this->countryCode);
		$this->xPanel->addClause('where', 'active', 1);
		$this->xPanel->setParentRoute(admin_uri('domains'));
		$this->xPanel->setParentEntityNameStrings(
			mb_strtolower(trans('domainmapping::messages.Domain')),
			mb_strtolower(trans('domainmapping::messages.Domains'))
		);
		$this->xPanel->allowAccess(['parent', 'reorder']);
		$this->xPanel->denyAccess(['create', 'delete']);
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->setDefaultPageLength(100);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft');
			$this->xPanel->orderBy('id');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'generate_default_entries', 'generateDefaultEntriesBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('top', 'reset_default_entries', 'resetDefaultEntriesBtn', 'end');
		$this->xPanel->removeButton('update');
		$this->xPanel->addButtonFromModelFunction('line', 'configure', 'configureButton', 'beginning');
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'          => 'name',
			'label'         => 'Setting',
			'type'          => 'model_function',
			'function_name' => 'getNameHtml',
		]);
		
		$this->xPanel->addColumn([
			'name'  => 'description',
			'label' => '',
		]);
		
		// FIELDS
		// ...
	}
	
	public function store(StoreRequest $request): RedirectResponse
	{
		return parent::storeCrud($request);
	}
	
	public function update(UpdateRequest $request): RedirectResponse
	{
		$setting = DomainSetting::find($request->segment(5));
		if (!empty($setting)) {
			// Get the right Setting class
			$countryCode = $setting->country_code ?? '';
			$classKey = $setting->key ?? '';
			
			// Get valid setting key
			$countryCode = str($countryCode)->lower()->append('_')->toString();
			$classKey = str($classKey)->replaceFirst($countryCode, '')->toString();
			
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
			
			if (class_exists($class)) {
				if (method_exists($class, 'passedValidation')) {
					$request = $class::passedValidation($request);
				}
			}
		}
		
		return $this->updateTrait($request);
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
			$keyField = 'key';
			$defaultEntriesKeys = DomainSetting::getDefaultEntriesKeys();
			
			// Remove all current settings
			$entries = DomainSetting::where('country_code', '=', $countryCode)->get();
			$entries->each(fn ($item, $key) => $item->delete());
			
			// Reset permissions table ID auto-increment
			$tableName = DomainSetting::query()->getModel()->getTable();
			DB::statement('ALTER TABLE ' . DBTool::table($tableName) . ' AUTO_INCREMENT = 1;');
			
			// Get the main settings
			$mainEntries = Setting::where('active', 1)->get();
			
			// Copy the main settings
			$entriesKeys = [];
			$mainEntries->each(function ($item) use ($keyField, &$entriesKeys, $countryCode, $defaultEntriesKeys) {
				if (in_array($item->$keyField, $defaultEntriesKeys)) {
					// Clear the settings elements
					$item = collect($item)
						->put($keyField, strtolower($countryCode) . '_' . $item->$keyField)
						->put('value', null)
						->put('field', null)
						->put('country_code', $countryCode)
						->reject(fn ($v, $k) => ($k == 'id'))
						->toArray();
					
					// Generate settings for the domain
					$entries = DomainSetting::create($item);
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
				$message = trans("domainmapping::messages.The settings were been generated successfully for this domain");
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
			$entries = DomainSetting::where('country_code', '=', $countryCode)->get();
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
