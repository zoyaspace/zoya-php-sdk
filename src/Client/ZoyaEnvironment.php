<?php

declare(strict_types=1);

namespace Zoya\Sdk\Client;

enum ZoyaEnvironment: string
{
    case Production = 'prod';
    case Development = 'dev';

    public function apiHost(): string
    {
        return match ($this) {
            self::Production => 'https://api.zoyaspace.com',
            self::Development => 'https://api-dev.zoyaspace.com',
        };
    }
}
