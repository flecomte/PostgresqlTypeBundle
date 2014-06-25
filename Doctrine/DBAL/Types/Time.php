<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class Time extends Type
{
    const TIME = 'time';

    public function getName()
    {
        return self::TIME;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('TIME');
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format('H:i:s.u') : null;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        try {
            $val = \DateTime::createFromFormat('H:i:s', $value);
            if ($val === false) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'H:i:s');
            }
        } catch (\Exception $e) {
            $val = \DateTime::createFromFormat('H:i:s.u', $value);
            if (! $val) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'H:i:s.u');
            }
        }
        return $val;
    }
}
