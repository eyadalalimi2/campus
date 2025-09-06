<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function __construct(string $message = 'خطأ في الطلب.', int $code = 422)
    {
        parent::__construct($message, $code);
    }

    public function render($request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status'  => 'error',
                'message' => $this->getMessage(),
            ], $this->getCode() ?: 422);
        }
    }
}
