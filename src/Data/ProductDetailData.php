<?php

declare(strict_types=1);

namespace Zoya\Sdk\Data;

final readonly class ProductDetailData
{
    /**
     * @param list<array<string, mixed>> $variantOptions
     * @param list<array<string, mixed>> $variants
     */
    public function __construct(
        public ProductData $product,
        public ?string $categoryPath,
        public ?string $descriptionHtml,
        public ?string $promotionalPriceStartAt,
        public ?string $promotionalPriceEndAt,
        public bool $hasPromotionalPrice,
        public array $variantOptions,
        public array $variants,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            product: ProductData::fromArray($data),
            categoryPath: isset($data['category_path']) ? (string) $data['category_path'] : null,
            descriptionHtml: isset($data['description_html']) ? (string) $data['description_html'] : null,
            promotionalPriceStartAt: isset($data['promotional_price_start_at']) ? (string) $data['promotional_price_start_at'] : null,
            promotionalPriceEndAt: isset($data['promotional_price_end_at']) ? (string) $data['promotional_price_end_at'] : null,
            hasPromotionalPrice: (bool) ($data['has_promotional_price'] ?? false),
            variantOptions: self::normalizeObjectList($data['variant_options'] ?? []),
            variants: self::normalizeObjectList($data['variants'] ?? []),
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
