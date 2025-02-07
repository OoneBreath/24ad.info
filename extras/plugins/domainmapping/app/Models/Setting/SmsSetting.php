<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class SmsSetting
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
		// Get Drivers List
		$smsDrivers = (array)config('larapen.options.sms');
		
		// Get the drivers selectors list as JS objects
		$smsDriversSelectorsJson = collect($smsDrivers)
			->keys()
			->mapWithKeys(fn ($item) => [$item => '.' . $item])
			->toJson();
		
		$fields = [
			[
				'name'    => 'enable_phone_as_auth_field',
				'label'   => trans('admin.enable_phone_as_auth_field_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.card_light_inverse', [
					'content' => trans('admin.enable_phone_as_auth_field_hint', [
						'phone_verification_label' => trans('admin.phone_verification_label'),
					]),
				]),
				'wrapper' => [
					'class' => 'col-md-6 mt-3',
				],
			],
			[
				'name'    => 'default_auth_field',
				'label'   => trans('admin.default_auth_field_label'),
				'type'    => 'select2_from_array',
				'options' => [
					'email' => t('email_address'),
					'phone' => t('phone_number'),
				],
				'default' => 'email',
				'hint'    => trans('admin.card_light_inverse', [
					'content' => trans('admin.default_auth_field_hint', [
						'enable_phone_as_auth_field_label' => trans('admin.enable_phone_as_auth_field_label'),
						'email'                            => t('email_address'),
						'phone'                            => t('phone_number'),
					]),
				]),
				'wrapper' => [
					'class' => 'col-md-6 auth-field-el',
				],
				'newline' => true,
			],
		];
		
		$phoneOfCountriesOptions = [
			'local'     => trans('admin.phone_of_countries_op_1'),
			'activated' => trans('admin.phone_of_countries_op_2'),
			'all'       => trans('admin.phone_of_countries_op_3'),
		];
		$phoneValidatorOptions = [
			'none'                   => trans('admin.phone_validator_op_0'),
			'isValidMobileNumber'    => trans('admin.phone_validator_op_1'),
			'isPossibleMobileNumber' => trans('admin.phone_validator_op_2'),
			'isValidPhoneNumber'     => trans('admin.phone_validator_op_3'),
			'isPossiblePhoneNumber'  => trans('admin.phone_validator_op_4'),
		];
		$fields = array_merge($fields, [
			[
				'name'    => 'phone_of_countries',
				'label'   => trans('admin.phone_of_countries_label'),
				'type'    => 'select2_from_array',
				'options' => $phoneOfCountriesOptions,
				'hint'    => trans('admin.card_light_inverse', [
					'content' => trans('admin.phone_of_countries_hint', $phoneOfCountriesOptions),
				]),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'phone_validator',
				'label'   => trans('admin.phone_validator_label'),
				'type'    => 'select2_from_array',
				'options' => $phoneValidatorOptions,
				'hint'    => trans('admin.card_light_inverse', [
					'content' => trans('admin.phone_validator_hint', array_merge($phoneValidatorOptions, [
						'enable_phone_as_auth_field_label' => trans('admin.enable_phone_as_auth_field_label'),
					])),
				]),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'    => 'default_auth_field_sep',
				'type'    => 'custom_html',
				'value'   => '<hr style="border: 1px dashed #EFEFEF;" class="my-3">',
				'wrapper' => [
					'class' => 'col-12',
				],
			],
		]);
		
		// driver
		$fields = array_merge($fields, [
			[
				'name'    => 'driver',
				'label'   => trans('admin.SMS Driver'),
				'type'    => 'select2_from_array',
				'options' => $smsDrivers,
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
		]);
		
		// vonage
		if (array_key_exists('vonage', $smsDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'    => 'driver_vonage_title',
					'type'    => 'custom_html',
					'value'   => trans('admin.driver_vonage_title'),
					'wrapper' => [
						'class' => 'col-md-12 vonage',
					],
				],
				[
					'name'    => 'driver_vonage_info',
					'type'    => 'custom_html',
					'value'   => trans('admin.driver_vonage_info'),
					'wrapper' => [
						'class' => 'col-md-12 vonage',
					],
				],
				[
					'name'    => 'vonage_key',
					'label'   => trans('admin.Vonage Key'),
					'type'    => 'text',
					'wrapper' => [
						'class' => 'col-md-6 vonage',
					],
				],
				[
					'name'    => 'vonage_secret',
					'label'   => trans('admin.Vonage Secret'),
					'type'    => 'text',
					'wrapper' => [
						'class' => 'col-md-6 vonage',
					],
				],
				[
					'name'    => 'vonage_from',
					'label'   => trans('admin.Vonage From'),
					'type'    => 'text',
					'wrapper' => [
						'class' => 'col-md-6 vonage',
					],
				],
			]);
		}
		
		// twilio
		if (array_key_exists('twilio', $smsDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'    => 'driver_twilio_title',
					'type'    => 'custom_html',
					'value'   => trans('admin.driver_twilio_title'),
					'wrapper' => [
						'class' => 'col-md-12 twilio',
					],
				],
				[
					'name'    => 'driver_twilio_info',
					'type'    => 'custom_html',
					'value'   => trans('admin.driver_twilio_info'),
					'wrapper' => [
						'class' => 'col-md-12 twilio',
					],
				],
				[
					'name'    => 'twilio_username',
					'label'   => trans('admin.twilio_username_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_username_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'    => 'twilio_password',
					'label'   => trans('admin.twilio_password_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_password_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'    => 'twilio_account_sid',
					'label'   => trans('admin.twilio_account_sid_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_account_sid_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'    => 'twilio_auth_token',
					'label'   => trans('admin.twilio_auth_token_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_auth_token_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'    => 'twilio_from',
					'label'   => trans('admin.twilio_from_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_from_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'    => 'twilio_alpha_sender',
					'label'   => trans('admin.twilio_alpha_sender_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_alpha_sender_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'    => 'twilio_sms_service_sid',
					'label'   => trans('admin.twilio_sms_service_sid_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_sms_service_sid_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'    => 'twilio_debug_to',
					'label'   => trans('admin.twilio_debug_to_label'),
					'type'    => 'text',
					'hint'    => trans('admin.twilio_debug_to_hint'),
					'wrapper' => [
						'class' => 'col-md-6 twilio',
					],
				],
			]);
		}
		
		$fields = array_merge($fields, [
			[
				'name'  => 'driver_test_title',
				'type'  => 'custom_html',
				'value' => trans('admin.driver_test_title'),
			],
			[
				'name'  => 'driver_test_info',
				'type'  => 'custom_html',
				'value' => trans('admin.card_light_inverse', [
					'content' => trans('admin.sms_driver_test_info', ['smsTo' => trans('admin.sms_to_label')]),
				]),
			],
			[
				'name'    => 'driver_test',
				'label'   => trans('admin.driver_test_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.sms_driver_test_hint'),
				'wrapper' => [
					'class' => 'col-md-6 mt-2',
				],
			],
			[
				'name'    => 'sms_to',
				'label'   => trans('admin.sms_to_label'),
				'type'    => 'tel',
				'default' => config('settings.app.phone_number'),
				'hint'    => trans('admin.sms_to_hint', ['option' => trans('admin.driver_test_label')]),
				'wrapper' => [
					'class' => 'col-md-6 driver-test',
				],
			],
		]);
		
		$fields = array_merge($fields, [
			[
				'name'  => 'sms_notification_types_title',
				'type'  => 'custom_html',
				'value' => trans('admin.sms_notification_types_title'),
			],
			[
				'name'  => 'phone_verification',
				'label' => trans('admin.phone_verification_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.phone_verification_hint', [
						'email_verification_label' => trans('admin.email_verification_label'),
					]) . '<br>' . trans('admin.sms_sending_requirements'),
			],
			[
				'name'  => 'confirmation',
				'label' => trans('admin.settings_sms_confirmation_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.settings_sms_confirmation_hint') . '<br>' . trans('admin.sms_sending_requirements'),
			],
			[
				'name'  => 'messenger_notifications',
				'label' => trans('admin.messenger_notifications_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.messenger_notifications_hint') . '<br>' . trans('admin.sms_sending_requirements'),
			],
		]);
		
		return addOptionsGroupJavaScript(__NAMESPACE__, __CLASS__, $fields, [
			'smsDriversSelectorsJson' => $smsDriversSelectorsJson,
		]);
	}
}
