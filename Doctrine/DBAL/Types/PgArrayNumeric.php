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
        return $platform->getDoctrineTypeMapping('integer[]');
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
