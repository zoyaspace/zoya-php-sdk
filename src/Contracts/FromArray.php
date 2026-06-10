<?php

declare(strict_types=1);

namespace Zoya\Sdk\Contracts;

interface FromArray
{
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): static;
}
