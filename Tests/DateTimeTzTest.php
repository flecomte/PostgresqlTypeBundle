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

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        DateTimeTz::overrideType('datetimetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTimeTz');
        self::$datetimeType = DateTimeTz::getType('datetimetz');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('datetimetz', 'datetimetz');
    }

    public function testConvertToDatabaseValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01+01:00');

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertEquals('2005-08-15 15:52:01.000000+0100', $sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $datetime = null;

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertNull($sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01+01:00');

        $sqlDateTime = self::$datetimeType->convertToPHPValue('2005-08-15 15:52:01.000000+0100', self::$platform);
        $this->assertEquals($datetime, $sqlDateTime, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $sqlDateTime = self::$datetimeType->convertToPHPValue(null, self::$platform);
        $this->assertNull($sqlDateTime, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('datetimetz', self::$datetimeType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $this->assertEquals('TIMESTAMP(0) WITH TIME ZONE', self::$datetimeType->getSQLDeclaration([], self::$platform), 'SQL declaration is not correct');
    }
}
