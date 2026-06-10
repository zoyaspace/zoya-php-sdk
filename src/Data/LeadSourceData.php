<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

use Zoya\Sdk\Contracts\FromArray;

final readonly class LeadSourceData implements FromArray
{
    public function __construct(
        public string $id,
        public string $type,
        public ?string $organizationId,
        public ?string $organizationName,
        public string $name,
        public ?string $status,
        public bool $isDefault,
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
            name: (string) $data['name'],
            status: isset($data['status']) ? (string) $data['status'] : null,
            isDefault: (bool) ($data['is_default'] ?? false),
            createdAt: isset($data['created_at']) ? (string) $data['created_at'] : null,
            updatedAt: isset($data['updated_at']) ? (string) $data['updated_at'] : null,
        );
    }
}
