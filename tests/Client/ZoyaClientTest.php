<?php

declare(strict_types=1);

namespace Zoya\Sdk\Tests\Client;

use PHPUnit\Framework\TestCase;
use Zoya\Sdk\Auth\ApiKeyAuthenticator;
use Zoya\Sdk\Client\ZoyaClient;
use Zoya\Sdk\Client\ZoyaClientConfig;
use Zoya\Sdk\Client\ZoyaEnvironment;
use Zoya\Sdk\Testing\FakeTransport;

final class ZoyaClientTest extends TestCase
{
    public function testItBuildsAJsonRequestWithApiKeyAuthentication(): void
    {
        $transport = new FakeTransport(static function ($request) {
            return new \Zoya\Sdk\Http\ApiResponse(
                statusCode: 200,
                headers: ['Content-Type' => 'application/json'],
                body: '{"ok":true}'
            );
        });

        $client = new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test',
                userAgent: 'zoya-sdk-tests/1.0',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );

        $response = $client->request(
            method: 'post',
            path: '/tenants',
            query: ['filter' => ['status' => 'active']],
            json: ['name' => 'Acme'],
        );

        self::assertTrue($response->successful());

        $transport->assertSent(static function ($request): bool {
            self::assertSame('POST', $request->method);
            self::assertSame(
                'https://api.zoya.test/tenants?filter%5Bstatus%5D=active',
                $request->uri
            );
            self::assertSame('zya_test.secret', $request->headers['X-Zoya-Api-Key'] ?? null);
            self::assertSame('application/json', $request->headers['Content-Type'] ?? null);
            self::assertSame('{"name":"Acme"}', $request->body);

            return true;
        });
    }

    public function testItBuildsAPropertyInvestmentsListRequestWithApiKeyAuthentication(): void
    {
        $transport = new FakeTransport(static function ($request) {
            return new \Zoya\Sdk\Http\ApiResponse(
                statusCode: 200,
                headers: ['Content-Type' => 'application/json'],
                body: '{"data":[],"meta":{"current_page":1}}'
            );
        });

        $client = new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test/public/v1',
                userAgent: 'zoya-sdk-tests/1.0',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );

        $response = $client->listPropertyInvestments([
            'page' => [
                'number' => 1,
                'size' => 20,
            ],
            'filter' => [
                'status' => ['active'],
                'city' => 'Warszawa',
            ],
            'sort' => '-created_at',
        ]);

        self::assertTrue($response->successful());

        $transport->assertSent(static function ($request): bool {
            self::assertSame('GET', $request->method);
            self::assertSame(
                'https://api.zoya.test/public/v1/investments?page%5Bnumber%5D=1&page%5Bsize%5D=20&filter%5Bstatus%5D%5B0%5D=active&filter%5Bcity%5D=Warszawa&sort=-created_at',
                $request->uri
            );
            self::assertSame('zya_test.secret', $request->headers['X-Zoya-Api-Key'] ?? null);
            self::assertNull($request->body);

            return true;
        });
    }

    public function testItBuildsAPropertyInvestmentDetailRequest(): void
    {
        $transport = new FakeTransport(static function ($request) {
            return new \Zoya\Sdk\Http\ApiResponse(
                statusCode: 200,
                headers: ['Content-Type' => 'application/json'],
                body: '{"id":"piv_123","type":"property_investments"}'
            );
        });

        $client = new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test/public/v1',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );

        $client->getPropertyInvestment('piv_123');

        $transport->assertSent(static function ($request): bool {
            self::assertSame('GET', $request->method);
            self::assertSame('https://api.zoya.test/public/v1/investments/piv_123', $request->uri);

            return true;
        });
    }

    public function testItBuildsAProductListRequest(): void
    {
        $transport = new FakeTransport(static function ($request) {
            return new \Zoya\Sdk\Http\ApiResponse(
                statusCode: 200,
                headers: ['Content-Type' => 'application/json'],
                body: '{"data":[],"meta":{"current_page":1}}'
            );
        });

        $client = new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test/public/v1',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );

        $client->listProducts([
            'filter' => [
                'type' => ['product'],
                'sales_channel_id' => ['sch_123'],
            ],
            'sort' => 'name',
        ]);

        $transport->assertSent(static function ($request): bool {
            self::assertSame('GET', $request->method);
            self::assertSame(
                'https://api.zoya.test/public/v1/products?filter%5Btype%5D%5B0%5D=product&filter%5Bsales_channel_id%5D%5B0%5D=sch_123&sort=name',
                $request->uri
            );

            return true;
        });
    }

    public function testItBuildsALeadSourcesListRequest(): void
    {
        $transport = new FakeTransport(static function ($request) {
            return new \Zoya\Sdk\Http\ApiResponse(
                statusCode: 200,
                headers: ['Content-Type' => 'application/json'],
                body: '{"data":[],"meta":{"current_page":1}}'
            );
        });

        $client = new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test/public/v1',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );

        $client->listLeadSources([
            'search' => 'Meta Ads',
        ]);

        $transport->assertSent(static function ($request): bool {
            self::assertSame('GET', $request->method);
            self::assertSame(
                'https://api.zoya.test/public/v1/lead-sources?search=Meta%20Ads',
                $request->uri
            );

            return true;
        });
    }

    public function testItBuildsASalesChannelDetailRequest(): void
    {
        $transport = new FakeTransport(static function ($request) {
            return new \Zoya\Sdk\Http\ApiResponse(
                statusCode: 200,
                headers: ['Content-Type' => 'application/json'],
                body: '{"id":"sch_123","type":"sales_channels"}'
            );
        });

        $client = new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test/public/v1',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );

        $client->getSalesChannel('sch_123');

        $transport->assertSent(static function ($request): bool {
            self::assertSame('GET', $request->method);
            self::assertSame('https://api.zoya.test/public/v1/sales-channels/sch_123', $request->uri);

            return true;
        });
    }

    public function testItBuildsALeadCreationRequest(): void
    {
        $transport = new FakeTransport(static function ($request) {
            return new \Zoya\Sdk\Http\ApiResponse(
                statusCode: 201,
                headers: ['Content-Type' => 'application/json'],
                body: '{"id":"led_123","type":"leads"}'
            );
        });

        $client = new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test/public/v1',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );

        $client->createLead([
            'contact_name' => 'Jan Kowalski',
            'contact_email' => 'jan@example.com',
            'sales_channel_id' => 'sch_123',
            'lead_source_id' => 'lso_123',
            'interested_product_id' => 'prd_123',
        ]);

        $transport->assertSent(static function ($request): bool {
            self::assertSame('POST', $request->method);
            self::assertSame('https://api.zoya.test/public/v1/leads', $request->uri);
            self::assertSame('application/json', $request->headers['Content-Type'] ?? null);
            self::assertSame('{"contact_name":"Jan Kowalski","contact_email":"jan@example.com","sales_channel_id":"sch_123","lead_source_id":"lso_123","interested_product_id":"prd_123"}', $request->body);

            return true;
        });
    }
}
