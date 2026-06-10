<?php

declare(strict_types=1);

namespace Zoya\Sdk\Client;

use UnexpectedValueException;
use Zoya\Sdk\Contracts\FromArray;
use Zoya\Sdk\Data\LeadData;
use Zoya\Sdk\Data\LeadSourceData;
use Zoya\Sdk\Data\ProductData;
use Zoya\Sdk\Data\ProductDetailData;
use Zoya\Sdk\Data\PropertyBuildingData;
use Zoya\Sdk\Data\PropertyBuildingDetailData;
use Zoya\Sdk\Data\PropertyInvestmentData;
use Zoya\Sdk\Data\PropertyInvestmentDetailData;
use Zoya\Sdk\Data\PropertyUnitData;
use Zoya\Sdk\Data\PropertyUnitDetailData;
use Zoya\Sdk\Data\SalesChannelData;
use Zoya\Sdk\Auth\Authenticator;
use Zoya\Sdk\Http\ApiRequest;
use Zoya\Sdk\Http\ApiResponse;
use Zoya\Sdk\Http\Transport;
use Zoya\Sdk\Results\PaginatedResult;
use Zoya\Sdk\Results\PaginationMeta;
use Zoya\Sdk\Support\Json;

final readonly class ZoyaClient
{
    public function __construct(
        private ZoyaClientConfig $config,
        private Transport $transport,
        private ?Authenticator $authenticator = null,
    ) {
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     * @param array<string, string> $headers
     * @param array<string, mixed>|null $json
     */
    public function request(
        string $method,
        string $path,
        array $query = [],
        array $headers = [],
        ?array $json = null,
    ): ApiResponse {
        $normalizedHeaders = [
            'Accept' => $this->config->accept,
            'User-Agent' => $this->config->userAgent,
            ...$headers,
        ];

        if ($json !== null) {
            $normalizedHeaders['Content-Type'] = 'application/json';
        }

        if ($this->authenticator !== null) {
            $normalizedHeaders = $this->authenticator->authenticate($normalizedHeaders);
        }

        $request = new ApiRequest(
            method: strtoupper($method),
            uri: $this->buildUri($path, $query),
            headers: $normalizedHeaders,
            body: $json === null ? null : Json::encode($json),
        );

        return $this->transport->send($request);
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     */
    public function listPropertyInvestments(array $query = []): ApiResponse
    {
        return $this->request('GET', '/investments', $query);
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     *
     * @return PaginatedResult<PropertyInvestmentData>
     */
    public function listPropertyInvestmentsPage(array $query = []): PaginatedResult
    {
        return $this->decodePaginated(
            $this->listPropertyInvestments($query),
            PropertyInvestmentData::class,
        );
    }

    public function getPropertyInvestment(string $publicId): ApiResponse
    {
        return $this->request('GET', '/investments/' . rawurlencode($publicId));
    }

    public function getPropertyInvestmentData(string $publicId): PropertyInvestmentDetailData
    {
        return PropertyInvestmentDetailData::fromArray(
            $this->decodeItem($this->getPropertyInvestment($publicId)),
        );
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     */
    public function listProducts(array $query = []): ApiResponse
    {
        return $this->request('GET', '/products', $query);
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     *
     * @return PaginatedResult<ProductData>
     */
    public function listProductsPage(array $query = []): PaginatedResult
    {
        return $this->decodePaginated(
            $this->listProducts($query),
            ProductData::class,
        );
    }

    public function getProduct(string $publicId): ApiResponse
    {
        return $this->request('GET', '/products/' . rawurlencode($publicId));
    }

    public function getProductData(string $publicId): ProductDetailData
    {
        return ProductDetailData::fromArray(
            $this->decodeItem($this->getProduct($publicId)),
        );
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     */
    public function listPropertyBuildings(array $query = []): ApiResponse
    {
        return $this->request('GET', '/buildings', $query);
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     *
     * @return PaginatedResult<PropertyBuildingData>
     */
    public function listPropertyBuildingsPage(array $query = []): PaginatedResult
    {
        return $this->decodePaginated(
            $this->listPropertyBuildings($query),
            PropertyBuildingData::class,
        );
    }

    public function getPropertyBuilding(string $publicId): ApiResponse
    {
        return $this->request('GET', '/buildings/' . rawurlencode($publicId));
    }

    public function getPropertyBuildingData(string $publicId): PropertyBuildingDetailData
    {
        return PropertyBuildingDetailData::fromArray(
            $this->decodeItem($this->getPropertyBuilding($publicId)),
        );
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     */
    public function listPropertyUnits(array $query = []): ApiResponse
    {
        return $this->request('GET', '/units', $query);
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     *
     * @return PaginatedResult<PropertyUnitData>
     */
    public function listPropertyUnitsPage(array $query = []): PaginatedResult
    {
        return $this->decodePaginated(
            $this->listPropertyUnits($query),
            PropertyUnitData::class,
        );
    }

    public function getPropertyUnit(string $publicId): ApiResponse
    {
        return $this->request('GET', '/units/' . rawurlencode($publicId));
    }

    public function getPropertyUnitData(string $publicId): PropertyUnitDetailData
    {
        return PropertyUnitDetailData::fromArray(
            $this->decodeItem($this->getPropertyUnit($publicId)),
        );
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     */
    public function listLeadSources(array $query = []): ApiResponse
    {
        return $this->request('GET', '/lead-sources', $query);
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     *
     * @return PaginatedResult<LeadSourceData>
     */
    public function listLeadSourcesPage(array $query = []): PaginatedResult
    {
        return $this->decodePaginated(
            $this->listLeadSources($query),
            LeadSourceData::class,
        );
    }

    public function getLeadSource(string $publicId): ApiResponse
    {
        return $this->request('GET', '/lead-sources/' . rawurlencode($publicId));
    }

    public function getLeadSourceData(string $publicId): LeadSourceData
    {
        return LeadSourceData::fromArray($this->decodeItem($this->getLeadSource($publicId)));
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     */
    public function listSalesChannels(array $query = []): ApiResponse
    {
        return $this->request('GET', '/sales-channels', $query);
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     *
     * @return PaginatedResult<SalesChannelData>
     */
    public function listSalesChannelsPage(array $query = []): PaginatedResult
    {
        return $this->decodePaginated(
            $this->listSalesChannels($query),
            SalesChannelData::class,
        );
    }

    public function getSalesChannel(string $publicId): ApiResponse
    {
        return $this->request('GET', '/sales-channels/' . rawurlencode($publicId));
    }

    public function getSalesChannelData(string $publicId): SalesChannelData
    {
        return SalesChannelData::fromArray($this->decodeItem($this->getSalesChannel($publicId)));
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createLead(array $payload): ApiResponse
    {
        return $this->request('POST', '/leads', json: $payload);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createLeadData(array $payload): LeadData
    {
        return LeadData::fromArray($this->decodeItem($this->createLead($payload)));
    }

    /**
     * @param array<string, scalar|array<array-key, scalar|null>|null> $query
     */
    private function buildUri(string $path, array $query): string
    {
        $uri = str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : $this->config->baseUri() . '/' . ltrim($path, '/');

        if ($query === []) {
            return $uri;
        }

        $queryString = http_build_query($query, '', '&', PHP_QUERY_RFC3986);

        if ($queryString === '') {
            return $uri;
        }

        $separator = str_contains($uri, '?') ? '&' : '?';

        return $uri . $separator . $queryString;
    }

    /**
     * @template T of FromArray
     *
     * @param class-string<T> $dataClass
     *
     * @return PaginatedResult<T>
     */
    private function decodePaginated(ApiResponse $response, string $dataClass): PaginatedResult
    {
        $payload = $response->json();

        if (! is_array($payload) || ! isset($payload['data']) || ! is_array($payload['data']) || ! isset($payload['meta']) || ! is_array($payload['meta'])) {
            throw new UnexpectedValueException('Expected a paginated JSON response with data and meta objects.');
        }

        $items = array_map(
            static fn (array $item) => $dataClass::fromArray($item),
            $payload['data'],
        );

        return new PaginatedResult(
            items: array_values($items),
            meta: PaginationMeta::fromArray($payload['meta']),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeItem(ApiResponse $response): array
    {
        $payload = $response->json();

        if (! is_array($payload)) {
            throw new UnexpectedValueException('Expected a JSON object response.');
        }

        return $payload;
    }
}
