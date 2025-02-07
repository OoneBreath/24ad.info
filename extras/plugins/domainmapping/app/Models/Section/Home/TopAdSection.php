<?php

namespace extras\plugins\domainmapping\app\Models\Section\Home;

class TopAdSection
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
		$fields = [
			[
				'name'  => 'active',
				'label' => trans('admin.Active'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.top_ad_active_hint'),
			],
		];
		
		return $fields;
	}
}
