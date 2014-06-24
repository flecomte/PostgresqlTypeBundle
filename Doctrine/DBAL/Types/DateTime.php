<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeType;

class DateTime extends DateTimeType
{
    use DateTimeTrait;

    protected static $format = 'Y-m-d H:i:s.u';
}
