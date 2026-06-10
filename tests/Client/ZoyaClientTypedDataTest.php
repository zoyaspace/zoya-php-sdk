<?php

declare(strict_types=1);

namespace Zoya\Sdk\Tests\Client;

use PHPUnit\Framework\TestCase;
use Zoya\Sdk\Auth\ApiKeyAuthenticator;
use Zoya\Sdk\Client\ZoyaClient;
use Zoya\Sdk\Client\ZoyaClientConfig;
use Zoya\Sdk\Client\ZoyaEnvironment;
use Zoya\Sdk\Data\LeadData;
use Zoya\Sdk\Data\LeadSourceData;
use Zoya\Sdk\Data\ProductDetailData;
use Zoya\Sdk\Data\PropertyBuildingDetailData;
use Zoya\Sdk\Data\PropertyInvestmentDetailData;
use Zoya\Sdk\Data\PropertyUnitDetailData;
use Zoya\Sdk\Data\SalesChannelData;
use Zoya\Sdk\Results\PaginatedResult;
use Zoya\Sdk\Testing\FakeTransport;

final class ZoyaClientTypedDataTest extends TestCase
{
    public function testItDecodesAPaginatedLeadSourceResult(): void
    {
        $client = $this->clientForPayload('{"data":[{"id":"lso_123","type":"lead_sources","organization_id":"org_123","organization_name":"Acme","name":"Meta Ads","status":"active","is_default":true,"created_at":"2026-06-10T12:00:00Z","updated_at":"2026-06-10T12:00:00Z"}],"meta":{"current_page":1,"from":1,"last_page":1,"path":"https://api.zoya.test/public/v1/lead-sources","per_page":50,"to":1,"total":1}}');

        $result = $client->listLeadSourcesPage();

        self::assertInstanceOf(PaginatedResult::class, $result);
        self::assertCount(1, $result->items);
        self::assertInstanceOf(LeadSourceData::class, $result->items[0]);
        self::assertSame('Meta Ads', $result->items[0]->name);
        self::assertSame(1, $result->meta->currentPage);
    }

    public function testItDecodesASalesChannelDetailResult(): void
    {
        $client = $this->clientForPayload('{"id":"sch_123","type":"sales_channels","organization_id":"org_123","organization_name":"Acme","name":"WWW","status":"active","is_default":false,"created_at":"2026-06-10T12:00:00Z","updated_at":"2026-06-10T12:00:00Z"}');

        $result = $client->getSalesChannelData('sch_123');

        self::assertInstanceOf(SalesChannelData::class, $result);
        self::assertSame('WWW', $result->name);
        self::assertSame('active', $result->status);
    }

    public function testItDecodesALeadCreationResult(): void
    {
        $client = $this->clientForPayload('{"id":"led_123","type":"leads","organization_id":"org_123","organization_name":"Acme","sales_channel_id":"sch_123","sales_channel_name":"WWW","lead_source_id":"lso_123","lead_source_name":"Meta Ads","interested_product_id":"prd_123","interested_product_name":"Abonament","interested_product_variant_id":null,"interested_product_variant_name":null,"interested_property_investment_id":null,"interested_property_investment_name":null,"interested_property_unit_id":null,"interested_property_unit_number":null,"contact_name":"Jan Kowalski","contact_email":"jan@example.com","contact_phone":"+48123456789","status":"new","message":"Dzień dobry","created_at":"2026-06-10T12:00:00Z","updated_at":"2026-06-10T12:00:00Z"}', 201);

        $result = $client->createLeadData([
            'contact_name' => 'Jan Kowalski',
        ]);

        self::assertInstanceOf(LeadData::class, $result);
        self::assertSame('Jan Kowalski', $result->contactName);
        self::assertSame('new', $result->status);
    }

    public function testItDecodesAProductDetailResult(): void
    {
        $client = $this->clientForPayload('{"id":"prd_123","type":"products","organization_id":"org_123","organization_name":"Acme","name":"Abonament","slug":"abonament","sku":"SKU-1","category_id":"cat_123","category_name":"Usługi","category_path":"Usługi / Premium","sales_channel_id":"sch_123","sales_channel_name":"WWW","description_html":"<p>Opis</p>","product_type":"service","status":"active","availability_status":"available","currency_code":"PLN","price_amount":"199.00","promotional_price_amount":"149.00","promotional_price_start_at":"2026-06-10T12:00:00Z","promotional_price_end_at":"2026-06-20T12:00:00Z","has_promotional_price":true,"has_variants":true,"is_available":true,"available_quantity":10,"variant_options":[{"name":"Plan"}],"variants":[{"id":"prv_123","name":"Premium"}],"created_at":"2026-06-10T12:00:00Z","updated_at":"2026-06-10T12:00:00Z"}');

        $result = $client->getProductData('prd_123');

        self::assertInstanceOf(ProductDetailData::class, $result);
        self::assertSame('Abonament', $result->product->name);
        self::assertSame('Usługi / Premium', $result->categoryPath);
        self::assertTrue($result->hasPromotionalPrice);
        self::assertCount(1, $result->variants);
    }

