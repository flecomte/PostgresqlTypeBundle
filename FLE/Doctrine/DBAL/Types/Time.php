<?php
namespace FLE\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\TimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class Time extends TimeType
{
    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format('H:i:s.uP') : null;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        try {
            $val = \DateTime::createFromFormat('H:i:sP', $value);
        } catch (\Exception $e) {
            $val = \DateTime::createFromFormat('H:i:s.uP', $value);
            if (! $val) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'H:i:s.uP');
            }
        }

        return $val;
    }
}
