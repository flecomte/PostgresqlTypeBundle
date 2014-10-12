<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Time;

class TimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Time
     */
    protected static $timeType;

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        Time::overrideType('time', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Time');
        self::$timeType = Time::getType('time');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('time', 'time');
    }

    public function testConvertToDatabaseValue()
    {
        $datetime = \DateTime::createFromFormat('H:i:s.u', '15:52:01.555555');

        $sqlTime = self::$timeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertEquals('15:52:01.555555', $sqlTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $datetime = null;

        $sqlTime = self::$timeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertNull($sqlTime, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $datetime = \DateTime::createFromFormat('H:i:s.u', '15:52:01.555555');

        $sqlTime = self::$timeType->convertToPHPValue('15:52:01.555555', self::$platform);
        $this->assertEquals($datetime->format('Hisu'), $sqlTime->format('Hisu'), 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueNoMicrosec()
    {
        $datetime = \DateTime::createFromFormat('H:i:s', '15:52:01');

        $sqlTime = self::$timeType->convertToPHPValue('15:52:01.000000', self::$platform);
        $this->assertEquals($datetime->format('Hisu'), $sqlTime->format('Hisu'), 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $sqlTime = self::$timeType->convertToPHPValue(null, self::$platform);
        $this->assertNull($sqlTime, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('time', self::$timeType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $this->assertEquals('time', self::$timeType->getSQLDeclaration([], self::$platform), 'SQL declaration is not correct');
    }
}
