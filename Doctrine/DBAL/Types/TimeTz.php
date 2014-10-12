<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class TimeTz extends Type
{
    const TIME_TZ = 'timetz';

    public function getName()
    {
        return self::TIME_TZ;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('timetz');
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format('H:i:s.uO') : null;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        try {
            $val = \DateTime::createFromFormat('H:i:sO', $value);
            if ($val === false) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'H:i:s');
            }
        } catch (\Exception $e) {
            $val = \DateTime::createFromFormat('H:i:s.uO', $value);
            if (! $val) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), 'H:i:s.uO');
            }
        }

        return $val;
    }
}
