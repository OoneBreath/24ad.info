<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SocialAuthSetting
{
	public static function getValues($value, $disk)
	{
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		$baseUrl = config('app.url');
		
		$facebookInfo = trans('admin.facebook_oauth_info', ['baseUrl' => $baseUrl]);
		$linkedinInfo = trans('admin.linkedin_oauth_info', ['baseUrl' => $baseUrl]);
		$twitterOauth2Info = trans('admin.twitter_oauth_2_info', ['baseUrl' => $baseUrl]);
		$twitterOauth1Info = trans('admin.twitter_oauth_1_info', ['baseUrl' => $baseUrl]);
		$googleInfo = trans('admin.google_oauth_info', ['baseUrl' => $baseUrl]);
		
		if (config('plugins.domainmapping.installed')) {
			$facebookInfo .= trans('admin.facebook_oauth_domainmapping');
			$linkedinInfo .= trans('admin.linkedin_oauth_domainmapping');
			$twitterOauth2Info .= trans('admin.twitter_oauth_2_domainmapping');
			$twitterOauth1Info .= trans('admin.twitter_oauth_1_domainmapping');
			$googleInfo .= trans('admin.google_oauth_domainmapping');
		}
		
		$twitterOauth2Info .= trans('admin.twitter_oauth_2_note');
		$twitterOauth1Info .= trans('admin.twitter_oauth_1_note');
		
		$facebookInfo = trans('admin.card_light_inverse', ['content' => $facebookInfo]);
		$linkedinInfo = trans('admin.card_light_inverse', ['content' => $linkedinInfo]);
		$twitterOauth2Info = trans('admin.card_light_inverse', ['content' => $twitterOauth2Info]);
		$twitterOauth1Info = trans('admin.card_light_inverse', ['content' => $twitterOauth1Info]);
		$googleInfo = trans('admin.card_light_inverse', ['content' => $googleInfo]);
		
		$fields = [];
		
		$fields = array_merge($fields, [
			[
				'name'       => 'social_auth_enabled',
				'label'      => trans('admin.social_auth_enabled_label'),
				'type'       => 'checkbox_switch',
				'attributes' => [
					'data-social-network' => 'all',
				],
				'hint'       => trans('admin.social_auth_enabled_hint'),
				'wrapper'    => [
					'class' => 'col-md-12',
				],
			],
		]);
		
		// facebook
		$fields = array_merge($fields, [
			[
				'name'    => 'facebook_title',
				'type'    => 'custom_html',
				'value'   => trans('admin.facebook_title'),
				'wrapper' => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'       => 'facebook_enabled',
				'label'      => trans('admin.facebook_enabled_label'),
				'type'       => 'checkbox_switch',
				'attributes' => [
					'data-social-network' => 'facebook',
				],
				'hint'       => trans('admin.facebook_enabled_hint'),
				'wrapper'    => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'    => 'facebook_oauth_info',
				'type'    => 'custom_html',
				'value'   => $facebookInfo,
				'wrapper' => [
					'class' => 'col-md-12 facebook',
				],
			],
			[
				'name'    => 'facebook_client_id',
				'label'   => trans('admin.facebook_client_id_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 facebook',
				],
			],
			[
				'name'    => 'facebook_client_secret',
				'label'   => trans('admin.facebook_client_secret_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 facebook',
				],
			],
		]);
		
		// linkedin
		$fields = array_merge($fields, [
			[
				'name'    => 'linkedin_title',
				'type'    => 'custom_html',
				'value'   => trans('admin.linkedin_title'),
				'wrapper' => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'       => 'linkedin_enabled',
				'label'      => trans('admin.linkedin_enabled_label'),
				'type'       => 'checkbox_switch',
				'attributes' => [
					'data-social-network' => 'linkedin',
				],
				'hint'       => trans('admin.linkedin_enabled_hint'),
				'wrapper'    => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'    => 'linkedin_oauth_info',
				'type'    => 'custom_html',
				'value'   => $linkedinInfo,
				'wrapper' => [
					'class' => 'col-md-12 linkedin',
				],
			],
			[
				'name'    => 'linkedin_client_id',
				'label'   => trans('admin.linkedin_client_id_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 linkedin',
				],
			],
			[
				'name'    => 'linkedin_client_secret',
				'label'   => trans('admin.linkedin_client_secret_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 linkedin',
				],
			],
		]);
		
		// twitter (OAuth 2.0)
		$fields = array_merge($fields, [
			[
				'name'    => 'twitter_oauth_2_title',
				'type'    => 'custom_html',
				'value'   => trans('admin.twitter_oauth_2_title'),
				'wrapper' => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'       => 'twitter_oauth_2_enabled',
				'label'      => trans('admin.twitter_oauth_2_enabled_label'),
				'type'       => 'checkbox_switch',
				'attributes' => [
					'data-social-network' => 'twitter-oauth-2',
				],
				'hint'       => trans('admin.twitter_oauth_2_enabled_hint'),
				'wrapper'    => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'    => 'twitter_oauth_2_info',
				'type'    => 'custom_html',
				'value'   => $twitterOauth2Info,
				'wrapper' => [
					'class' => 'col-md-12 twitter-oauth-2',
				],
			],
			[
				'name'    => 'twitter_oauth_2_client_id',
				'label'   => trans('admin.twitter_oauth_2_client_id_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 twitter-oauth-2',
				],
			],
			[
				'name'    => 'twitter_oauth_2_client_secret',
				'label'   => trans('admin.twitter_oauth_2_client_secret_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 twitter-oauth-2',
				],
			],
		]);
		
		// twitter (OAuth 1.0)
		$fields = array_merge($fields, [
			[
				'name'    => 'twitter_oauth_1_title',
				'type'    => 'custom_html',
				'value'   => trans('admin.twitter_oauth_1_title'),
				'wrapper' => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'       => 'twitter_oauth_1_enabled',
				'label'      => trans('admin.twitter_oauth_1_enabled_label'),
				'type'       => 'checkbox_switch',
				'attributes' => [
					'data-social-network' => 'twitter-oauth-1',
				],
				'hint'       => trans('admin.twitter_oauth_1_enabled_hint'),
				'wrapper'    => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'    => 'twitter_oauth_1_info',
				'type'    => 'custom_html',
				'value'   => $twitterOauth1Info,
				'wrapper' => [
					'class' => 'col-md-12 twitter-oauth-1',
				],
			],
			[
				'name'    => 'twitter_client_id',
				'label'   => trans('admin.twitter_client_id_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 twitter-oauth-1',
				],
			],
			[
				'name'    => 'twitter_client_secret',
				'label'   => trans('admin.twitter_client_secret_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 twitter-oauth-1',
				],
			],
		]);
		
		// google
		$fields = array_merge($fields, [
			[
				'name'    => 'google_title',
				'type'    => 'custom_html',
				'value'   => trans('admin.google_title'),
				'wrapper' => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'       => 'google_enabled',
				'label'      => trans('admin.google_enabled_label'),
				'type'       => 'checkbox_switch',
				'attributes' => [
					'data-social-network' => 'google',
				],
				'hint'       => trans('admin.google_enabled_hint'),
				'wrapper'    => [
					'class' => 'col-md-12',
				],
			],
			[
				'name'    => 'google_oauth_info',
				'type'    => 'custom_html',
				'value'   => $googleInfo,
				'wrapper' => [
					'class' => 'col-md-12 google',
				],
			],
			[
				'name'    => 'google_client_id',
				'label'   => trans('admin.google_client_id_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 google',
				],
			],
			[
				'name'    => 'google_client_secret',
				'label'   => trans('admin.google_client_secret_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 google',
				],
			],
		]);
		
		return addOptionsGroupJavaScript(__NAMESPACE__, __CLASS__, $fields);
	}
}
