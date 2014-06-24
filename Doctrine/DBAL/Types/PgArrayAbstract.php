<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\Type;

abstract class PgArrayAbstract extends Type
{
    protected function isInt ($v)
    {
        return (string) (int) $v === (string) $v;
    }

    protected function isFloat ($v)
    {
        return (string) (float) $v === (float) $v;
    }
}
