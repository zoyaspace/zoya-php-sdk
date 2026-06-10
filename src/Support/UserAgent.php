<?php

declare(strict_types=1);

namespace Zoya\Sdk\Support;

use Composer\InstalledVersions;

final class UserAgent
{
    private const PACKAGE_NAME = 'zoyaspace/zoya-php-sdk';
    private const FALLBACK_VERSION = '0.1.0';

    public static function default(): string
    {
        $version = self::FALLBACK_VERSION;

        if (class_exists(InstalledVersions::class) && InstalledVersions::isInstalled(self::PACKAGE_NAME)) {
            $prettyVersion = InstalledVersions::getPrettyVersion(self::PACKAGE_NAME);

            if (is_string($prettyVersion) && $prettyVersion !== '') {
                $version = ltrim($prettyVersion, 'v');
            }
        }

        return sprintf('zoya-php-sdk/%s', $version);
    }
}
