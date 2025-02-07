<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

class ListingFormSetting
{
	public static function getValues($value, $disk)
	{
		$defaultCatDisplayType = (config('larapen.core.item.slug') == 'laraclassifier') ? 'c_bigIcon_list' : 'c_border_list';
		
		if (empty($value)) {
			
			$value['publication_form_type'] = '1';
			$value['picture_mandatory'] = '1';
			$value['pictures_limit'] = '5';
			$value['guest_can_submit_listings'] = '0';
			$value['guest_can_contact_authors'] = '0';
			$value['cat_display_type'] = $defaultCatDisplayType;
			$value['wysiwyg_editor'] = 'tinymce';
			$value['auto_registration'] = '0';
			
		} else {
			
			if (!array_key_exists('publication_form_type', $value)) {
				$value['publication_form_type'] = '1';
			}
			if (!array_key_exists('picture_mandatory', $value)) {
				$value['picture_mandatory'] = '1';
			}
			if (!array_key_exists('pictures_limit', $value)) {
				$value['pictures_limit'] = '5';
			}
			if (!array_key_exists('guest_can_submit_listings', $value)) {
				$value['guest_can_submit_listings'] = '0';
			}
			if (!array_key_exists('guest_can_contact_authors', $value)) {
				$value['guest_can_contact_authors'] = '0';
			}
			if (!array_key_exists('cat_display_type', $value)) {
				$value['cat_display_type'] = $defaultCatDisplayType;
			}
			if (!array_key_exists('wysiwyg_editor', $value)) {
				$value['wysiwyg_editor'] = 'tinymce';
			}
			if (!array_key_exists('auto_registration', $value)) {
				$value['auto_registration'] = '0';
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
		$wysiwygEditors = (array)config('larapen.options.wysiwyg');
		
		$fields = [
			[
				'name'  => 'general_separator_title',
				'type'  => 'custom_html',
				'value' => trans('admin.general_separator_title'),
			],
		];
		
		$formTypeField =
			[
				'name'    => 'publication_form_type',
				'label'   => trans('admin.publication_form_type_label'),
				'type'    => 'select2_from_array',
				'options' => [
					1 => trans('admin.publication_form_type_option_1'),
					2 => trans('admin.publication_form_type_option_2'),
				],
				'wrapper' => [
					'class' => 'col-md-6',
				],
			];
		
		// Add LaraClassifier extra fields
		if (config('larapen.core.item.slug') == 'laraclassifier') {
			$formTypeField['hint'] = trans('admin.publication_form_type_hint');
		}
		
		$fields[] = $formTypeField;
		
		// Add LaraClassifier extra fields
		if (config('larapen.core.item.slug') == 'laraclassifier') {
			$lcFields = [
				[
					'name'    => 'picture_mandatory',
					'label'   => trans('admin.picture_mandatory_label'),
					'type'    => 'checkbox_switch',
					'hint'    => trans('admin.picture_mandatory_hint'),
					'wrapper' => [
						'class' => 'col-md-6',
						'style' => 'margin-top: 10px;',
					],
					'newline' => true,
				],
				
				[
					'name'    => 'pictures_limit',
					'label'   => trans('admin.pictures_limit_label'),
					'type'    => 'text',
					'wrapper' => [
						'class' => 'col-md-6',
					],
				],
			];
			
			$fields = array_merge($fields, $lcFields);
		}
		
		$fields = array_merge($fields, [
			[
				'name'    => 'guest_can_submit_listings',
				'label'   => trans('admin.Allow Guests to post Listings'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'listings_review_activation',
				'label'   => trans('admin.Allow listings to be reviewed by Admins'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'form_cat_selection_title',
				'type'  => 'custom_html',
				'value' => trans('admin.form_cat_selection_title'),
			],
			[
				'name'        => 'cat_display_type',
				'label'       => trans('admin.form_cat_display_type_label'),
				'type'        => 'select2_from_array',
				'options'     => [
					'c_normal_list'  => trans('admin.cat_display_type_op_1'),
					'c_border_list'  => trans('admin.cat_display_type_op_2'),
					'c_bigIcon_list' => trans('admin.cat_display_type_op_3'),
					'c_picture_list' => trans('admin.cat_display_type_op_4'),
				],
				'allows_null' => false,
				'hint'        => trans('admin.form_cat_display_type_hint'),
				'wrapper'     => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'wysiwyg_editor_title',
				'type'  => 'custom_html',
				'value' => trans('admin.wysiwyg_editor_title_value'),
			],
			[
				'name'    => 'wysiwyg_editor',
				'label'   => trans('admin.wysiwyg_editor_label'),
				'type'    => 'select2_from_array',
				'options' => $wysiwygEditors,
				'hint'    => trans('admin.wysiwyg_editor_hint'),
			],
			[
				'name'  => 'remove_url_title',
				'type'  => 'custom_html',
				'value' => trans('admin.remove_url_title_value'),
			],
			[
				'name'    => 'remove_url_before',
				'label'   => trans('admin.remove_element_before_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.remove_element_before_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'remove_url_after',
				'label'   => trans('admin.remove_element_after_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.remove_element_after_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'remove_email_title',
				'type'  => 'custom_html',
				'value' => trans('admin.remove_email_title_value'),
			],
			[
				'name'    => 'remove_email_before',
				'label'   => trans('admin.remove_element_before_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.remove_element_before_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'remove_email_after',
				'label'   => trans('admin.remove_element_after_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.remove_element_after_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'remove_phone_title',
				'type'  => 'custom_html',
				'value' => trans('admin.remove_phone_title_value'),
			],
			[
				'name'    => 'remove_phone_before',
				'label'   => trans('admin.remove_element_before_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.remove_element_before_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'remove_phone_after',
				'label'   => trans('admin.remove_element_after_label'),
				'type'    => 'checkbox_switch',
				'hint'    => trans('admin.remove_element_after_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'  => 'auto_registration_sep',
				'type'  => 'custom_html',
				'value' => trans('admin.auto_registration_sep_value'),
			],
			[
				'name'    => 'auto_registration',
				'label'   => trans('admin.auto_registration_label'),
				'type'    => 'select2_from_array',
				'options' => [
					0 => trans('admin.auto_registration_option_0'),
					1 => trans('admin.auto_registration_option_1'),
					2 => trans('admin.auto_registration_option_2'),
				],
				'hint'    => trans('admin.auto_registration_hint'),
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
		]);
		
		return $fields;
	}
}
