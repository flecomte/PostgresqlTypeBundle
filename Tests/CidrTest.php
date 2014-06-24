<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Cidr;

class CidrTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cidr
     */
    protected static $cidrType;

    public static function setUpBeforeClass()
    {
        Cidr::addType('cidr', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Cidr');
        self::$cidrType = Cidr::getType('cidr');
    }

    public function testConvertToDatabaseValue()
    {
        $platform = new PostgreSqlPlatform();
        $ip = '192.168.0.10';

        $sqlCidr = self::$cidrType->convertToDatabaseValue($ip, $platform);

        $this->assertEquals($ip, $sqlCidr, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $platform = new PostgreSqlPlatform();
        $ip = null;

        $sqlCidr = self::$cidrType->convertToDatabaseValue($ip, $platform);

        $this->assertNull($ip, $sqlCidr, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $platform = new PostgreSqlPlatform();
        $ip = '192.168.0.10';

        $phpCidr = self::$cidrType->convertToPHPValue($ip, $platform);
        $this->assertEquals($ip, $phpCidr, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $platform = new PostgreSqlPlatform();
        $ip = null;

        $phpCidr = self::$cidrType->convertToPHPValue($ip, $platform);
        $this->assertNull($ip, $phpCidr, 'PHP convertion is not correct');
    }
}
