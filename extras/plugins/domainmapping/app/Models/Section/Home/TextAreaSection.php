<?php

namespace extras\plugins\domainmapping\app\Models\Section\Home;

use App\Models\Language;

class TextAreaSection
{
	public static function getValues($value)
	{
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		$wysiwygEditor = config('settings.other.wysiwyg_editor');
		$wysiwygEditorViewPath = '/views/admin/panel/fields/' . $wysiwygEditor . '.blade.php';
		
		$fields = [
			[
				'name'  => 'dynamic_variables_hint',
				'type'  => 'custom_html',
				'value' => trans('admin.dynamic_variables_hint'),
			],
		];
		
		$languages = Language::active()->get();
		if ($languages->count() > 0) {
			$txtFields = [];
			foreach ($languages as $language) {
				$titleLabel = mb_ucfirst(trans('admin.title')) . ' (' . $language->name . ')';
				$bodyLabel = trans('admin.body_label') . ' (' . $language->name . ')';
				
				$txtFields[] = [
					'name'       => 'title_' . $language->code,
					'label'      => $titleLabel,
					'type'       => 'text',
					'attributes' => [
						'placeholder' => $titleLabel,
					],
					'wrapper'    => [
						'class' => 'col-md-12',
					],
					'tab'        => $language->name,
				];
				$txtFields[] = [
					'name'       => 'body_' . $language->code,
					'label'      => $bodyLabel,
					'type'       => ($wysiwygEditor != 'none' && file_exists(resource_path() . $wysiwygEditorViewPath))
						? $wysiwygEditor
						: 'textarea',
					'attributes' => [
						'placeholder' => $bodyLabel,
						'id'          => 'description',
						'rows'        => 5,
					],
					'hint'       => trans('admin.body_hint') . ' (' . $language->name . ')',
					'wrapper'    => [
						'class' => 'col-md-12',
					],
					'tab'        => $language->name,
				];
				
				$txtFields[] = [
					'name'  => 'seo_start_' . $language->code,
					'type'  => 'custom_html',
					'value' => '<hr style="border: 1px dashed #EFEFEF;" class="my-3">',
				];
			}
			
			$fields = array_merge($fields, $txtFields);
		}
		
		$fields = array_merge($fields, [
			[
				'name'  => 'hide_on_mobile',
				'label' => trans('admin.hide_on_mobile_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.hide_on_mobile_hint'),
			],
			[
				'name'  => 'active',
				'label' => trans('admin.Active'),
				'type'  => 'checkbox_switch',
			],
		]);
		
		return $fields;
	}
}
