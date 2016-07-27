<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayTime extends Time
{
    use ArrayTrait;

    const ARRAY_TIME = 'time[]';

    public function getName ()
    {
        return self::ARRAY_TIME;
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
