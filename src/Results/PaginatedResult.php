<?php

declare(strict_types=1);

namespace Zoya\Sdk\Results;

/**
 * @template T
 */
final readonly class PaginatedResult
{
    /**
     * @param list<T> $items
     */
    public function __construct(
        public array $items,
        public PaginationMeta $meta,
    ) {
    }
}
