<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class Response
{
    public static function make(bool $success, string $message = '', $data = []): JsonResponse
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        if (!empty($message)) {
            $response['message'] = $message;
        }
        if (!empty($data)) {
            $response['data'] = $data;
        }
        return response()->json($response);
    }
}
