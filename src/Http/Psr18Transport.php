<?php

declare(strict_types=1);

namespace Zoya\Sdk\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Zoya\Sdk\Exceptions\AuthenticationException;
use Zoya\Sdk\Exceptions\RateLimitException;
use Zoya\Sdk\Exceptions\TransportException;
use Zoya\Sdk\Exceptions\ValidationException;

final readonly class Psr18Transport implements Transport
{
    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
    ) {
    }

    public function send(ApiRequest $request): ApiResponse
    {
        $psrRequest = $this->requestFactory->createRequest($request->method, $request->uri);

        foreach ($request->headers as $header => $value) {
            $psrRequest = $psrRequest->withHeader($header, $value);
        }

        if ($request->body !== null) {
            $psrRequest = $psrRequest->withBody($this->streamFactory->createStream($request->body));
        }

        try {
            $psrResponse = $this->httpClient->sendRequest($psrRequest);
        } catch (ClientExceptionInterface $exception) {
            throw new TransportException('The HTTP request to the API failed.', previous: $exception);
        }

        $headers = [];

        foreach ($psrResponse->getHeaders() as $header => $values) {
            $headers[$header] = implode(', ', $values);
        }

        $response = new ApiResponse(
            statusCode: $psrResponse->getStatusCode(),
            headers: $headers,
            body: (string) $psrResponse->getBody(),
        );

        if ($response->successful()) {
            return $response;
        }

        $message = 'The API request failed with status ' . $response->statusCode . '.';
        $decodedBody = $this->tryDecodeJson($response);

        if (is_array($decodedBody) && isset($decodedBody['message']) && is_string($decodedBody['message'])) {
            $message = $decodedBody['message'];
        }

        return match ($response->statusCode) {
            401, 403 => throw new AuthenticationException($message, $response),
            422 => throw new ValidationException(
                $message,
                is_array($decodedBody['errors'] ?? null) ? $decodedBody['errors'] : [],
                $response,
            ),
            429 => throw new RateLimitException($message, $response),
            default => throw new TransportException($message, $response),
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    private function tryDecodeJson(ApiResponse $response): ?array
    {
        if ($response->body === '') {
            return null;
        }

        try {
            $decoded = $response->json();
        } catch (TransportException) {
            return null;
        }

        return is_array($decoded) ? $decoded : null;
    }
}
