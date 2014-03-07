<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayNumeric extends Type
{
    const ARRAY_NUMERIC = 'integer[]';

    public function getName ()
    {
        return self::ARRAY_NUMERIC;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('integer[]');
    }

    private function array_to_pg_array(array $array)
    {
        $values = [];
        foreach ($array as $value) {
            if (!is_numeric($value)) {
                throw new \Exception('not numeric!');
            }
            $values[] = $value;
        }

        return '{'.implode(', ', $values).'}';
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        return $this->array_to_pg_array($value);
    }

    private function is_int ($v)
    {
        return (string) (int) $v === (string) $v;
    }

    private function is_float ($v)
    {
        return (string) (float) $v === (float) $v;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        preg_match_all('`{(?P<value>[^{},]+)}`', $value, $matches);
        $r = [];
        foreach ($matches['value'] as $i => $v) {
            if ($this->is_int($v)) {
                $v = (int) $v;
            } elseif ($this->is_float($v)) {
                $v = (float) $v;
            }
            $r[] = $this->is_int($v) ? (int) $v : ($this->is_float($v) ? (float) $v : $v);
        }

        return $r;
    }
}
