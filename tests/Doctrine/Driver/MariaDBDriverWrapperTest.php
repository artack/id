<?php

declare(strict_types=1);

namespace Artack\Tests\Id\Doctrine\Driver;

use Artack\Id\Doctrine\Driver\MariaDBDriverWrapper;
use Artack\Id\Doctrine\Platform\MariaDB1011Platform;
use Doctrine\DBAL\Connection\StaticServerVersionProvider;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\Keywords\MySQL84Keywords;
use Doctrine\DBAL\Platforms\MariaDB1010Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Platforms\MySQL84Platform;
use Doctrine\DBAL\ServerVersionProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MariaDBDriverWrapperTest extends TestCase
{
    private Driver $innerDriver;
    private MariaDBDriverWrapper $wrapper;

    protected function setUp(): void
    {
        $this->innerDriver = $this->createMock(Driver::class);
        $this->wrapper = new MariaDBDriverWrapper($this->innerDriver);
    }


    public static function serverVersionProvider(): array
    {
        return [
            ['10.11.1-MariaDB', MariaDB1011Platform::class],
            ['10.10.1-MariaDB', MariaDB1010Platform::class],
            ['8.0.1-MySQL', MySQL80Platform::class],
            ['8.4.1-MySQL', MySQL84Platform::class],
        ];
    }

    #[DataProvider('serverVersionProvider')]
    public function testGetDatabasePlatformFromVersion(string $version, string $expectedPlattformClass): void
    {
        $platform = $this->wrapper->getDatabasePlatform(new StaticServerVersionProvider($version));
        dump($version, $platform);

        self::assertInstanceOf($expectedPlattformClass, $platform);
    }

//    public function testGetDatabasePlatformWithMariaDB1010(): void
//    {
//        // Create a mock for the parent platform that would be returned
//        $parentPlatform = new MariaDB1010Platform();
//
//        // Create a mock for the inner driver that returns the parent platform
//        $innerDriver = $this->createMock(Driver::class);
//        $innerDriver->method('getDatabasePlatform')
//            ->willReturn($parentPlatform);
//
//        // Create a new wrapper with our mocked inner driver
//        $wrapper = new MariaDBDriverWrapper($innerDriver);
//
//        // Create a version provider that returns a version below 10.11.0
//        $versionProvider = $this->createMock(ServerVersionProvider::class);
//        $versionProvider->method('getServerVersion')->willReturn('10.10.0-MariaDB');
//
//        // This should call the parent method which returns the mocked platform
//        $platform = $wrapper->getDatabasePlatform($versionProvider);
//
//        // Since we can't directly test the parent call, we verify that the returned platform
//        // is not a MariaDB1011Platform instance
//        self::assertNotInstanceOf(MariaDB1011Platform::class, $platform);
//        self::assertInstanceOf(MariaDB1010Platform::class, $platform);
//    }
//
//    public function testGetDatabasePlatformWithNonMariaDB(): void
//    {
//        // Create a mock for the parent platform that would be returned
//        $parentPlatform = new MariaDB1010Platform();
//
//        // Create a mock for the inner driver that returns the parent platform
//        $innerDriver = $this->createMock(Driver::class);
//        $innerDriver->method('getDatabasePlatform')
//            ->willReturn($parentPlatform);
//
//        // Create a new wrapper with our mocked inner driver
//        $wrapper = new MariaDBDriverWrapper($innerDriver);
//
//        // Create a version provider that returns a non-MariaDB version
//        $versionProvider = $this->createMock(ServerVersionProvider::class);
//        $versionProvider->method('getServerVersion')->willReturn('8.0.0-MySQL');
//
//        // This should call the parent method which returns the mocked platform
//        $platform = $wrapper->getDatabasePlatform($versionProvider);
//
//        // Since we can't directly test the parent call, we verify that the returned platform
//        // is not a MariaDB1011Platform instance
//        self::assertNotInstanceOf(MariaDB1011Platform::class, $platform);
//        self::assertInstanceOf(MariaDB1010Platform::class, $platform);
//    }
}
