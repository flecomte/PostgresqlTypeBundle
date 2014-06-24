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

    public static function setUpBeforeClass()
    {
        PgArrayMultiText::addType('text[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayMultiText');
        self::$pgArrayMultiTextType = PgArrayMultiText::getType('text[]');
    }

    public function testConvertToDatabaseValue()
    {
        $platform = new PostgreSqlPlatform();
        $array = array("first" => "Hello", "last" => "World");

        $sqlArray = self::$pgArrayMultiTextType->convertToDatabaseValue($array, $platform);

        $this->assertEquals('{{"first", "Hello"}, {"last", "World"}}', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $platform = new PostgreSqlPlatform();
        $array = null;

        $sqlArray = self::$pgArrayMultiTextType->convertToDatabaseValue($array, $platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $platform = new PostgreSqlPlatform();
        $array = array("first" => "Hello", "last" => "World");

        $phpArray = self::$pgArrayMultiTextType->convertToPHPValue('{{first, Hello}, {last, World}}', $platform);
        $this->assertEquals($array, $phpArray, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $platform = new PostgreSqlPlatform();

        $phpArray = self::$pgArrayMultiTextType->convertToPHPValue(null, $platform);
        $this->assertNull($phpArray, 'PHP convertion is not correct');
    }
}
