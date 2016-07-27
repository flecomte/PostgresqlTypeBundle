<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class ArrayAbstract extends AbstractType
{
    protected function isInt ($v)
    {
        return (string) (int) $v === (string) $v;
    }

    protected function isFloat ($v)
    {
        return (string) (float) $v === (string) $v;
    }

    public function convertArrayToDatabaseValue ($array, AbstractPlatform $platform)
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

    public function convertArrayToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if ($value === '{}') {
            return [];
        }
        return explode(',', mb_substr(parent::convertToPHPValue($value, $platform), 1, -1));
    }
}
