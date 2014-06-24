<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Box extends Type
{
    const BOX = 'box';

    public function getName ()
    {
        return self::BOX;
    }

    public function getSQLDeclaration (array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('BOX');
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if (is_array($value)) {
            if (empty($value)) {
                return null;
            } elseif (count($value) == 4) {
                if (isset($value['x1'])) {
                    if ($value['x1'] === null && $value['y1'] === null && $value['x2'] === null && $value['y2'] === null) {
                        return null;
                    } else {
                        return '('.$value['x1'].','.$value['y1'].'),('.$value['x2'].','.$value['y2'].')';
                    }
                } else if (isset($value[0])) {
                    if ($value[0] === null && $value[1] === null && $value[2] === null && $value[3] === null) {
                        return null;
                    } else {
                        return '('.$value[0].','.$value[1].'),('.$value[2].','.$value[3].')';
                    }
                }
            } else {
                throw new \Exception('BoxType Error: wrong format');
            }
        } else {
            throw new \Exception('BoxType Error: wrong format');
        }
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if ($value === null) {
            return array(
                'x1' => null,
                'y1' => null,
                'x2' => null,
                'y2' => null
            );
        } elseif (preg_match('`\((-?[0-9.]+),(-?[0-9.]+)\),\((-?[0-9.]+),(-?[0-9.]+)\)`', $value, $matches)) {
            $reordered = array('x1' => $matches[3], 'y1' => $matches[4], 'x2' => $matches[1], 'y2' => $matches[2]);

            return $reordered;
        } else {
            throw new \Exception('BoxType Error. Value:'.$value);
        }
    }
}
