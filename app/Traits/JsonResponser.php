<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;


trait JsonResponser {

    /**
     * @param mixed $data
     * @param string $message
     * @param int $code
     *
     * @return JsonResponse
     */
    public function success(mixed $data, string $message, int $code = 200): JsonResponse {

        return response()->json([
            'message' => $message,
            'data' => $data,
            'code' => $code
        ]);
    }

    public function error(string $message, int $code): JsonResponse {

        return response()->json([
            'message' => $message,
            'code' => $code
        ]);
    }
}
