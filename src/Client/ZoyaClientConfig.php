<?php

declare(strict_types=1);

namespace Zoya\Sdk\Client;

use InvalidArgumentException;
use Zoya\Sdk\Support\UserAgent;

final readonly class ZoyaClientConfig
{
    public ZoyaEnvironment $environment;
    public string $apiVersion;
    public string $userAgent;
    public string $accept;
    public ?string $baseUrlOverride;

    public function __construct(
        ZoyaEnvironment $environment = ZoyaEnvironment::Production,
        string $apiVersion = 'v1',
        ?string $userAgent = null,
        string $accept = 'application/json',
        ?string $baseUrlOverride = null,
    ) {
        $this->environment = $environment;
        $this->apiVersion = $apiVersion;
        $this->userAgent = $userAgent ?? UserAgent::default();
        $this->accept = $accept;
        $this->baseUrlOverride = $baseUrlOverride;

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
