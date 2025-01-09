<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;


trait JsonResponser {

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     *
     * @return JsonResponse
     */
    public function success(array $data, string $message, int $code = 200): JsonResponse {

        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return JsonResponse
     */
    public function error(string $message, int $code): JsonResponse {

        return response()->json([
            'message' => $message
        ], $code);
    }
}
