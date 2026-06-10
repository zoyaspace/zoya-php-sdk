<?php

declare(strict_types=1);

namespace Zoya\Sdk\Exceptions;

use RuntimeException;
use Zoya\Sdk\Http\ApiResponse;

class ApiException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly ?ApiResponse $response = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function response(): ?ApiResponse
    {
        return $this->response;
    }
}
