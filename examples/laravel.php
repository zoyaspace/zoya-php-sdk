<?php

declare(strict_types=1);

use Zoya\Sdk\Client\ZoyaClientFactory;
use Zoya\Sdk\Client\ZoyaEnvironment;

$client = ZoyaClientFactory::make(
    apiToken: config('services.zoya.api_token'),
    environment: app()->environment('production')
        ? ZoyaEnvironment::Production
        : ZoyaEnvironment::Development,
    apiVersion: 'v1',
    userAgent: 'zoya-laravel-app/0.1',
);

$response = $client->listPropertyInvestments([
    'page' => ['number' => 1, 'size' => 20],
]);

$payload = $response->json();
