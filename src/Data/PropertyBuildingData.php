<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

use Zoya\Sdk\Contracts\FromArray;

final readonly class PropertyBuildingData implements FromArray
{
    public function __construct(
        public string $id,
        public string $type,
        public string $name,
        public ?string $status,
        public ?string $propertyInvestmentId,
        public ?string $propertyInvestmentName,
        public int $unitsCount,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new self(
            id: (string) $data['id'],
            type: (string) $data['type'],
            name: (string) $data['name'],
            status: isset($data['status']) ? (string) $data['status'] : null,
            propertyInvestmentId: isset($data['property_investment_id']) ? (string) $data['property_investment_id'] : null,
            propertyInvestmentName: isset($data['property_investment_name']) ? (string) $data['property_investment_name'] : null,
            unitsCount: (int) ($data['units_count'] ?? 0),
            createdAt: isset($data['created_at']) ? (string) $data['created_at'] : null,
            updatedAt: isset($data['updated_at']) ? (string) $data['updated_at'] : null,
        );
    }
}
