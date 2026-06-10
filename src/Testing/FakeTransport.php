<?php

declare(strict_types=1);

namespace Zoya\Sdk\Testing;

use Closure;
use RuntimeException;
use Zoya\Sdk\Http\ApiRequest;
use Zoya\Sdk\Http\ApiResponse;
use Zoya\Sdk\Http\Transport;

final class FakeTransport implements Transport
{
    /**
     * @var list<ApiRequest>
     */
    private array $requests = [];

    /**
     * @param Closure(ApiRequest): ApiResponse $handler
     */
    public function __construct(
        private readonly Closure $handler,
    ) {
    }

    public function send(ApiRequest $request): ApiResponse
    {
        $this->requests[] = $request;

        return ($this->handler)($request);
    }

    /**
     * @return list<ApiRequest>
     */
    public function requests(): array
    {
        return $this->requests;
    }

    public function assertSent(callable $predicate): void
    {
        foreach ($this->requests as $request) {
            if ($predicate($request) === true) {
                return;
            }
        }

        throw new RuntimeException('No matching request was sent through the fake transport.');
    }
}
