<?php

declare(strict_types=1);

namespace Artack\Id\Doctrine\Driver;

use Doctrine\DBAL\Driver;

final class MariaDBDriverMiddleware implements Driver\Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return new MariaDBDriverWrapper($driver);
    }
}
