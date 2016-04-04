<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

abstract class ArrayAbstract extends AbstractType
{
    protected function isInt ($v)
    {
        return (string) (int) $v === (string) $v;
    }

    protected function isFloat ($v)
    {
        return (string) (float) $v === (string) $v;
    }
}
