<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Cidr extends Type
{
    const CIDR = 'cidr';

    public function getName ()
    {
        return self::CIDR;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('CIDR');
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        if ($value === null || $value == '') {
            return null;
        }
        return (string) $value;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value == '' || $value == null) {
            return null;
        }
        return (string) $value;
    }
}
