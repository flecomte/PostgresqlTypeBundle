<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class PgArray extends Type
{
    const PG_ARRAY = 'pg_array';

    public function getName ()
    {
        return self::PG_ARRAY;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('PG_ARRAY');
    }

    private function array_to_pg_array(array $array)
    {
        $convertArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                throw new \Exception('multidimentional array!');
            }
            $value = '"'.$value.'"';
            $key = '"'.$key.'"';

            $convertArray[] = '{'.$key.', '.$value.'}';
        }

        return '{'.implode(', ', $convertArray).'}';
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
        preg_match_all('`{(?P<key>[^{},]+), ?(?P<value>[^{},]+)}`', $value, $matches);
        $r = [];
        foreach ($matches['value'] as $i => $v) {
            if ($this->is_int($v)) {
                $v = (int) $v;
            } elseif ($this->is_float($v)) {
                $v = (float) $v;
            } elseif (substr($v, -1) == '"' && substr($v, 0, 1) == '"') {
                $v = substr($v, 1, -1);
            }
            $r[$matches['key'][$i]] = $this->is_int($v) ? (int) $v : ($this->is_float($v) ? (float) $v : $v);
        }

        return $r;
    }
}
