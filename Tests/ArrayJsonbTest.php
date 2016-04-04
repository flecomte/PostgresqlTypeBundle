<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Jsonb;

class JsonbTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Jsonb
     */
    protected static $jsonType;

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        Jsonb::addType('jsonb', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Jsonb');
        self::$jsonType = Jsonb::getType('jsonb');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('jsonb', 'jsonb');
    }

    public function testConvertToDatabaseValue()
    {
        $value = array(1, 999, array('foo' => 'bar'));
        $sqlArray = self::$jsonType->convertToDatabaseValue($value, self::$platform);

        $this->assertEquals('[1,999,{"foo":"bar"}]', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $value = null;

        $sqlArray = self::$jsonType->convertToDatabaseValue($value, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $value = array(1, 999, array('foo' => 'bar'));

        $phpValue = self::$jsonType->convertToPHPValue('[1,999,{"foo":"bar"}]', self::$platform);
        $this->assertEquals($value, $phpValue, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $phpValue = self::$jsonType->convertToPHPValue(null, self::$platform);
        $this->assertNull($phpValue, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('jsonb', self::$jsonType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $value = array(1, 999, array('foo' => 'bar'));
        $this->assertEquals('jsonb', self::$jsonType->getSQLDeclaration($value, self::$platform), 'SQL declaration is not correct');
    }
}
