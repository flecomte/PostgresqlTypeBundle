<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTimeTz;

class DateTimeTzTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateTimeTypeTz
     */
    protected static $datetimeType;

    public static function setUpBeforeClass()
    {
        DateTimeTz::overrideType('datetimetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTimeTz');
        self::$datetimeType = DateTimeTz::getType('datetimetz');
    }

    public function testConvertToDatabaseValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01+01:00');

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, new PostgreSqlPlatform());

        $this->assertEquals('2005-08-15 15:52:01.000000+0100', $sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $datetime = null;

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, new PostgreSqlPlatform());

        $this->assertNull($sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01+01:00');

        $sqlDateTime = self::$datetimeType->convertToPHPValue('2005-08-15 15:52:01.000000+0100', new PostgreSqlPlatform());
        $this->assertEquals($datetime, $sqlDateTime, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $sqlDateTime = self::$datetimeType->convertToPHPValue(null, new PostgreSqlPlatform());
        $this->assertNull($sqlDateTime, 'PHP convertion is not correct');
    }
}
