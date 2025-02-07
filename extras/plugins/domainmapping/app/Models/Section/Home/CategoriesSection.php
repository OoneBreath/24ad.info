<?php

namespace extras\plugins\domainmapping\app\Models\Section\Home;

class CategoriesSection
{
	public static function getValues($value)
	{
		if (empty($value)) {
			
			$value['cat_display_type'] = 'c_bigIcon_list';
			$value['show_icon'] = '1';
			$value['max_sub_cats'] = '3';
			
		} else {
			
			if (!isset($value['cat_display_type'])) {
				$value['cat_display_type'] = 'c_bigIcon_list';
			} else {
				if (in_array($value['cat_display_type'], ['c_circle_list', 'c_check_list'])) {
					$value['cat_display_type'] = 'c_bigIcon_list';
				}
			}
			if (!isset($value['show_icon'])) {
				$value['show_icon'] = '1';
			}
			if (!isset($value['max_sub_cats'])) {
				$value['max_sub_cats'] = '3';
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
				'name'        => 'cat_display_type',
				'label'       => trans('admin.cat_display_type_label'),
				'type'        => 'select2_from_array',
				'options'     => [
					'c_normal_list'    => trans('admin.cat_display_type_op_1'),
					'c_border_list'    => trans('admin.cat_display_type_op_2'),
					'c_bigIcon_list'   => trans('admin.cat_display_type_op_3'),
					'c_picture_list'   => trans('admin.cat_display_type_op_4'),
					'cc_normal_list'   => trans('admin.cat_display_type_op_5'),
					'cc_normal_list_s' => trans('admin.cat_display_type_op_6'),
				],
				'allows_null' => false,
				'hint'        => trans('admin.cat_display_type_hint'),
				'wrapper'     => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'max_items',
				'label'   => trans('admin.max_categories_label'),
				'type'    => 'number',
				'hint'    => trans('admin.max_categories_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'count_categories_listings',
				'label'   => trans('admin.count_categories_listings_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
			
			[
				'name'    => 'max_sub_cats',
				'label'   => trans('admin.Max subcategories displayed by default'),
				'type'    => 'number',
				'hint'    => trans('admin.max_sub_cats_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
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
