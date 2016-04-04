<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

class ArrayBigInt extends ArrayInt
{
    const ARRAY_BIGINT = 'bigint[]';

    public function getName ()
    {
        return self::ARRAY_BIGINT;
    }
}
