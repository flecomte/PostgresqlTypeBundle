<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt;

class ArrayIntTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayInt
     */
    protected static $arrayNumericType;

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        ArrayInt::addType('integer[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt');
        self::$arrayNumericType = ArrayInt::getType('integer[]');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('integer[]', 'integer[]');
    }

    public function testConvertToDatabaseValue()
    {
        $platform = new PostgreSqlPlatform();
        $array = array(1, 2, 3, 4, 5.5, 999);

        $sqlArray = self::$arrayNumericType->convertToDatabaseValue($array, self::$platform);

        $this->assertEquals('{1, 2, 3, 4, 5.5, 999}', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $array = null;

        $sqlArray = self::$arrayNumericType->convertToDatabaseValue($array, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueException()
    {
        $this->setExpectedException(
            'Exception', 'not numeric!'
        );

        $array = array('plip', 'plop');

        $sqlArray = self::$arrayNumericType->convertToDatabaseValue($array, self::$platform);
    }

    public function testConvertToPHPValue()
    {
        $array = array(1, 2, 3, 4, 5.5, 999);

        $phpArray = self::$arrayNumericType->convertToPHPValue('{1, 2, 3, 4, 5.5, 999}', self::$platform);
        $this->assertEquals($array, $phpArray, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $phpArray = self::$arrayNumericType->convertToPHPValue(null, self::$platform);
        $this->assertNull($phpArray, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('integer[]', self::$arrayNumericType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $array = array(1, 2, 3, 4, 5.5, 999);
        $this->assertEquals('integer[]', self::$arrayNumericType->getSQLDeclaration($array, self::$platform), 'SQL declaration is not correct');
    }
}