    public function testItDecodesAPropertyInvestmentDetailResult(): void
    {
        $client = $this->clientForPayload('{"id":"piv_123","type":"property_investments","name":"Osiedle Słoneczne","status":"active","organization_id":"org_123","organization_name":"Acme","city":"Warszawa","street":"Prosta","building_no":"1","postal_code":"00-001","start_date":"2026-01-01","end_date":"2026-12-31","slug":"osiedle-sloneczne","list_signature":"signature","distinction":"premium","code":"INV-1","description":"Opis","meta_title":"Meta","meta_description":"Meta desc","organization_ids":["org_123"],"organizations":[{"id":"org_123","name":"Acme","system_name":"ACME"}],"investor_organization_id":"org_999","investor_organization_name":"Investor","investor_organization_system_name":"INV","province":"mazowieckie","county":"Warszawa","commune":"Warszawa","district":"Centrum","country_code":"PL","latitude":"52.1","longitude":"21.0","location_description":"Świetna lokalizacja","google_maps_url":"https://maps.example","units_count":20,"availability_units_count":8,"availability_units_by_status":{"available":8,"sold":12},"availability_value_by_status":{"available":"1000000.00","sold":"2000000.00"},"units_currency_code":"PLN","total_units_value_amount":"3000000.00","total_units_usable_area":"1500.00","average_price_per_square_meter_amount":"2000.00","sold_units_value_amount":"2000000.00","unit_type_breakdown":[{"type":"mieszkanie","count":20}],"created_at":"2026-06-10T12:00:00Z","updated_at":"2026-06-10T12:00:00Z"}');

        $result = $client->getPropertyInvestmentData('piv_123');

        self::assertInstanceOf(PropertyInvestmentDetailData::class, $result);
        self::assertSame('Osiedle Słoneczne', $result->investment->name);
        self::assertSame(20, $result->unitsCount);
        self::assertSame(8, $result->availabilityUnitsByStatus['available']);
    }

    public function testItDecodesAPropertyBuildingDetailResult(): void
    {
        $client = $this->clientForPayload('{"id":"pbd_123","type":"property_buildings","name":"Budynek A","status":"active","property_investment_id":"piv_123","property_investment_name":"Osiedle","organization_ids":["org_123"],"organization_names":["Acme"],"units_count":12,"availability_units_count":5,"availability_units_by_status":{"available":5,"sold":7},"created_at":"2026-06-10T12:00:00Z","updated_at":"2026-06-10T12:00:00Z"}');

        $result = $client->getPropertyBuildingData('pbd_123');

        self::assertInstanceOf(PropertyBuildingDetailData::class, $result);
        self::assertSame('Budynek A', $result->building->name);
        self::assertSame(5, $result->availabilityUnitsCount);
    }

    public function testItDecodesAPropertyUnitDetailResult(): void
    {
        $client = $this->clientForPayload('{"id":"unt_123","type":"property_units","organization_id":"org_123","organization_name":"Acme","property_investment_id":"piv_123","property_investment_name":"Osiedle","property_building_id":"pbd_123","property_building_name":"Budynek A","property_unit_type_id":"put_123","property_unit_type_name":"Mieszkanie","number":"12A","status":"active","availability_status":"available","floor":3,"room_count":4,"usable_area":"65.50","total_area":"70.00","garden_area":"0.00","terrace_area":"6.50","balcony_area":"2.00","description_html":"<p>Opis</p>","currency_code":"PLN","price_amount":"650000.00","presented_price_amount":"650000.00","presented_price_per_square_meter_amount":"9923.66","presented_price_breakdown":[{"label":"Cena podstawowa","amount":"650000.00"}],"is_for_sale":true,"is_for_rent":false,"promotional_price_amount":"620000.00","promotional_price_start_at":"2026-06-10T12:00:00Z","promotional_price_end_at":"2026-06-20T12:00:00Z","has_promotional_price":true,"created_at":"2026-06-10T12:00:00Z","updated_at":"2026-06-10T12:00:00Z"}');

        $result = $client->getPropertyUnitData('unt_123');

        self::assertInstanceOf(PropertyUnitDetailData::class, $result);
        self::assertSame('12A', $result->unit->number);
        self::assertSame('Mieszkanie', $result->propertyUnitTypeName);
        self::assertTrue($result->isForSale);
        self::assertCount(1, $result->presentedPriceBreakdown);
    }

    private function clientForPayload(string $payload, int $statusCode = 200): ZoyaClient
    {
        $transport = new FakeTransport(static fn () => new \Zoya\Sdk\Http\ApiResponse(
            statusCode: $statusCode,
            headers: ['Content-Type' => 'application/json'],
            body: $payload,
        ));

        return new ZoyaClient(
            config: new ZoyaClientConfig(
                environment: ZoyaEnvironment::Production,
                baseUrlOverride: 'https://api.zoya.test/public/v1',
            ),
            transport: $transport,
            authenticator: new ApiKeyAuthenticator('zya_test.secret'),
        );
    }
}
