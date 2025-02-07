<?php

namespace extras\plugins\reviews\app\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use extras\plugins\reviews\app\Http\Requests\ReviewRequest;
use extras\plugins\reviews\app\Services\ReviewService;
use Illuminate\Http\JsonResponse;

/**
 * @group Reviews
 */
class ReviewController extends BaseController
{
	protected ReviewService $reviewService;
	
	public function __construct(ReviewService $reviewService)
	{
		parent::__construct();
		
		$this->reviewService = $reviewService;
	}
	
	/**
	 * List reviews
	 *
	 * @bodyParam postId int required The post's ID. Example: 2
	 *
	 * @param $postId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index($postId): JsonResponse
	{
		$params = [
			'perPage' => request()->integer('perPage'),
			'embed'   => request()->input('embed'),
		];
		
		return $this->reviewService->getEntries($postId, $params);
	}
	
	/**
	 * Store review
	 *
	 * @authenticated
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @bodyParam post_id int required The listing's ID. Example: null
	 * @bodyParam user_id int The logged user's ID. Example: null
	 * @bodyParam comment string required The review's message. Example: null
	 * @bodyParam rating int required The review's rating. Example: 4
	 * @bodyParam captcha_key string Key generated by the CAPTCHA endpoint calling (Required when the CAPTCHA verification is enabled from the Admin panel).
	 *
	 * @urlParam postId int required The listing's ID. Example: 2
	 *
	 * @param $postId
	 * @param \extras\plugins\reviews\app\Http\Requests\ReviewRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store($postId, ReviewRequest $request): JsonResponse
	{
		return $this->reviewService->store($postId, $request);
	}
	
	/**
	 * Delete review(s)
	 *
	 * NOTE: Let's consider that only the reviews of the same listings can be deleted in bulk.
	 *
	 * @authenticated
	 * @header Authorization Bearer {YOUR_AUTH_TOKEN}
	 *
	 * @urlParam postId int required The listing's ID. Example: 2
	 * @urlParam ids string required The ID or comma-separated IDs list of review(s).
	 *
	 * @param $postId
	 * @param $ids
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($postId, $ids): JsonResponse
	{
		// Get entries ID(s)
		$ids = getSelectedEntryIds($ids, request()->input('entries'), asString: true);
		
		return $this->reviewService->destroy($postId, $ids);
	}
}
