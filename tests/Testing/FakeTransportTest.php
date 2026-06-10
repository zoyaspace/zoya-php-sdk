<?php

declare(strict_types=1);

namespace Zoya\Sdk\Tests\Testing;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Zoya\Sdk\Http\ApiResponse;
use Zoya\Sdk\Testing\FakeTransport;

final class FakeTransportTest extends TestCase
{
    public function testItStoresSentRequests(): void
    {
        $transport = new FakeTransport(static fn ($request) => new ApiResponse(204, [], ''));

        $transport->send(new \Zoya\Sdk\Http\ApiRequest('GET', 'https://api.zoya.test/health'));

        self::assertCount(1, $transport->requests());
    }

    public function testItFailsWhenExpectedRequestWasNotSent(): void
    {
        $this->expectException(RuntimeException::class);

        $transport = new FakeTransport(static fn ($request) => new ApiResponse(204, [], ''));

        $transport->assertSent(static fn () => false);
    }
}
