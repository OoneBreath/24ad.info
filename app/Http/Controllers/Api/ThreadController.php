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

namespace App\Http\Controllers\Api;

use App\Http\Requests\Front\ReplyMessageRequest;
use App\Http\Requests\Front\SendMessageRequest;
use App\Services\ThreadService;
use Illuminate\Http\JsonResponse;

/**
 * @group Threads
 */
class ThreadController extends BaseController
{
	protected ThreadService $threadService;
	
	/**
	 * @param \App\Services\ThreadService $threadService
	 */
	public function __construct(ThreadService $threadService)
	{
		parent::__construct();
		
		$this->threadService = $threadService;
	}
	
	/**
	 * List threads
	 *
	 * Get all logged user's threads.
	 * Filters:
	 * - unread: Get the logged user's unread threads
	 * - started: Get the logged user's started threads
	 * - important: Get the logged user's make as important threads
	 *
	 * @authenticated
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @queryParam filter string Filter for the list - Possible value: unread, started or important. Example: unread
	 * @queryParam embed string Comma-separated list of the post relationships for Eager Loading - Possible values: post. Example: null
	 * @queryParam perPage int Items per page. Can be defined globally from the admin settings. Cannot be exceeded 100. Example: 2
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(): JsonResponse
	{
		$params = [
			'perPage' => request()->integer('perPage'),
			'embed'   => request()->input('embed'),
			'filter'  => request()->input('filter'),
		];
		
		return $this->threadService->getEntries($params);
	}
	
	/**
	 * Get thread
	 *
	 * Get a thread (owned by the logged user) details
	 *
	 * @authenticated
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @queryParam embed string Comma-separated list of the post relationships for Eager Loading - Possible values: user,post,messages,participants. Example: null
	 *
	 * @urlParam id int required The thread's ID. Example: 8
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id): JsonResponse
	{
		$params = [
			'embed' => request()->input('embed'),
		];
		
		return $this->threadService->getEntry($id, $params);
	}
	
	/**
	 * Store thread
	 *
	 * Start a conversation. Creation of a new thread.
	 *
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @bodyParam post_id int required The related post ID. Example: 2
	 * @bodyParam name string required The thread's creator name. Example: John Doe
	 * @bodyParam auth_field string required The user's auth field ('email' or 'phone'). Example: email
	 * @bodyParam email string The thread's creator email address (Required when 'auth_field' value is 'email'). Example: john.doe@domain.tld
	 * @bodyParam phone string The thread's creator mobile phone number (Required when 'auth_field' value is 'phone').
	 * @bodyParam phone_country string required The user's phone number's country code (Required when the 'phone' field is filled). Example: null
	 * @bodyParam body string required The name of the user. Example: Modi temporibus voluptas expedita voluptatibus voluptas veniam.
	 * @bodyParam file_path file The thread attached file.
	 * @bodyParam captcha_key string Key generated by the CAPTCHA endpoint calling (Required when the CAPTCHA verification is enabled from the Admin panel).
	 *
	 * @param \App\Http\Requests\Front\SendMessageRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(SendMessageRequest $request): JsonResponse
	{
		$postId = $request->input('post_id');
		
		return $this->threadService->store($postId, $request);
	}
	
	/**
	 * Update thread
	 *
	 * @authenticated
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @bodyParam body string required The name of the user. Example: Modi temporibus voluptas expedita voluptatibus voluptas veniam.
	 * @bodyParam file_path file The thread attached file.
	 *
	 * @urlParam id int required The thread's ID. Example: 111111
	 *
	 * @param $id
	 * @param \App\Http\Requests\Front\ReplyMessageRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id, ReplyMessageRequest $request): JsonResponse
	{
		return $this->threadService->update($id, $request);
	}
	
	/**
	 * Bulk updates
	 *
	 * @authenticated
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @queryParam type string required The type of action to execute (markAsRead, markAsUnread, markAsImportant, markAsNotImportant or markAllAsRead).
	 *
	 * @urlParam ids string required The ID or comma-separated IDs list of thread(s).. Example: 111111,222222,333333
	 *
	 * @param string|null $ids
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function bulkUpdate(?string $ids = null): JsonResponse
	{
		$actionType = request()->input('type');
		
		return $this->threadService->bulkUpdate($ids, $actionType);
	}
	
	/**
	 * Delete thread(s)
	 *
	 * @authenticated
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @urlParam ids string required The ID or comma-separated IDs list of thread(s). Example: 111111,222222,333333
	 *
	 * @param string $ids
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy(string $ids): JsonResponse
	{
		return $this->threadService->destroy($ids);
	}
}
