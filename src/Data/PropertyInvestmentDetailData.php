<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

final readonly class PropertyInvestmentDetailData
{
    /**
     * @param list<string> $organizationIds
     * @param list<array<string, mixed>> $organizations
     * @param array<string, int> $availabilityUnitsByStatus
     * @param array<string, string|null> $availabilityValueByStatus
     * @param list<array<string, mixed>> $unitTypeBreakdown
     */
    public function __construct(
        public PropertyInvestmentData $investment,
        public ?string $slug,
        public ?string $listSignature,
        public ?string $distinction,
        public ?string $code,
        public ?string $description,
        public ?string $metaTitle,
        public ?string $metaDescription,
        public array $organizationIds,
        public array $organizations,
        public ?string $investorOrganizationId,
        public ?string $investorOrganizationName,
        public ?string $investorOrganizationSystemName,
        public ?string $province,
        public ?string $county,
        public ?string $commune,
        public ?string $district,
        public ?string $countryCode,
        public ?string $latitude,
        public ?string $longitude,
        public ?string $locationDescription,
        public ?string $googleMapsUrl,
        public int $unitsCount,
        public int $availabilityUnitsCount,
        public array $availabilityUnitsByStatus,
        public array $availabilityValueByStatus,
        public ?string $unitsCurrencyCode,
        public ?string $totalUnitsValueAmount,
        public ?string $totalUnitsUsableArea,
        public ?string $averagePricePerSquareMeterAmount,
        public ?string $soldUnitsValueAmount,
        public array $unitTypeBreakdown,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            investment: PropertyInvestmentData::fromArray($data),
            slug: isset($data['slug']) ? (string) $data['slug'] : null,
            listSignature: isset($data['list_signature']) ? (string) $data['list_signature'] : null,
            distinction: isset($data['distinction']) ? (string) $data['distinction'] : null,
            code: isset($data['code']) ? (string) $data['code'] : null,
            description: isset($data['description']) ? (string) $data['description'] : null,
            metaTitle: isset($data['meta_title']) ? (string) $data['meta_title'] : null,
            metaDescription: isset($data['meta_description']) ? (string) $data['meta_description'] : null,
            organizationIds: self::normalizeStringList($data['organization_ids'] ?? []),
            organizations: self::normalizeObjectList($data['organizations'] ?? []),
            investorOrganizationId: isset($data['investor_organization_id']) ? (string) $data['investor_organization_id'] : null,
            investorOrganizationName: isset($data['investor_organization_name']) ? (string) $data['investor_organization_name'] : null,
            investorOrganizationSystemName: isset($data['investor_organization_system_name']) ? (string) $data['investor_organization_system_name'] : null,
            province: isset($data['province']) ? (string) $data['province'] : null,
            county: isset($data['county']) ? (string) $data['county'] : null,
            commune: isset($data['commune']) ? (string) $data['commune'] : null,
            district: isset($data['district']) ? (string) $data['district'] : null,
            countryCode: isset($data['country_code']) ? (string) $data['country_code'] : null,
            latitude: isset($data['latitude']) ? (string) $data['latitude'] : null,
            longitude: isset($data['longitude']) ? (string) $data['longitude'] : null,
            locationDescription: isset($data['location_description']) ? (string) $data['location_description'] : null,
            googleMapsUrl: isset($data['google_maps_url']) ? (string) $data['google_maps_url'] : null,
            unitsCount: (int) ($data['units_count'] ?? 0),
            availabilityUnitsCount: (int) ($data['availability_units_count'] ?? 0),
            availabilityUnitsByStatus: self::normalizeIntMap($data['availability_units_by_status'] ?? []),
            availabilityValueByStatus: self::normalizeNullableStringMap($data['availability_value_by_status'] ?? []),
            unitsCurrencyCode: isset($data['units_currency_code']) ? (string) $data['units_currency_code'] : null,
            totalUnitsValueAmount: isset($data['total_units_value_amount']) ? (string) $data['total_units_value_amount'] : null,
            totalUnitsUsableArea: isset($data['total_units_usable_area']) ? (string) $data['total_units_usable_area'] : null,
            averagePricePerSquareMeterAmount: isset($data['average_price_per_square_meter_amount']) ? (string) $data['average_price_per_square_meter_amount'] : null,
            soldUnitsValueAmount: isset($data['sold_units_value_amount']) ? (string) $data['sold_units_value_amount'] : null,
            unitTypeBreakdown: self::normalizeObjectList($data['unit_type_breakdown'] ?? []),
        );
    }

    /**
     * @param mixed $value
     * @return list<string>
     */
    private static function normalizeStringList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_map(static fn ($item): string => (string) $item, $value));
    }

    /**
     * @param mixed $value
     * @return list<array<string, mixed>>
     */
    private static function normalizeObjectList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, static fn ($item): bool => is_array($item)));
    }

    /**
     * @param mixed $value
     * @return array<string, int>
     */
    private static function normalizeIntMap(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];
        foreach ($value as $key => $item) {
            $result[(string) $key] = (int) $item;
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @return array<string, string|null>
     */
    private static function normalizeNullableStringMap(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];
        foreach ($value as $key => $item) {
            $result[(string) $key] = $item === null ? null : (string) $item;
        }

        return $result;
    }
}
