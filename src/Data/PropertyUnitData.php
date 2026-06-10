<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

use Zoya\Sdk\Contracts\FromArray;

final readonly class PropertyUnitData implements FromArray
{
    public function __construct(
        public string $id,
        public string $type,
        public ?string $organizationId,
        public ?string $organizationName,
        public ?string $propertyInvestmentId,
        public ?string $propertyInvestmentName,
        public ?string $propertyBuildingId,
        public ?string $propertyBuildingName,
        public string $number,
        public ?string $status,
        public ?string $availabilityStatus,
        public ?int $floor,
        public ?int $roomCount,
        public ?string $usableArea,
        public ?string $currencyCode,
        public ?string $priceAmount,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new self(
            id: (string) $data['id'],
            type: (string) $data['type'],
            organizationId: isset($data['organization_id']) ? (string) $data['organization_id'] : null,
            organizationName: isset($data['organization_name']) ? (string) $data['organization_name'] : null,
            propertyInvestmentId: isset($data['property_investment_id']) ? (string) $data['property_investment_id'] : null,
            propertyInvestmentName: isset($data['property_investment_name']) ? (string) $data['property_investment_name'] : null,
            propertyBuildingId: isset($data['property_building_id']) ? (string) $data['property_building_id'] : null,
            propertyBuildingName: isset($data['property_building_name']) ? (string) $data['property_building_name'] : null,
            number: (string) $data['number'],
            status: isset($data['status']) ? (string) $data['status'] : null,
            availabilityStatus: isset($data['availability_status']) ? (string) $data['availability_status'] : null,
            floor: isset($data['floor']) ? (int) $data['floor'] : null,
            roomCount: isset($data['room_count']) ? (int) $data['room_count'] : null,
            usableArea: isset($data['usable_area']) ? (string) $data['usable_area'] : null,
            currencyCode: isset($data['currency_code']) ? (string) $data['currency_code'] : null,
            priceAmount: isset($data['price_amount']) ? (string) $data['price_amount'] : null,
            createdAt: isset($data['created_at']) ? (string) $data['created_at'] : null,
            updatedAt: isset($data['updated_at']) ? (string) $data['updated_at'] : null,
        );
    }
}
