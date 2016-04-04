<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class Jsonb extends AbstractType
{
    const JSONB = 'jsonb';

    public function getName ()
    {
        return self::JSONB;
    }

    public function convertToDatabaseValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $json = json_encode($value);
        if ($json === false) {
            throw new \Exception('Not valid json.');
        }
        return $json;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return json_decode($value, true);
    }
}
