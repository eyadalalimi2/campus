<?php
namespace App\Exceptions\Api;

use Exception;
use Throwable;

class ApiException extends Exception
{
    public string $apiCode;
    public int $status;
    public array $fields;

    public function __construct(string $apiCode, string $message, int $status = 400, array $fields = [], ?Throwable $previous = null)
    {
        parent::__construct($message, $status, $previous);
        $this->apiCode = $apiCode;
        $this->status  = $status;
        $this->fields  = $fields;
    }

    public function toArray(): array
    {
        return [
            'code'    => $this->apiCode,
            'message' => $this->getMessage(),
            'fields'  => $this->fields ?: null,
        ];
    }
}
