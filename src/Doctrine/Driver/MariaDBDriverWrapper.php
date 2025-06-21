<?php

declare(strict_types=1);

namespace Artack\Id\Doctrine\Driver;

use Artack\Id\Doctrine\Platform\MariaDB1011Platform;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\ServerVersionProvider;

final class MariaDBDriverWrapper extends AbstractDriverMiddleware
{
    use MariaDBDriverWrapperParentCopy;

    public function getDatabasePlatform(ServerVersionProvider $versionProvider): AbstractPlatform
    {
        $version = $versionProvider->getServerVersion();
        if (false !== mb_stripos($version, 'mariadb')) {
            $mariaDbVersion = $this->getMariaDbMysqlVersionNumber($version);
            if (version_compare($mariaDbVersion, '10.11.0', '>=')) {
                return new MariaDB1011Platform();
            }
        }

        return parent::getDatabasePlatform($versionProvider);
    }
}
