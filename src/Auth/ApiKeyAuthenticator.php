<?php

declare(strict_types=1);

namespace Zoya\Sdk\Auth;

final readonly class ApiKeyAuthenticator implements Authenticator
{
    public function __construct(
        private string $apiKey,
        private string $headerName = 'X-Zoya-Api-Key',
    ) {
    }

    public function authenticate(array $headers): array
    {
        $headers[$this->headerName] = $this->apiKey;

        return $headers;
    }
}
