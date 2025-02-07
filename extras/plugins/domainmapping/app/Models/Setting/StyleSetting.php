<?php

namespace extras\plugins\domainmapping\app\Models\Setting;

use App\Helpers\Common\Files\Upload;

class StyleSetting
{
	public static function passedValidation($request)
	{
		$params = [
			[
				'attribute' => 'body_background_image_path',
				'destPath'  => 'app/logo',
				'width'     => (int)config('larapen.media.resize.namedOptions.bg-body.width', 2500),
				'height'    => (int)config('larapen.media.resize.namedOptions.bg-body.height', 2500),
				'ratio'     => config('larapen.media.resize.namedOptions.bg-body.ratio', '1'),
				'upsize'    => config('larapen.media.resize.namedOptions.bg-body.upsize', '0'),
				'filename'  => 'body-background-',
			],
			[
				'attribute' => 'login_bg_image_path',
				'destPath'  => 'app/backend',
				'width'     => (int)config('larapen.media.resize.namedOptions.bg-body.width', 2500),
				'height'    => (int)config('larapen.media.resize.namedOptions.bg-body.height', 2500),
				'ratio'     => config('larapen.media.resize.namedOptions.bg-body.ratio', '1'),
				'upsize'    => config('larapen.media.resize.namedOptions.bg-body.upsize', '0'),
				'filename'  => 'login-bg-image-',
			],
		];
		
		foreach ($params as $param) {
			$file = $request->hasFile($param['attribute'])
				? $request->file($param['attribute'])
				: $request->input($param['attribute']);
			
			$request->request->set($param['attribute'], Upload::image($param['destPath'], $file, $param));
		}
		
		return $request;
	}
	
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			// ...
			
		} else {
			
			foreach ($value as $key => $item) {
				if ($key == 'body_background_image_path') {
					if (empty($item) || !$disk->exists($item)) {
						$value[$key] = null;
					}
				}
			}
			// ...
			
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
				'value' => trans('admin.style_html_frontend'),
			],
			[
				'name'  => 'separator_2',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_front'),
			],
			[
				'name'  => 'separator_2_1',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_front_global'),
			],
			[
				'name'       => 'body_background_color',
				'label'      => trans('admin.Body Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#FFFFFF',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'body_text_color',
				'label'      => trans('admin.Body Text Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#292B2C',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'body_background_image_path',
				'label'   => trans('admin.Body Background Image'),
				'type'    => 'image',
				'upload'  => true,
				'disk'    => $diskName,
				'default' => null,
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
			
			[
				'name'    => 'body_background_image_fixed',
				'label'   => trans('admin.Body Background Image Fixed'),
				'type'    => 'checkbox_switch',
				'wrapper' => [
					'class' => 'col-md-6',
				
				],
			],
			[
				'name'    => 'page_width',
				'label'   => trans('admin.Page Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
				'newline' => true,
			],
			
			[
				'name'       => 'title_color',
				'label'      => trans('admin.Titles Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#292B2C',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'progress_background_color',
				'label'      => trans('admin.Progress Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'link_color',
				'label'      => trans('admin.Links Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#4682B4',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'link_color_hover',
				'label'      => trans('admin.Links Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#FF8C00',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2_2',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_header'),
			],
			[
				'name'  => 'header_sticky',
				'label' => trans('admin.Header Sticky'),
				'type'  => 'checkbox_switch',
			],
			[
				'name'    => 'header_height',
				'label'   => trans('admin.Header Height'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_background_color',
				'label'      => trans('admin.Header Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#F8F8F8',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'header_bottom_border_width',
				'label'   => trans('admin.Header Bottom Border Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_bottom_border_color',
				'label'      => trans('admin.Header Bottom Border Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#E8E8E8',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_link_color',
				'label'      => trans('admin.Header Links Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'header_link_color_hover',
				'label'      => trans('admin.Header Links Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#000',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2_3',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_footer'),
			],
			[
				'name'       => 'footer_background_color',
				'label'      => trans('admin.Footer Background Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#F5F5F5',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_text_color',
				'label'      => trans('admin.Footer Text Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_title_color',
				'label'      => trans('admin.Footer Titles Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#000',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
				'newline'    => true,
			],
			
			[
				'name'       => 'footer_link_color',
				'label'      => trans('admin.Footer Links Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'footer_link_color_hover',
				'label'      => trans('admin.Footer Links Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#333',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'payment_icon_top_border_width',
				'label'   => trans('admin.Payment Methods Icons Top Border Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'payment_icon_top_border_color',
				'label'      => trans('admin.Payment Methods Icons Top Border Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#DDD',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'    => 'payment_icon_bottom_border_width',
				'label'   => trans('admin.Payment Methods Icons Bottom Border Width'),
				'type'    => 'number',
				'wrapper' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'payment_icon_bottom_border_color',
				'label'      => trans('admin.Payment Methods Icons Bottom Border Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#DDD',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_2_4',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_customize_button_al'),
			],
			[
				'name'       => 'btn_listing_bg_top_color',
				'label'      => trans('admin.Gradient Background Top Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#ffeb43',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_bg_bottom_color',
				'label'      => trans('admin.Gradient Background Bottom Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#fcde11',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_border_color',
				'label'      => trans('admin.Button Border Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#f6d80f',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_text_color',
				'label'      => trans('admin.Button Text Color'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#292b2c',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_bg_top_color_hover',
				'label'      => trans('admin.Gradient Background Top Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#fff860',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_bg_bottom_color_hover',
				'label'      => trans('admin.Gradient Background Bottom Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#ffeb43',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_border_color_hover',
				'label'      => trans('admin.Button Border Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#fcde11',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'       => 'btn_listing_text_color_hover',
				'label'      => trans('admin.Button Text Color Hover'),
				'type'       => 'color_picker',
				'attributes' => [
					'placeholder' => '#1b1d1e',
				],
				'wrapper'    => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'  => 'separator_3',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_raw_css'),
			],
			[
				'name'  => 'separator_3_1',
				'type'  => 'custom_html',
				'value' => trans('admin.style_html_raw_css_hint'),
			],
			[
				'name'       => 'custom_css',
				'label'      => trans('admin.Custom CSS'),
				'type'       => 'textarea',
				'attributes' => [
					'rows' => '5',
				],
				'hint'       => trans('admin.do_not_include_style_tags'),
			],
		];
		
		return $fields;
	}
}
