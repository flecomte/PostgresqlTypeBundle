<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Box;

class BoxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Box
     */
    protected static $boxType;

    public static function setUpBeforeClass()
    {
        Box::addType('box', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Box');
        self::$boxType = Box::getType('box');
    }

    public function testConvertToDatabaseValue()
    {
        $platform = new PostgreSqlPlatform();
        $array = array(100, 200, 300, 400);

        $sqlArray = self::$boxType->convertToDatabaseValue($array, $platform);

        $this->assertEquals('(100,200),(300,400)', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $platform = new PostgreSqlPlatform();
        $array = null;

        $sqlArray = self::$boxType->convertToDatabaseValue($array, $platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsEmpty()
    {
        $platform = new PostgreSqlPlatform();
        $array = array();

        $sqlArray = self::$boxType->convertToDatabaseValue($array, $platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $platform = new PostgreSqlPlatform();
        $array = array('x2' => 300, 'y2' => 400, 'x1' => 100, 'y1' => 200);

        $phpArray = self::$boxType->convertToPHPValue('(300,400),(100,200)', $platform);
        $this->assertEquals($array, $phpArray, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $platform = new PostgreSqlPlatform();

        $phpArray = self::$boxType->convertToPHPValue(null, $platform);

        $this->assertNull($phpArray, 'PHP convertion is not correct');
    }
}
