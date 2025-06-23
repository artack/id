<?php

declare(strict_types=1);

namespace Artack\Tests\Id\Doctrine\Driver;

use Artack\Id\Doctrine\Driver\MariaDBDriverWrapperParentCopy;
use Doctrine\DBAL\Platforms\Exception\InvalidPlatformVersion;
use PHPUnit\Framework\TestCase;

final class MariaDBDriverWrapperParentCopyTest extends TestCase
{
    private object $traitObject;

    protected function setUp(): void
    {
        // Create an anonymous class that uses the trait
        $this->traitObject = new class() {
            use MariaDBDriverWrapperParentCopy;

            public function getVersionNumber(string $versionString): string
            {
                return $this->getMariaDbMysqlVersionNumber($versionString);
            }
        };
    }

    public function testGetMariaDbMysqlVersionNumberWithMariaDB(): void
    {
        $version = '10.11.0-MariaDB';

        $result = $this->traitObject->getVersionNumber($version);

        self::assertSame('10.11.0', $result);
    }

    public function testGetMariaDbMysqlVersionNumberWithPrefixedMariaDB(): void
    {
        $version = '5.5.5-10.11.0-MariaDB';

        $result = $this->traitObject->getVersionNumber($version);

        self::assertSame('10.11.0', $result);
    }

    public function testGetMariaDbMysqlVersionNumberWithMySQL(): void
    {
        $version = '8.0.0-MySQL';

        $result = $this->traitObject->getVersionNumber($version);

        self::assertSame('8.0.0', $result);
    }

    public function testGetMariaDbMysqlVersionNumberWithInvalidVersion(): void
    {
        $version = 'invalid-version';

        $this->expectException(InvalidPlatformVersion::class);

        $this->traitObject->getVersionNumber($version);
    }
}
