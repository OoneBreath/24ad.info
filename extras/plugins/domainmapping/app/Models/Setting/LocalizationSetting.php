<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class LocalizationSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['show_country_flag'] = '1';
			
		} else {
			
			if (!array_key_exists('show_country_flag', $value)) {
				$value['show_country_flag'] = '1';
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
			// Country & Region
			[
				'name'  => 'localization_country_region',
				'type'  => 'custom_html',
				'value' => trans('admin.localization_country_region'),
			],
			[
				'name'    => 'local_currency_packages_activation',
				'label'   => trans('admin.Allow users to pay the Packages in their country currency'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.package_currency_by_country_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			
			// Front Header UI/UX
			[
				'name'  => 'localization_front_header',
				'type'  => 'custom_html',
				'value' => trans('admin.localization_front_header'),
			],
			[
				'name'    => 'show_country_flag',
				'label'   => trans('admin.show_country_flag_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'disabled'     => trans('admin.show_country_flag_option_0'),
					'in_next_logo' => trans('admin.show_country_flag_option_1'),
					'in_next_lang' => trans('admin.show_country_flag_option_2'),
				],
				'hint'    => trans('admin.show_country_flag_hint', [
					'option_0' => trans('admin.show_country_flag_option_0'),
					'option_1' => trans('admin.show_country_flag_option_1'),
					'option_2' => trans('admin.show_country_flag_option_2'),
				]),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'country_flag_shape',
				'label'   => trans('admin.country_flag_shape_label'),
				'type'    => 'select2_from_array',
				'options' => getCountryFlagShapes(),
				'hint'    => trans('admin.country_flag_shape_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'show_languages_flags',
				'label'   => trans('admin.show_languages_flags_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.show_languages_flags_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		];
		
		return $fields;
	}
}
