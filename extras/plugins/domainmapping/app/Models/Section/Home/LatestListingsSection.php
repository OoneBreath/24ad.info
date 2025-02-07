<?php

namespace extras\plugins\domainmapping\app\Models\Section\Home;

class LatestListingsSection
{
	public static function getValues($value)
	{
		if (empty($value)) {
			
			$value['max_items'] = '8';
			$value['show_view_more_btn'] = '1';
			
		} else {
			
			if (!isset($value['max_items'])) {
				$value['max_items'] = '8';
			}
			if (!isset($value['show_view_more_btn'])) {
				$value['show_view_more_btn'] = '1';
			}
			
		}
		
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
				'name'    => 'max_items',
				'label'   => trans('admin.Max Items'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'        => 'order_by',
				'label'       => trans('admin.Order By'),
				'type'        => 'select2_from_array',
				'options'     => [
					'date'   => 'Date',
					'random' => 'Random',
				],
				'allows_null' => false,
				'wrapper'     => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'show_view_more_btn',
				'label' => trans('admin.Show View More Button'),
				'type'  => 'checkbox_switch',
			],
			[
				'name'       => 'cache_expiration',
				'label'      => trans('admin.Cache Expiration Time for this section'),
				'type'       => 'number',
				'attributes' => [
					'placeholder' => '0',
				],
				'hint'       => trans('admin.section_cache_expiration_hint'),
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_last',
				'type'  => 'custom_html',
				'value' => '<hr>',
			],
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
		];
		
		return $fields;
	}
}
