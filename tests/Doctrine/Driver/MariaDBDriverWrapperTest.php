<?php

declare(strict_types=1);

namespace Artack\Tests\Id\Doctrine\Driver;

use Artack\Id\Doctrine\Driver\MariaDBDriverWrapper;
use Artack\Id\Doctrine\Platform\MariaDB1011Platform;
use Doctrine\DBAL\Connection\StaticServerVersionProvider;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver as PDOMySQLDriver;
use Doctrine\DBAL\Platforms\MariaDB1010Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Platforms\MySQL84Platform;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MariaDBDriverWrapperTest extends TestCase
{
    private MariaDBDriverWrapper $wrapper;

    protected function setUp(): void
    {
        $this->wrapper = new MariaDBDriverWrapper(new PDOMySQLDriver());
    }

    /**
     * @return iterable<string, array{string, class-string}>
     */
    public static function serverVersionProvider(): iterable
    {
        yield 'MariaDB >= 10.11 uses the custom platform' => ['10.11.1-MariaDB', MariaDB1011Platform::class];
        yield 'MariaDB 10.11.0 boundary uses the custom platform' => ['10.11.0-MariaDB', MariaDB1011Platform::class];
        yield 'MariaDB < 10.11 is delegated to the wrapped driver' => ['10.10.1-MariaDB', MariaDB1010Platform::class];
        yield 'MySQL 8.0 is delegated to the wrapped driver' => ['8.0.36', MySQL80Platform::class];
        yield 'MySQL 8.4 is delegated to the wrapped driver' => ['8.4.1', MySQL84Platform::class];
    }

    /**
     * @param class-string $expectedPlatformClass
     */
    #[DataProvider('serverVersionProvider')]
    public function testGetDatabasePlatformFromVersion(string $version, string $expectedPlatformClass): void
    {
        $platform = $this->wrapper->getDatabasePlatform(new StaticServerVersionProvider($version));

        self::assertInstanceOf($expectedPlatformClass, $platform);
    }
}
