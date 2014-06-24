<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;

abstract class PgArrayAbstract extends Type
{
    protected function is_int ($v)
    {
        return (string) (int) $v === (string) $v;
    }

    protected function is_float ($v)
    {
        return (string) (float) $v === (float) $v;
    }
}
