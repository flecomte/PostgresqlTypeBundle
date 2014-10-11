<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class PgArrayNumeric extends PgArrayAbstract
{
    const ARRAY_NUMERIC = 'integer[]';

    public function getName ()
    {
        return self::ARRAY_NUMERIC;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('_int');
    }

    public function convertToDatabaseValue ($array, AbstractPlatform $platform)
    {
        if ($array === null) {
            return null;
        }

        $convertArray = [];
        foreach ($array as $value) {
            if (!is_numeric($value)) {
                throw new \Exception('not numeric!');
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

        preg_match_all('`(?P<value>[0-9\.]+)`', $value, $matches);
        $r = [];
        foreach ($matches['value'] as $i => $v) {
            if ($this->isInt($v)) {
                $v = (int) $v;
            } elseif ($this->isFloat($v)) {
                $v = (float) $v;
            }
            $r[] = $v;
        }

        return $r;
    }
}
