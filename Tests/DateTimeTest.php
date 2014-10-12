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

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        DateTime::overrideType('datetime', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime');
        self::$datetimeType = DateTime::getType('datetime');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('datetime', 'datetime');
    }

    public function testConvertToDatabaseValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01');

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertEquals('2005-08-15 15:52:01.000000', $sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $datetime = null;

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertNull($sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsString()
    {
        $datetime = '2005-08-15T15:52:01';

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, self::$platform);

        $this->assertEquals('2005-08-15 15:52:01.000000', $sqlDateTime, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsStringBadFormat()
    {
        $datetime = 'qsdf qsdf qsdf qsdf ';

        $this->setExpectedException(
            'Exception', 'Date "'.$datetime.'" is not a valid date'
        );

        $sqlDateTime = self::$datetimeType->convertToDatabaseValue($datetime, self::$platform);
    }

    public function testConvertToPHPValue()
    {
        $datetime = new \DateTime('2005-08-15T15:52:01');

        $sqlDateTime = self::$datetimeType->convertToPHPValue('2005-08-15 15:52:01.000000', self::$platform);
        $this->assertEquals($datetime, $sqlDateTime, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $sqlDateTime = self::$datetimeType->convertToPHPValue(null, self::$platform);
        $this->assertNull($sqlDateTime, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsBadFormat()
    {
        $value = 'pokpokpok';
        $this->setExpectedException(
            'Exception', "Could not convert database value \"$value\" to Doctrine Type datetime. Expected format: Y-m-d H:i:s.u"
        );
        $sqlDateTime = self::$datetimeType->convertToPHPValue($value, self::$platform);
        $this->assertNull($sqlDateTime, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('datetime', self::$datetimeType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $this->assertEquals('TIMESTAMP(0) WITHOUT TIME ZONE', self::$datetimeType->getSQLDeclaration([], self::$platform), 'SQL declaration is not correct');
    }
}
