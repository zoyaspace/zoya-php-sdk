<?php

declare(strict_types=1);

namespace Zoya\Sdk\Http;

use Zoya\Sdk\Support\Json;

final readonly class ApiResponse
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        public int $statusCode,
        public array $headers,
        public string $body,
    ) {
    }

    public function successful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function json(): mixed
    {
        if ($this->body === '') {
            return null;
        }

        return Json::decode($this->body);
    }

    public function header(string $name): ?string
    {
        $lowerName = strtolower($name);

        foreach ($this->headers as $headerName => $value) {
            if (strtolower($headerName) === $lowerName) {
                return $value;
            }
        }

        return null;
    }
}
