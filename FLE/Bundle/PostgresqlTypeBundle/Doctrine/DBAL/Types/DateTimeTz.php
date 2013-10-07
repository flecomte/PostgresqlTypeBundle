<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeTzType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class DateTimeTz extends DateTimeTzType
{
    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format('Y-m-d H:i:s.uP') : null;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        try {
            return parent::convertToPHPValue($value, $platform);
        } catch (ConversionException $e) {
            $val = \DateTime::createFromFormat('Y-m-d H:i:s.uP', $value);
            if (! $val) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'Y-m-d H:i:s.uP');
            }

            return $val;
        }
    }
}
