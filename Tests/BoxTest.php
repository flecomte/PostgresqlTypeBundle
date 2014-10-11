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

    /**
     * @var PostgreSqlPlatform
     */
    protected static $platform;

    public static function setUpBeforeClass()
    {
        Box::addType('box', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Box');
        self::$boxType = Box::getType('box');
        self::$platform = new PostgreSqlPlatform();
        self::$platform->registerDoctrineTypeMapping('BOX', 'box');
    }

    public function testConvertToDatabaseValue()
    {
        $array = array(100, 200, 300, 400);

        $sqlArray = self::$boxType->convertToDatabaseValue($array, self::$platform);

        $this->assertEquals('(100,200),(300,400)', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueX1()
    {
        $array = array('x1' => 100, 'y1' => 200, 'x2' => 300, 'y2' => 400);

        $sqlArray = self::$boxType->convertToDatabaseValue($array, self::$platform);

        $this->assertEquals('(100,200),(300,400)', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $array = null;

        $sqlArray = self::$boxType->convertToDatabaseValue($array, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNullArray()
    {
        $array = [null, null, null, null];

        $sqlArray = self::$boxType->convertToDatabaseValue($array, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNullArrayX1()
    {
        $array = array('x1' => null, 'y1' => null, 'x2' => null, 'y2' => null);

        $sqlArray = self::$boxType->convertToDatabaseValue($array, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsEmpty()
    {
        $array = array();

        $sqlArray = self::$boxType->convertToDatabaseValue($array, self::$platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $array = array('x2' => 300, 'y2' => 400, 'x1' => 100, 'y1' => 200);

        $phpArray = self::$boxType->convertToPHPValue('(300,400),(100,200)', self::$platform);
        $this->assertEquals($array, $phpArray, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $phpArray = self::$boxType->convertToPHPValue(null, self::$platform);

        $this->assertNull($phpArray, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueBadFormat()
    {
        $array = 'badformat';

        $this->setExpectedException(
            'Exception', "BoxType Error. Value:$array"
        );

        $phpArray = self::$boxType->convertToPHPValue($array, self::$platform);
    }

    public function testGetName()
    {
        $this->assertEquals('box', self::$boxType->getName(), 'SQL name is not correct');
    }

    public function testGetSQLDeclaration()
    {
        $this->assertEquals('box', self::$boxType->getSQLDeclaration([], self::$platform), 'SQL declaration is not correct');
    }
}
