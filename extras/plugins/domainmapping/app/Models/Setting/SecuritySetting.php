<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SecuritySetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['login_open_in_modal'] = '1';
			
		} else {
			
			if (!array_key_exists('login_open_in_modal', $value)) {
				$value['login_open_in_modal'] = '1';
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
				'name'  => 'login_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.login_sep_value'),
			],
			[
				'name'  => 'login_open_in_modal',
				'label' => trans('admin.Open In Modal'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.Open the top login link into Modal'),
			],
			
			[
				'name'  => 'captcha_title',
				'type'  => 'custom_html',
				'value' => trans('admin.captcha_title'),
			],
			[
				'name'    => 'captcha',
				'label'   => trans('admin.captcha_label'),
				'type'    => 'select2_from_array',
				'options' => [
					''          => 'Disabled',
					'default'   => 'Simple Captcha (Default)',
					'math'      => 'Simple Captcha (Math)',
					'flat'      => 'Simple Captcha (Flat)',
					'mini'      => 'Simple Captcha (Mini)',
					'inverse'   => 'Simple Captcha (Inverse)',
					'custom'    => 'Simple Captcha (Custom)',
					'recaptcha' => 'Google reCAPTCHA',
				],
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'hint'    => trans('admin.captcha_hint'),
			],
			[
				'name'    => 'captcha_delay',
				'label'   => trans('admin.captcha_delay_label'),
				'type'    => 'select2_from_array',
				'options' => [
					1000 => '1000ms',
					1100 => '1100ms',
					1200 => '1200ms',
					1300 => '1300ms',
					1400 => '1400ms',
					1500 => '1500ms',
					1600 => '1600ms',
					1700 => '1700ms',
					1800 => '1800ms',
					1900 => '1900ms',
					2000 => '2000ms',
					2500 => '2500ms',
					3000 => '3000ms',
				],
				'wrapper' => [
					'class' => 'col-md-6 s-captcha',
				],
				'hint'    => trans('admin.captcha_delay_hint'),
			],
			[
				'name'    => 'captcha_custom',
				'type'    => 'custom_html',
				'value'   => trans('admin.captcha_custom'),
				'wrapper' => [
					'class' => 'col-md-12 s-captcha s-captcha-custom',
				],
			],
			[
				'name'    => 'captcha_custom_info',
				'type'    => 'custom_html',
				'value'   => trans('admin.captcha_custom_info'),
				'wrapper' => [
					'class' => 'col-md-12 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_width',
				'label'      => trans('admin.captcha_width_label', ['max' => 300]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 100,
					'max'  => 300,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_height',
				'label'      => trans('admin.captcha_height_label', ['max' => 150]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 30,
					'max'  => 150,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_length',
				'label'      => trans('admin.captcha_length_label', ['max' => 8]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 3,
					'max'  => 8,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_quality',
				'label'      => trans('admin.captcha_quality_label', ['max' => 100]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'    => 'captcha_bgImage',
				'label'   => trans('admin.captcha_bgImage_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-12 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_bgImage_hint'),
			],
			[
				'name'       => 'captcha_bgColor',
				'label'      => trans('admin.captcha_bgColor_label'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '',
				],
				'wrapper'    => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_lines',
				'label'      => trans('admin.captcha_lines_label', ['max' => 20]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 20,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_angle',
				'label'      => trans('admin.captcha_angle_label', ['max' => 180]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 180,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_sharpen',
				'label'      => trans('admin.captcha_sharpen_label', ['max' => 20]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 20,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_blur',
				'label'      => trans('admin.captcha_blur_label', ['max' => 20]),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'max'  => 20,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_contrast',
				'label'      => trans('admin.captcha_contrast_label', ['max' => 50]),
				'type'       => 'number',
				'attributes' => [
					'min'  => -50,
					'max'  => 50,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'       => 'captcha_expire',
				'label'      => trans('admin.captcha_expire_label'),
				'type'       => 'number',
				'attributes' => [
					'min'  => 0,
					'step' => 1,
				],
				'wrapper'    => [
					'class' => 'col-md-3 s-captcha s-captcha-custom',
				],
			],
			[
				'name'    => 'captcha_math',
				'label'   => trans('admin.captcha_math_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_math_hint'),
			],
			[
				'name'    => 'captcha_encrypt',
				'label'   => trans('admin.captcha_encrypt_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_encrypt_hint'),
			],
			[
				'name'    => 'captcha_sensitive',
				'label'   => trans('admin.captcha_sensitive_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_sensitive_hint'),
			],
			[
				'name'    => 'captcha_invert',
				'label'   => trans('admin.captcha_invert_label'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6 s-captcha s-captcha-custom',
				],
				'hint'    => trans('admin.captcha_invert_hint'),
			],
			
			// ==========
			
			[
				'name'    => 'recaptcha_sep_info',
				'type'    => 'custom_html',
				'value'   => trans('admin.recaptcha_sep_info_value'),
				'wrapper' => [
					'class' => 'col-md-12 recaptcha',
				],
			],
			[
				'name'    => 'recaptcha_version',
				'label'   => trans('admin.recaptcha_version_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'v2' => 'v2 (Checkbox)',
					'v3' => 'v3',
				],
				'hint'    => trans('admin.recaptcha_version_hint'),
				'wrapper' => [
					'class' => 'col-md-6 recaptcha',
				],
				'newline' => true,
			],
			
			[
				'name'    => 'recaptcha_v2_site_key',
				'label'   => trans('admin.recaptcha_v2_site_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v2',
				],
			],
			[
				'name'    => 'recaptcha_v2_secret_key',
				'label'   => trans('admin.recaptcha_v2_secret_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v2',
				],
			],
			[
				'name'    => 'recaptcha_v3_site_key',
				'label'   => trans('admin.recaptcha_v3_site_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v3',
				],
			],
			[
				'name'    => 'recaptcha_v3_secret_key',
				'label'   => trans('admin.recaptcha_v3_secret_key_label'),
				'type'    => 'text',
				'wrapper' => [
					'class' => 'col-md-6 recaptcha recaptcha-v3',
				],
			],
			[
				'name'    => 'recaptcha_skip_ips',
				'label'   => trans('admin.recaptcha_skip_ips_label'),
				'type'    => 'textarea',
				'hint'    => trans('admin.recaptcha_skip_ips_hint'),
				'wrapper' => [
					'class' => 'col-md-6 recaptcha',
				],
			],
		];
		
		return addOptionsGroupJavaScript(__NAMESPACE__, __CLASS__, $fields);
	}
}
