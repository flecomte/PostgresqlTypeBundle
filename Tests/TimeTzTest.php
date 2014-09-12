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

    public static function setUpBeforeClass()
    {
        TimeTz::addType('timetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\TimeTz');
        self::$timeType = TimeTz::getType('timetz');
    }

    public function testConvertToDatabaseValue()
    {
        $datetime = \DateTime::createFromFormat('H:i:s.uP', '15:52:01.555555+05:30');

        $sqlTime = self::$timeType->convertToDatabaseValue($datetime, new PostgreSqlPlatform());

        $this->assertEquals('15:52:01.555555+0530', $sqlTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $datetime = null;

        $sqlTime = self::$timeType->convertToDatabaseValue($datetime, new PostgreSqlPlatform());

        $this->assertNull($sqlTime, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $datetime = \DateTime::createFromFormat('H:i:s.uP', '15:52:01.555555+05:30');

        $sqlTime = self::$timeType->convertToPHPValue('15:52:01.555555+0530', new PostgreSqlPlatform());
        $this->assertEquals($datetime->format('HisuO'), $sqlTime->format('HisuO'), 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueNoMicrosec()
    {
        $datetime = \DateTime::createFromFormat('H:i:sP', '15:52:01+05:30');

        $sqlTime = self::$timeType->convertToPHPValue('15:52:01.000000+05:30', new PostgreSqlPlatform());
        $this->assertEquals($datetime->format('HisuO'), $sqlTime->format('HisuO'), 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $sqlTime = self::$timeType->convertToPHPValue(null, new PostgreSqlPlatform());
        $this->assertNull($sqlTime, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $name = self::$timeType->getName();
        $this->assertRegExp('`^[a-zA-Z_]+$`', $name, 'PHP convertion is not correct');
    }
}
