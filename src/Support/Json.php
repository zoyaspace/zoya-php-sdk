<?php

declare(strict_types=1);

namespace Zoya\Sdk\Support;

use JsonException;
use Zoya\Sdk\Exceptions\TransportException;

final class Json
{
    /**
     * @param array<string, mixed> $payload
     */
    public static function encode(array $payload): string
    {
        try {
            return json_encode($payload, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new TransportException('Failed to encode the request body as JSON.', previous: $exception);
        }
    }

    public static function decode(string $payload): mixed
    {
        try {
            return json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new TransportException('Failed to decode the API response JSON.', previous: $exception);
        }
    }
}
