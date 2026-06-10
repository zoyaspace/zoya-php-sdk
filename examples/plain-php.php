<?php

declare(strict_types=1);

use Zoya\Sdk\Client\ZoyaClientFactory;
use Zoya\Sdk\Client\ZoyaEnvironment;

require __DIR__ . '/../vendor/autoload.php';

$apiToken = getenv('ZOYA_API_TOKEN');

if (! is_string($apiToken) || $apiToken === '') {
    throw new RuntimeException('Set the ZOYA_API_TOKEN environment variable before running this example.');
}

$client = ZoyaClientFactory::make(
    apiToken: $apiToken,
    environment: ZoyaEnvironment::Production,
    apiVersion: 'v1',
);

$response = $client->listLeadSources();

var_dump($response->json());
