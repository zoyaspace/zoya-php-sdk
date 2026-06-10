<?php

declare(strict_types=1);

namespace Zoya\Sdk\Tests\Client;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Zoya\Sdk\Client\ZoyaClientConfig;
use Zoya\Sdk\Client\ZoyaEnvironment;
use Zoya\Sdk\Support\UserAgent;

final class ZoyaClientConfigTest extends TestCase
{
    public function testItUsesTheProductionEnvironmentByDefault(): void
    {
        $config = new ZoyaClientConfig();

        self::assertSame('https://api.zoyaspace.com/public/v1', $config->baseUri());
        self::assertSame(UserAgent::default(), $config->userAgent);
    }

    public function testItMapsTheDevelopmentEnvironmentToTheDevelopmentApi(): void
    {
        $config = new ZoyaClientConfig(environment: ZoyaEnvironment::Development);

        self::assertSame('https://api-dev.zoyaspace.com/public/v1', $config->baseUri());
    }

    public function testItAllowsChangingThePublicApiVersion(): void
    {
        $config = new ZoyaClientConfig(
            environment: ZoyaEnvironment::Production,
            apiVersion: 'v2',
        );

        self::assertSame('https://api.zoyaspace.com/public/v2', $config->baseUri());
    }

    public function testItAllowsABaseUrlOverrideForMockingAndContractTests(): void
    {
        $config = new ZoyaClientConfig(
            environment: ZoyaEnvironment::Production,
            baseUrlOverride: 'https://mock.zoya.test/public/v1',
        );

        self::assertSame('https://mock.zoya.test/public/v1', $config->baseUri());
    }

    public function testItAllowsOverridingTheUserAgent(): void
    {
        $config = new ZoyaClientConfig(userAgent: 'zoya-api-test/1.0');

        self::assertSame('zoya-api-test/1.0', $config->userAgent);
    }

    public function testItRejectsAnInvalidApiVersion(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ZoyaClientConfig(apiVersion: '1');
    }

    public function testItRejectsAnInvalidBaseUrlOverride(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ZoyaClientConfig(baseUrlOverride: 'not-a-url');
    }
}
