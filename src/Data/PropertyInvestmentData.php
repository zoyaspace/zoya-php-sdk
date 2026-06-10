<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

use Zoya\Sdk\Contracts\FromArray;

final readonly class PropertyInvestmentData implements FromArray
{
    public function __construct(
        public string $id,
        public string $type,
        public string $name,
        public ?string $status,
        public ?string $organizationId,
        public ?string $organizationName,
        public ?string $city,
        public ?string $street,
        public ?string $buildingNo,
        public ?string $postalCode,
        public ?string $startDate,
        public ?string $endDate,
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
            organizationId: isset($data['organization_id']) ? (string) $data['organization_id'] : null,
            organizationName: isset($data['organization_name']) ? (string) $data['organization_name'] : null,
            city: isset($data['city']) ? (string) $data['city'] : null,
            street: isset($data['street']) ? (string) $data['street'] : null,
            buildingNo: isset($data['building_no']) ? (string) $data['building_no'] : null,
            postalCode: isset($data['postal_code']) ? (string) $data['postal_code'] : null,
            startDate: isset($data['start_date']) ? (string) $data['start_date'] : null,
            endDate: isset($data['end_date']) ? (string) $data['end_date'] : null,
            createdAt: isset($data['created_at']) ? (string) $data['created_at'] : null,
            updatedAt: isset($data['updated_at']) ? (string) $data['updated_at'] : null,
        );
    }
}
