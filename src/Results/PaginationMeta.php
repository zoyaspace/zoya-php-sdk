<?php

declare(strict_types=1);

namespace Zoya\Sdk\Results;

use Zoya\Sdk\Contracts\FromArray;

final readonly class PaginationMeta implements FromArray
{
    public function __construct(
        public int $currentPage,
        public ?int $from,
        public int $lastPage,
        public string $path,
        public int $perPage,
        public ?int $to,
        public int $total,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new self(
            currentPage: (int) ($data['current_page'] ?? 1),
            from: isset($data['from']) ? (int) $data['from'] : null,
            lastPage: (int) ($data['last_page'] ?? 1),
            path: (string) ($data['path'] ?? ''),
            perPage: (int) ($data['per_page'] ?? 0),
            to: isset($data['to']) ? (int) $data['to'] : null,
            total: (int) ($data['total'] ?? 0),
        );
    }
}
