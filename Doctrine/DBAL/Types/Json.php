<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

class Json extends Jsonb
{
    const JSON = 'json';

    public function getName ()
    {
        return self::JSON;
    }
}
