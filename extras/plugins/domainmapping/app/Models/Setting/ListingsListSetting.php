<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class ListingsListSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['display_mode'] = 'make-grid';
			$value['enable_cities_autocompletion'] = '1';
			$value['cities_extended_searches'] = '1';
			$value['search_distance_max'] = '500';
			$value['search_distance_default'] = '50';
			$value['search_distance_interval'] = '100';
			
		} else {
			
			if (!array_key_exists('display_mode', $value)) {
				$value['display_mode'] = 'make-grid';
			}
			if (!array_key_exists('enable_cities_autocompletion', $value)) {
				$value['enable_cities_autocompletion'] = '1';
			}
			if (!array_key_exists('cities_extended_searches', $value)) {
				$value['cities_extended_searches'] = '1';
			}
			if (!array_key_exists('search_distance_max', $value)) {
				$value['search_distance_max'] = '500';
			}
			if (!array_key_exists('search_distance_default', $value)) {
				$value['search_distance_default'] = '50';
			}
			if (!array_key_exists('search_distance_interval', $value)) {
				$value['search_distance_interval'] = '100';
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
				'name'  => 'separator_1',
				'type'  => 'custom_html',
				'value' => trans('admin.list_html_displaying'),
			],
		];
		
		if (config('larapen.core.item.slug') == 'laraclassifier') {
			$lcFields = [
				[
					'name'    => 'display_mode',
					'label'   => trans('admin.Listing Page Display Mode'),
					'type'    => 'select2_from_array',
					'options' => collect(getDisplayModeList())
						->flip()
						->map(fn ($item) => ucfirst($item))
						->sort()
						->toArray(),
					'wrapper' => [
						'class' => 'col-md-6',
					],
				],
				[
					'name'    => 'grid_view_cols',
					'label'   => trans('admin.Grid View Columns'),
					'type'    => 'select2_from_array',
					'options' => [
						4 => '4',
						3 => '3',
						2 => '2',
					],
					'wrapper' => [
						'class' => 'col-md-6 make-grid',
					],
				],
			];
			
			$fields = array_merge($fields, $lcFields);
		}
		
		$fields = array_merge($fields, [
			[
				'name'    => 'left_sidebar',
				'label'   => trans('admin.Listing Page Left Sidebar'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				
				],
			],
			[
				'name'    => 'show_cats_in_top',
				'label'   => trans('admin.show_cats_in_top_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.show_cats_in_top_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'show_listings_tags',
				'label'   => trans('admin.show_listings_tags_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.show_listings_tags_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'search_title',
				'type'  => 'custom_html',
				'value' => trans('admin.search_title'),
			],
			[
				'name'    => 'enable_cities_autocompletion',
				'label'   => trans('admin.enable_cities_autocompletion_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.enable_cities_autocompletion_hint'),
				'wrapper' => [
					'class' => 'col-md-12',
				],
			],
			
			[
				'name'  => 'extended_searches_title',
				'type'  => 'custom_html',
				'value' => trans('admin.extended_searches_title'),
			],
			[
				'name'    => 'cities_extended_searches',
				'label'   => trans('admin.cities_extended_searches_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.cities_extended_searches_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'search_distance_max',
				'label'   => trans('admin.Max Search Distance'),
				'type'    => 'select2_from_array',
				'options' => [
					1000 => '1000',
					900  => '900',
					800  => '800',
					700  => '700',
					600  => '600',
					500  => '500',
					400  => '400',
					300  => '300',
					200  => '200',
					100  => '100',
					50   => '50',
					0    => '0',
				],
				'hint'    => trans('admin.Max search radius distance'),
				'wrapper' => [
					'class' => 'col-md-6 extended',
				],
				'newline' => true,
			],
			
			[
				'name'    => 'search_distance_default',
				'label'   => trans('admin.Default Search Distance'),
				'type'    => 'select2_from_array',
				'options' => [
					200 => '200',
					100 => '100',
					50  => '50',
					25  => '25',
					20  => '20',
					10  => '10',
					0   => '0',
				],
				'hint'    => trans('admin.Default search radius distance'),
				'wrapper' => [
					'class' => 'col-md-6 extended',
				],
			],
			[
				'name'    => 'search_distance_interval',
				'label'   => trans('admin.Distance Interval'),
				'type'    => 'select2_from_array',
				'options' => [
					250 => '250',
					200 => '200',
					100 => '100',
					50  => '50',
					25  => '25',
					20  => '20',
					10  => '10',
					5   => '5',
				],
				'hint'    => trans('admin.The interval between filter distances'),
				'wrapper' => [
					'class' => 'col-md-6 extended',
				],
			],
		]);
		
		return addOptionsGroupJavaScript(__NAMESPACE__, __CLASS__, $fields);
	}
}
