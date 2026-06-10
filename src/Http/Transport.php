<?php

declare(strict_types=1);

namespace Zoya\Sdk\Http;

interface Transport
{
    public function send(ApiRequest $request): ApiResponse;
}
