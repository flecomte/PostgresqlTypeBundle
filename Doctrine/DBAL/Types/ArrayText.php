<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayText extends ArrayAbstract
{
    const ARRAY_TEXT = 'text[]';

    public function getName ()
    {
        return self::ARRAY_TEXT;
    }

    public function convertToDatabaseValue ($array, AbstractPlatform $platform)
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

            $convertArray[] = $value;
        }

        return '{'.implode(', ', $convertArray).'}';
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if ($value === '{}') {
            return [];
        }
        return explode(',', mb_substr($value, 1, -1));
    }
}
