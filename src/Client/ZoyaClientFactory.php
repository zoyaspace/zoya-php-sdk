<?php

declare(strict_types=1);

namespace Zoya\Sdk\Client;

use InvalidArgumentException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\Psr18Client;
use Zoya\Sdk\Auth\ApiKeyAuthenticator;
use Zoya\Sdk\Http\Psr18Transport;
use Zoya\Sdk\Support\UserAgent;

final class ZoyaClientFactory
{
    public static function make(
        string $apiToken,
        ZoyaEnvironment $environment = ZoyaEnvironment::Production,
        string $apiVersion = 'v1',
        ?string $userAgent = null,
        string $accept = 'application/json',
        ?string $baseUrlOverride = null,
    ): ZoyaClient {
        if ($apiToken === '') {
            throw new InvalidArgumentException('The API token must not be empty.');
        }

        $psr17Factory = new Psr17Factory();

        return new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: $environment,
                apiVersion: $apiVersion,
                userAgent: $userAgent ?? UserAgent::default(),
                accept: $accept,
                baseUrlOverride: $baseUrlOverride,
            ),
            transport: new Psr18Transport(
                httpClient: new Psr18Client(),
                requestFactory: $psr17Factory,
                streamFactory: $psr17Factory,
            ),
            authenticator: new ApiKeyAuthenticator($apiToken),
        );
    }
}
