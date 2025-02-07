<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Front\ResetPasswordRequest;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Http\JsonResponse;

/**
 * @group Authentication
 */
class ResetPasswordController extends BaseController
{
	protected ResetPasswordService $resetPasswordService;
	
	/**
	 * @param \App\Services\Auth\ResetPasswordService $resetPasswordService
	 */
	public function __construct(ResetPasswordService $resetPasswordService)
	{
		parent::__construct();
		
		$this->resetPasswordService = $resetPasswordService;
	}
	
	/**
	 * Reset password
	 *
	 * @bodyParam auth_field string required The user's auth field ('email' or 'phone'). Example: email
	 * @bodyParam email string The user's email address or username (Required when 'auth_field' value is 'email'). Example: john.doe@domain.tld
	 * @bodyParam phone string The user's mobile phone number (Required when 'auth_field' value is 'phone'). Example: null
	 * @bodyParam phone_country string required The user's phone number's country code (Required when the 'phone' field is filled). Example: null
	 * @bodyParam password string required The user's password. Example: js!X07$z61hLA
	 * @bodyParam password_confirmation string required The confirmation of the user's password. Example: js!X07$z61hLA
	 * @bodyParam captcha_key string Key generated by the CAPTCHA endpoint calling (Required when the CAPTCHA verification is enabled from the Admin panel).
	 *
	 * @param \App\Http\Requests\Front\ResetPasswordRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function reset(ResetPasswordRequest $request): JsonResponse
	{
		return $this->resetPasswordService->reset($request);
	}
}
