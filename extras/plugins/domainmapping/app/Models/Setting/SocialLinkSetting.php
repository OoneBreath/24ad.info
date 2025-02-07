<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SocialLinkSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['facebook_page_url'] = '#';
			$value['twitter_url'] = '#';
			$value['tiktok_url'] = '#';
			$value['linkedin_url'] = '#';
			$value['pinterest_url'] = '#';
			$value['instagram_url'] = '#';
			
		} else {
			
			if (!array_key_exists('facebook_page_url', $value)) {
				$value['facebook_page_url'] = '';
			}
			if (!array_key_exists('twitter_url', $value)) {
				$value['twitter_url'] = '';
			}
			if (!array_key_exists('tiktok_url', $value)) {
				$value['tiktok_url'] = '';
			}
			if (!array_key_exists('linkedin_url', $value)) {
				$value['linkedin_url'] = '';
			}
			if (!array_key_exists('pinterest_url', $value)) {
				$value['pinterest_url'] = '';
			}
			if (!array_key_exists('instagram_url', $value)) {
				$value['instagram_url'] = '';
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
				'name'  => 'facebook_page_url',
				'label' => trans('admin.facebook_page_url'),
				'type'  => 'text',
			],
			[
				'name'  => 'twitter_url',
				'label' => trans('admin.twitter_url'),
				'type'  => 'text',
			],
			[
				'name'  => 'tiktok_url',
				'label' => trans('admin.tiktok_url'),
				'type'  => 'text',
			],
			[
				'name'  => 'linkedin_url',
				'label' => trans('admin.linkedin_url'),
				'type'  => 'text',
			],
			[
				'name'  => 'pinterest_url',
				'label' => trans('admin.pinterest_url'),
				'type'  => 'text',
			],
			[
				'name'  => 'instagram_url',
				'label' => trans('admin.instagram_url'),
				'type'  => 'text',
			],
		];
		
		return $fields;
	}
}
