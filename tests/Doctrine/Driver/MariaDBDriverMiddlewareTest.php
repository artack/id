<?php

declare(strict_types=1);

namespace Artack\Tests\Id\Doctrine\Driver;

use Artack\Id\Doctrine\Driver\MariaDBDriverMiddleware;
use Artack\Id\Doctrine\Driver\MariaDBDriverWrapper;
use Doctrine\DBAL\Driver;
use PHPUnit\Framework\TestCase;

final class MariaDBDriverMiddlewareTest extends TestCase
{
    public function testWrap(): void
    {
        $driver = $this->createStub(Driver::class);
        $middleware = new MariaDBDriverMiddleware();

        $wrappedDriver = $middleware->wrap($driver);

        self::assertInstanceOf(MariaDBDriverWrapper::class, $wrappedDriver);
    }
}