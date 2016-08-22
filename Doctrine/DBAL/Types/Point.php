<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class Point extends AbstractType
{
    const POINT = 'point';

    public function getName ()
    {
        return self::POINT;
    }

    /**
     * @param array            $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     * @throws \Exception
     */
    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if (is_array($value)) {
            if (empty($value)) {
                return null;
            } elseif (count($value) == 2) {
                if (isset($value['x']) && isset($value['y'])) {
                    if ($value['x'] === null && $value['y'] === null) {
                        return null;
                    } else {
                        return $value['x'].','.$value['y'];
                    }
                } elseif (isset($value[0]) && isset($value[1])) {
                    if ($value[0] === null && $value[1] === null) {
                        return null;
                    } else {
                        return $value[0].','.$value[1];
                    }
                } else {
                    throw new \Exception('PointType Error: wrong format');
                }
            } else {
                throw new \Exception('PointType Error: wrong format');
            }
        } else {
            throw new \Exception('PointType Error: wrong format');
        }
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return array|null
     * @throws \Exception
     */
    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
        if (!is_string($value)) {
            throw new \Exception('PointType Error.');
        }
        if (preg_match('`\((-?[0-9.]+),(-?[0-9.]+)\)`', $value, $matches)) {
            $reordered = array('x' => $matches[1], 'y' => $matches[2]);

            return $reordered;
        } else {
            throw new \Exception('PointType Error. Value:'.$value);
        }
    }
}
