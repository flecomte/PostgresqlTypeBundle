<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateTime
     */
    protected static $datetimeType;

    public static function setUpBeforeClass()
    {
        DateTime::overrideType('datetime', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime');
        self::$datetimeType = DateTime::getType('datetime');
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
