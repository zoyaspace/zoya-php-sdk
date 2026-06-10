<?php

declare(strict_types=1);

namespace Zoya\Sdk\Tests\Client;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Zoya\Sdk\Client\ZoyaClient;
use Zoya\Sdk\Client\ZoyaClientFactory;
use Zoya\Sdk\Client\ZoyaEnvironment;

final class ZoyaClientFactoryTest extends TestCase
{
    public function testItCreatesAClientWithTheDefaultPublicApiConfiguration(): void
    {
        $client = ZoyaClientFactory::make(
            apiToken: 'zya_test.secret',
            environment: ZoyaEnvironment::Development,
            apiVersion: 'v1',
            userAgent: 'zoya-sdk-tests/1.0',
        );

        self::assertInstanceOf(ZoyaClient::class, $client);
    }

    public function testItRejectsAnEmptyApiToken(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ZoyaClientFactory::make(apiToken: '');
    }
}
