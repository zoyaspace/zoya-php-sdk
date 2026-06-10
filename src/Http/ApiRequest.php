<?php

declare(strict_types=1);

namespace Zoya\Sdk\Http;

final readonly class ApiRequest
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        public string $method,
        public string $uri,
        public array $headers = [],
        public ?string $body = null,
    ) {
    }
}
