<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class Cidr extends AbstractType
{
    const CIDR = 'cidr';

    public function getName ()
    {
        return self::CIDR;
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (string) $value;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === '' || $value === null) {
            return null;
        }
        return (string) $value;
    }
}
