<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

final readonly class PropertyBuildingDetailData
{
    /**
     * @param list<string> $organizationIds
     * @param list<string> $organizationNames
     * @param array<string, int> $availabilityUnitsByStatus
     */
    public function __construct(
        public PropertyBuildingData $building,
        public array $organizationIds,
        public array $organizationNames,
        public int $availabilityUnitsCount,
        public array $availabilityUnitsByStatus,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $availabilityUnitsByStatus = [];
        foreach ((array) ($data['availability_units_by_status'] ?? []) as $key => $value) {
            $availabilityUnitsByStatus[(string) $key] = (int) $value;
        }

        return new self(
            building: PropertyBuildingData::fromArray($data),
            organizationIds: array_values(array_map(static fn ($item): string => (string) $item, (array) ($data['organization_ids'] ?? []))),
            organizationNames: array_values(array_map(static fn ($item): string => (string) $item, (array) ($data['organization_names'] ?? []))),
            availabilityUnitsCount: (int) ($data['availability_units_count'] ?? 0),
            availabilityUnitsByStatus: $availabilityUnitsByStatus,
        );
    }
}
