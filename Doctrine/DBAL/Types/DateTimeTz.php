<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeTzType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class DateTimeTz extends DateTimeTzType
{
	protected static $format = 'Y-m-d H:i:s.uP';

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
    	if ($value === null) {
    		return null;
    	} elseif ($value instanceof \DateTime) {
    		return $value->format(self::$format);
    	} elseif (is_string($value)) {
    		try {
        		$dt = new \DateTime($value);
        		return $dt->format(self::$format);
    		} catch (\Exception $e) {
    			throw new \Exception('Date "'.$value.'" is not a valid date');
    		}
    	}

    	throw new \Exception('Date "'.$value.'" is not a valid date');
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        try {
            return parent::convertToPHPValue($value, $platform);
        } catch (ConversionException $e) {
            $val = \DateTime::createFromFormat(self::$format, $value);
            if (! $val) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), self::$format);
            }

            return $val;
        }
    }
}
