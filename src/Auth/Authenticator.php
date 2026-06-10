<?php

declare(strict_types=1);

namespace Zoya\Sdk\Auth;

interface Authenticator
{
    /**
     * @param array<string, string> $headers
     *
     * @return array<string, string>
     */
    public function authenticate(array $headers): array;
}
