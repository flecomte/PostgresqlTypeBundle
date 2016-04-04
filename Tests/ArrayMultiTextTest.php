<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayMultiText;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;

class ArrayMultiTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayMultiText
     */
    protected static $arrayMultiTextType;

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        ArrayMultiText::addType('text[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayMultiText');
        self::$arrayMultiTextType = ArrayMultiText::getType('text[]');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('text[]', 'text[]');
    }

    public function testConvertToDatabaseValue()
    {
        $array = array("first" => "Hello", "last" => "World");

        $sqlArray = self::$arrayMultiTextType->convertToDatabaseValue($array, self::$platform);

        $this->assertEquals('{{"first", "Hello"}, {"last", "World"}}', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueException()
    {
        $this->setExpectedException(
            'Exception', 'multidimentional array!'
        );

        $array = array('plip' => ['tic' => 'tac'], 'plop');

        $sqlArray = self::$arrayMultiTextType->convertToDatabaseValue($array, self::$platform);
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $array = null;

        $sqlArray = self::$arrayMultiTextType->convertToDatabaseValue($array, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $array = array("first" => 1, "second" => 156.256, "third" => 'Hello', "last" => "World");

        $phpArray = self::$arrayMultiTextType->convertToPHPValue('{{first, 1}, {second, 156.256}, {third, "Hello"}, {last, World}}', self::$platform);
        $this->assertEquals($array, $phpArray, 'PHP convertion is not correct');

    }

    public function testConvertToPHPValueIsNull()
    {
        $phpArray = self::$arrayMultiTextType->convertToPHPValue(null, self::$platform);
        $this->assertNull($phpArray, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('text[]', self::$arrayMultiTextType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $this->assertEquals('text[]', self::$arrayMultiTextType->getSQLDeclaration([], self::$platform), 'SQL declaration is not correct');
    }
}
