<?php

declare(strict_types=1);

namespace Artack\Id\Doctrine\Driver;

use Doctrine\DBAL\Platforms\Exception\InvalidPlatformVersion;

/**
 * Copy from: Doctrine\DBAL\Driver\AbstractMySQLDriver class.
 */
trait MariaDBDriverWrapperParentCopy
{
    private function getMariaDbMysqlVersionNumber(string $versionString): string
    {
        if (
            1 !== preg_match(
                '/^(?:5\.5\.5-)?(mariadb-)?(?P<major>\d+)\.(?P<minor>\d+)\.(?P<patch>\d+)/i',
                $versionString,
                $versionParts,
            )
        ) {
            throw InvalidPlatformVersion::new(
                $versionString,
                '^(?:5\.5\.5-)?(mariadb-)?<major_version>.<minor_version>.<patch_version>',
            );
        }

        return $versionParts['major'].'.'.$versionParts['minor'].'.'.$versionParts['patch'];
    }
}
