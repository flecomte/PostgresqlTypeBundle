<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayMultiText;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;

class PgArrayMultiTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PgArrayMultiText
     */
    protected static $pgArrayMultiTextType;

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        PgArrayMultiText::addType('text[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayMultiText');
        self::$pgArrayMultiTextType = PgArrayMultiText::getType('text[]');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('_text', 'text[]');
    }

    public function testConvertToDatabaseValue()
    {
        $array = array("first" => "Hello", "last" => "World");

        $sqlArray = self::$pgArrayMultiTextType->convertToDatabaseValue($array, self::$platform);

        $this->assertEquals('{{"first", "Hello"}, {"last", "World"}}', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueException()
    {
        $this->setExpectedException(
            'Exception', 'multidimentional array!'
        );

        $array = array('plip' => ['tic' => 'tac'], 'plop');

        $sqlArray = self::$pgArrayMultiTextType->convertToDatabaseValue($array, self::$platform);
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $array = null;

        $sqlArray = self::$pgArrayMultiTextType->convertToDatabaseValue($array, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $array = array("first" => 1, "second" => 156.256, "third" => 'Hello', "last" => "World");

        $phpArray = self::$pgArrayMultiTextType->convertToPHPValue('{{first, 1}, {second, 156.256}, {third, "Hello"}, {last, World}}', self::$platform);
        $this->assertEquals($array, $phpArray, 'PHP convertion is not correct');

    }

    public function testConvertToPHPValueIsNull()
    {
        $phpArray = self::$pgArrayMultiTextType->convertToPHPValue(null, self::$platform);
        $this->assertNull($phpArray, 'PHP convertion is not correct');
    }

    public function testGetName()
    {
        $this->assertEquals('text[]', self::$pgArrayMultiTextType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $array = array("first" => "Hello", "last" => "World");
        $this->assertEquals('text[]', self::$pgArrayMultiTextType->getSQLDeclaration($array, self::$platform), 'SQL declaration is not correct');
    }
}
