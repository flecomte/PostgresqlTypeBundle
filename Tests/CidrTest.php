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

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        Cidr::addType('cidr', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Cidr');
        self::$cidrType = Cidr::getType('cidr');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('cidr', 'cidr');
    }

    public function testConvertToDatabaseValue()
    {
        $ip = '192.168.0.10';

        $sqlCidr = self::$cidrType->convertToDatabaseValue($ip, self::$platform);

        $this->assertEquals($ip, $sqlCidr, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $ip = null;

        $sqlCidr = self::$cidrType->convertToDatabaseValue($ip, self::$platform);

        $this->assertNull($ip, $sqlCidr, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $ip = '192.168.0.10';

        $phpCidr = self::$cidrType->convertToPHPValue($ip, self::$platform);
        $this->assertEquals($ip, $phpCidr, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $ip = null;

        $phpCidr = self::$cidrType->convertToPHPValue($ip, self::$platform);
        $this->assertNull($ip, $phpCidr, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('cidr', self::$cidrType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $this->assertEquals('cidr', self::$cidrType->getSQLDeclaration([], self::$platform), 'SQL declaration is not correct');
    }
}
