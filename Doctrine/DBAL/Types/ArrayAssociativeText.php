<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class ArrayAssociativeText extends ArrayAbstract
{
    const ARRAY_ASSOC_TEXT = 'text[][]';

    public function getName ()
    {
        return self::ARRAY_ASSOC_TEXT;
    }

    public function convertToDatabaseValue ($array, AbstractPlatform $platform)
    {
        if ($array === null) {
            return null;
        }

        $convertArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                throw new \Exception('This array can not contain more than 1 level deep');
            }

            $convertArray[] = '{"'.$key.'", "'.$value.'"}';
        }

        return '{'.implode(', ', $convertArray).'}';
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        preg_match_all('`{(?P<key>[^{},]+), ?(?P<value>[^{},]+)}`', $value, $matches);
        $r = [];
        foreach ($matches['value'] as $i => $v) {
            if ($this->isInt($v)) {
                $v = (int) $v;
            } elseif ($this->isFloat($v)) {
                $v = (float) $v;
            } elseif (substr($v, -1) == '"' && substr($v, 0, 1) == '"') {
                $v = substr($v, 1, -1);
            }
            $r[$matches['key'][$i]] = $v;
        }

        return $r;
    }
}
