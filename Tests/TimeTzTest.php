<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\TimeTz;

class TimeTzTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TimeTz
     */
    protected static $timeType;

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        TimeTz::addType('timetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\TimeTz');
        self::$timeType = TimeTz::getType('timetz');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('timetz', 'timetz');
    }

    public function testConvertToDatabaseValue()
    {
        $datetime = \DateTime::createFromFormat('H:i:s.uP', '15:52:01.555555+05:30');

        $sqlTime = self::$timeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertEquals('15:52:01.555555+0530', $sqlTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $datetime = null;

        $sqlTime = self::$timeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertNull($sqlTime, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $datetime = \DateTime::createFromFormat('H:i:s.uP', '15:52:01.555555+05:30');

        $sqlTime = self::$timeType->convertToPHPValue('15:52:01.555555+0530', self::$platform);
        $this->assertEquals($datetime->format('HisuO'), $sqlTime->format('HisuO'), 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueNoMicrosec()
    {
        $datetime = \DateTime::createFromFormat('H:i:sP', '15:52:01+05:30');

        $sqlTime = self::$timeType->convertToPHPValue('15:52:01.000000+05:30', self::$platform);
        $this->assertEquals($datetime->format('HisuO'), $sqlTime->format('HisuO'), 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $sqlTime = self::$timeType->convertToPHPValue(null, self::$platform);
        $this->assertNull($sqlTime, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('timetz', self::$timeType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $this->assertEquals('timetz', self::$timeType->getSQLDeclaration([], self::$platform), 'SQL declaration is not correct');
    }
}
