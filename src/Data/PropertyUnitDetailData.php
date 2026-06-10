<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

final readonly class PropertyUnitDetailData
{
    /**
     * @param list<array<string, mixed>> $presentedPriceBreakdown
     */
    public function __construct(
        public PropertyUnitData $unit,
        public ?string $propertyUnitTypeId,
        public ?string $propertyUnitTypeName,
        public ?string $totalArea,
        public ?string $gardenArea,
        public ?string $terraceArea,
        public ?string $balconyArea,
        public ?string $descriptionHtml,
        public ?string $presentedPriceAmount,
        public ?string $presentedPricePerSquareMeterAmount,
        public array $presentedPriceBreakdown,
        public bool $isForSale,
        public bool $isForRent,
        public ?string $promotionalPriceAmount,
        public ?string $promotionalPriceStartAt,
        public ?string $promotionalPriceEndAt,
        public bool $hasPromotionalPrice,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            unit: PropertyUnitData::fromArray($data),
            propertyUnitTypeId: isset($data['property_unit_type_id']) ? (string) $data['property_unit_type_id'] : null,
            propertyUnitTypeName: isset($data['property_unit_type_name']) ? (string) $data['property_unit_type_name'] : null,
            totalArea: isset($data['total_area']) ? (string) $data['total_area'] : null,
            gardenArea: isset($data['garden_area']) ? (string) $data['garden_area'] : null,
            terraceArea: isset($data['terrace_area']) ? (string) $data['terrace_area'] : null,
            balconyArea: isset($data['balcony_area']) ? (string) $data['balcony_area'] : null,
            descriptionHtml: isset($data['description_html']) ? (string) $data['description_html'] : null,
            presentedPriceAmount: isset($data['presented_price_amount']) ? (string) $data['presented_price_amount'] : null,
            presentedPricePerSquareMeterAmount: isset($data['presented_price_per_square_meter_amount']) ? (string) $data['presented_price_per_square_meter_amount'] : null,
            presentedPriceBreakdown: self::normalizeObjectList($data['presented_price_breakdown'] ?? []),
            isForSale: (bool) ($data['is_for_sale'] ?? false),
            isForRent: (bool) ($data['is_for_rent'] ?? false),
            promotionalPriceAmount: isset($data['promotional_price_amount']) ? (string) $data['promotional_price_amount'] : null,
            promotionalPriceStartAt: isset($data['promotional_price_start_at']) ? (string) $data['promotional_price_start_at'] : null,
            promotionalPriceEndAt: isset($data['promotional_price_end_at']) ? (string) $data['promotional_price_end_at'] : null,
            hasPromotionalPrice: (bool) ($data['has_promotional_price'] ?? false),
        );
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
}
