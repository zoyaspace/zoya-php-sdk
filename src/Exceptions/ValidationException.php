<?php

declare(strict_types=1);

namespace Zoya\Sdk\Exceptions;

final class ValidationException extends ApiException
{
    /**
     * @param array<string, array<int, string>> $errors
     */
    public function __construct(
        string $message,
        private readonly array $errors = [],
        ?\Zoya\Sdk\Http\ApiResponse $response = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $response, $previous);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
