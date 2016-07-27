<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types\TimeType;

class TimeTz extends TimeType
{
    use DateTimeTrait;

    const TIME_TZ = 'timetz';

    protected static $format = 'H:i:s.uO';

    public function getName()
    {
        return self::TIME_TZ;
    }
}
