<?php

declare(strict_types=1);

namespace Zoya\Sdk\Tests\Http;

use PHPUnit\Framework\TestCase;
use Zoya\Sdk\Http\ApiResponse;

final class ApiResponseTest extends TestCase
{
    public function testItReadsCaseInsensitiveHeaders(): void
    {
        $response = new ApiResponse(
            statusCode: 200,
            headers: ['Content-Type' => 'application/json'],
            body: '{"ok":true}',
        );

        self::assertSame('application/json', $response->header('content-type'));
        self::assertSame(['ok' => true], $response->json());
    }
}
