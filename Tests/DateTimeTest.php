<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Types\DateTimeType;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateTimeType
     */
    protected static $datetimeType;

    public static function setUpBeforeClass()
    {
        DateTimeType::overrideType('datetime', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime');
        self::$datetimeType = DateTimeType::getType('datetime');
    }

    public function testConvertToDatabaseValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01');

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, new PostgreSqlPlatform());

        $this->assertEquals('2005-08-15 15:52:01.000000', $sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $datetime = null;

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, new PostgreSqlPlatform());

        $this->assertNull($sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01');

        $sqlDateTime = self::$datetimeType->convertToPHPValue('2005-08-15 15:52:01.000000', new PostgreSqlPlatform());
        $this->assertEquals($datetime, $sqlDateTime, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $sqlDateTime = self::$datetimeType->convertToPHPValue(null, new PostgreSqlPlatform());
        $this->assertNull($sqlDateTime, 'PHP convertion is not correct');
    }
}
