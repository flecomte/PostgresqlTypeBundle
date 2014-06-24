<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Tests;

use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayNumeric;

class PgArrayNumericTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PgArrayNumeric
     */
    protected static $pgArrayNumericType;

    public static function setUpBeforeClass()
    {
        PgArrayNumeric::addType('integer[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayNumeric');
        self::$pgArrayNumericType = PgArrayNumeric::getType('integer[]');
    }

    public function testConvertToDatabaseValue()
    {
        $platform = new PostgreSqlPlatform();
        $array = array(1, 2, 3, 4, 5, 999);

        $sqlArray = self::$pgArrayNumericType->convertToDatabaseValue($array, $platform);

        $this->assertEquals('{1, 2, 3, 4, 5, 999}', $sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToDatabaseValueIsNull()
    {
        $platform = new PostgreSqlPlatform();
        $array = null;

        $sqlArray = self::$pgArrayNumericType->convertToDatabaseValue($array, $platform);

        $this->assertNull($sqlArray, 'SQL convertion is not correct');
    }

    public function testConvertToPHPValue()
    {
        $platform = new PostgreSqlPlatform();
        $array = array(1, 2, 3, 4, 5, 999);

        $phpArray = self::$pgArrayNumericType->convertToPHPValue('{1, 2, 3, 4, 5, 999}', $platform);
        $this->assertEquals($array, $phpArray, 'PHP convertion is not correct');
    }

    public function testConvertToPHPValueIsNull()
    {
        $platform = new PostgreSqlPlatform();

        $phpArray = self::$pgArrayNumericType->convertToPHPValue(null, $platform);
        $this->assertNull($phpArray, 'PHP convertion is not correct');
    }
}
