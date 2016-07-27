<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

trait ArrayTrait
{
    public function convertArrayToDatabaseValue (array $array = null, AbstractPlatform $platform)
    {
        if ($array === null) {
            return null;
        }
        if ($array === '{}') {
            return [];
        }

        $convertArray = [];
        foreach ($array as $value) {
            if (is_array($value)) {
                throw new \Exception('This array can not contain more than 1 level deep');
            }

            $convertArray[] = parent::convertToDatabaseValue($value, $platform);
        }

        return '{'.implode(', ', $convertArray).'}';
    }

    public function convertArrayToPHPValue ($array, AbstractPlatform $platform)
    {
        if ($array === null) {
            return null;
        }
        if ($array === '{}') {
            return [];
        }

        $convertArray = [];
        foreach (explode(',', mb_substr($array, 1, -1)) as $value) {
            $convertArray[] = parent::convertToPHPValue($value, $platform);
        }
        return $convertArray;
    }
}
