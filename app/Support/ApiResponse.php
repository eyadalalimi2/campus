<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function ok($data = null, array $meta = [], array $links = [], int $status = 200): JsonResponse
    {
        $payload = ['data' => $data];
        if ($meta)  $payload['meta']  = $meta;
        if ($links) $payload['links'] = $links;
        return response()->json($payload, $status, ['Content-Type' => 'application/json; charset=utf-8']);
    }

    public static function error(string $code, string $message, array $fields = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'error' => [
                'code'       => $code,
                'message'    => $message,
                'fields'     => $fields ?: null,
                'trace_id'   => request()->header('X-Trace-Id') ?: null,
                'request_id' => request()->header('X-Request-Id') ?: null,
            ]
        ], $status, ['Content-Type' => 'application/json; charset=utf-8']);
    }
}
