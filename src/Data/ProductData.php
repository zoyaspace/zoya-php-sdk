<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

use Zoya\Sdk\Contracts\FromArray;

final readonly class ProductData implements FromArray
{
    public function __construct(
        public string $id,
        public string $type,
        public ?string $organizationId,
        public ?string $organizationName,
        public string $name,
        public ?string $slug,
        public ?string $sku,
        public ?string $categoryId,
        public ?string $categoryName,
        public ?string $salesChannelId,
        public ?string $salesChannelName,
        public ?string $productType,
        public ?string $status,
        public ?string $availabilityStatus,
        public ?string $currencyCode,
        public ?string $priceAmount,
        public ?string $promotionalPriceAmount,
        public bool $hasVariants,
        public bool $isAvailable,
        public ?int $availableQuantity,
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
            slug: isset($data['slug']) ? (string) $data['slug'] : null,
            sku: isset($data['sku']) ? (string) $data['sku'] : null,
            categoryId: isset($data['category_id']) ? (string) $data['category_id'] : null,
            categoryName: isset($data['category_name']) ? (string) $data['category_name'] : null,
            salesChannelId: isset($data['sales_channel_id']) ? (string) $data['sales_channel_id'] : null,
            salesChannelName: isset($data['sales_channel_name']) ? (string) $data['sales_channel_name'] : null,
            productType: isset($data['product_type']) ? (string) $data['product_type'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
            availabilityStatus: isset($data['availability_status']) ? (string) $data['availability_status'] : null,
            currencyCode: isset($data['currency_code']) ? (string) $data['currency_code'] : null,
            priceAmount: isset($data['price_amount']) ? (string) $data['price_amount'] : null,
            promotionalPriceAmount: isset($data['promotional_price_amount']) ? (string) $data['promotional_price_amount'] : null,
            hasVariants: (bool) ($data['has_variants'] ?? false),
            isAvailable: (bool) ($data['is_available'] ?? false),
            availableQuantity: isset($data['available_quantity']) ? (int) $data['available_quantity'] : null,
            createdAt: isset($data['created_at']) ? (string) $data['created_at'] : null,
            updatedAt: isset($data['updated_at']) ? (string) $data['updated_at'] : null,
        );
    }
}
