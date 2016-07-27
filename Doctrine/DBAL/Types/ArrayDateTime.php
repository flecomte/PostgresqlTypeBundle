<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayDateTime extends DateTime
{
    use ArrayTrait;

    const ARRAY_DATETIME = 'datetime[]';

    public function getName ()
    {
        return self::ARRAY_DATETIME;
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        return $this->convertArrayToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        return $this->convertArrayToPHPValue($value, $platform);
    }
}
