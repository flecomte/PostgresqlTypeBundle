<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeTzType;

class DateTimeTz extends DateTimeTzType
{
    use DateTimeTrait;

    protected static $format = 'Y-m-d H:i:s.uP';
}
