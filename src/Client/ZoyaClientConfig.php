<?php

declare(strict_types=1);

namespace Zoya\Sdk\Client;

use InvalidArgumentException;

final readonly class ZoyaClientConfig
{
    public function __construct(
        public ZoyaEnvironment $environment = ZoyaEnvironment::Production,
        public string $apiVersion = 'v1',
        public string $userAgent = 'zoya-php-sdk/0.1.0',
        public string $accept = 'application/json',
        public ?string $baseUrlOverride = null,
    ) {
        if (! preg_match('/^v\d+$/', $this->apiVersion)) {
            throw new InvalidArgumentException('The API version must use the format v{number}.');
        }

        if (
            $this->baseUrlOverride !== null
            && filter_var($this->baseUrlOverride, FILTER_VALIDATE_URL) === false
        ) {
            throw new InvalidArgumentException('The base URL override must be a valid absolute URL.');
        }
    }

    public function baseUri(): string
    {
        if ($this->baseUrlOverride !== null) {
            return rtrim($this->baseUrlOverride, '/');
        }

        return rtrim($this->environment->apiHost(), '/') . '/public/' . $this->apiVersion;
    }
}
