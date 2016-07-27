<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\TimeType;

class Time extends TimeType
{
    use DateTimeTrait;

    protected static $format = 'H:i:s.u';
}
