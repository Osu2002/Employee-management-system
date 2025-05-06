<?php

namespace App\Traits;

use App\Enum\ResponseCodeEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{

	protected function successResponse($data, $message = null, $code = 200, $responseCode = 'SUCCESS')
	{
		return response()->json([
			'code' => $responseCode,
			'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function errorResponse($message = null, $code, $responseCode = 'ERROR')
	{
		return response()->json([
			'code' => $responseCode,
			'status' => 'Error',
			'message' => $message,
			'data' => null
		], $code);
	}
}
