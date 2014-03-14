<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayMultiText extends Type
{
    const ARRAY_MULTI_TEXT = 'text[]';

    public function getName ()
    {
        return self::ARRAY_MULTI_TEXT;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('text[]');
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
        if ($value === null) {
            return null;
        }
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
        if ($value === null) {
            return null;
        }
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
