<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

use Zoya\Sdk\Contracts\FromArray;

final readonly class LeadData implements FromArray
{
    public function __construct(
        public string $id,
        public string $type,
        public ?string $organizationId,
        public ?string $organizationName,
        public ?string $salesChannelId,
        public ?string $salesChannelName,
        public ?string $leadSourceId,
        public ?string $leadSourceName,
        public ?string $interestedProductId,
        public ?string $interestedProductName,
        public ?string $interestedProductVariantId,
        public ?string $interestedProductVariantName,
        public ?string $interestedPropertyInvestmentId,
        public ?string $interestedPropertyInvestmentName,
        public ?string $interestedPropertyUnitId,
        public ?string $interestedPropertyUnitNumber,
        public ?string $contactName,
        public ?string $contactEmail,
        public ?string $contactPhone,
        public ?string $status,
        public ?string $message,
        public ?string $createdAt,
        public ?string $updatedAt,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new self(
            id: (string) $data['id'],
            type: (string) $data['type'],
            organizationId: self::nullableString($data, 'organization_id'),
            organizationName: self::nullableString($data, 'organization_name'),
            salesChannelId: self::nullableString($data, 'sales_channel_id'),
            salesChannelName: self::nullableString($data, 'sales_channel_name'),
            leadSourceId: self::nullableString($data, 'lead_source_id'),
            leadSourceName: self::nullableString($data, 'lead_source_name'),
            interestedProductId: self::nullableString($data, 'interested_product_id'),
            interestedProductName: self::nullableString($data, 'interested_product_name'),
            interestedProductVariantId: self::nullableString($data, 'interested_product_variant_id'),
            interestedProductVariantName: self::nullableString($data, 'interested_product_variant_name'),
            interestedPropertyInvestmentId: self::nullableString($data, 'interested_property_investment_id'),
            interestedPropertyInvestmentName: self::nullableString($data, 'interested_property_investment_name'),
            interestedPropertyUnitId: self::nullableString($data, 'interested_property_unit_id'),
            interestedPropertyUnitNumber: self::nullableString($data, 'interested_property_unit_number'),
            contactName: self::nullableString($data, 'contact_name'),
            contactEmail: self::nullableString($data, 'contact_email'),
            contactPhone: self::nullableString($data, 'contact_phone'),
            status: self::nullableString($data, 'status'),
            message: self::nullableString($data, 'message'),
            createdAt: self::nullableString($data, 'created_at'),
            updatedAt: self::nullableString($data, 'updated_at'),
        );
    }

    private static function nullableString(array $data, string $key): ?string
    {
        return isset($data[$key]) ? (string) $data[$key] : null;
    }
}
