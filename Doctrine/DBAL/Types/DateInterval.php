<?php
namespace FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class DateInterval extends AbstractType
{
    const INTERVAL = 'interval';

    public function getName()
    {
        return self::INTERVAL;
    }

    public function convertToDatabaseValue ($interval, AbstractPlatform $platform)
    {
        if ($interval === null) {
            return null;
        }

        $sql = "
            $interval->y year +
            $interval->m month +
            $interval->d day +
            $interval->h hour +
            $interval->i minute +
            $interval->s second
        ";

        return $sql;
    }

    public function convertToPHPValue ($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        preg_match('`(?:(?P<year>[0-9]+) (?:year|years))? ?(?:(?P<month>[0-9]+) (?:month|mons|mon))? ?(?:(?P<day>[0-9]+) (?:day|days))? ?(?:(?P<hour>[0-9]{2}):(?P<minute>[0-9]{2}):(?P<second>[0-9]{2}))`', $value, $matches);

        if ($matches['year']) {
            $y = $matches['year'].'Y';
        } else {
            $y = null;
        }
        if ($matches['month']) {
            $m = $matches['month'].'M';
        } else {
            $m = null;
        }
        if ($matches['day']) {
            $d = $matches['day'].'D';
        } else {
            $d = null;
        }

        if ($y !== null || $m !== null || $d !== null) {
            $p = $y.$m.$d;
        } else {
            $p = '';
        }


        if ($matches['hour']) {
            $h = $matches['hour'].'H';
        } else {
            $h = null;
        }
        if ($matches['minute']) {
            $i = $matches['minute'].'M';
        } else {
            $i = null;
        }
        if ($matches['second']) {
            $s = $matches['second'].'S';
        } else {
            $s = null;
        }

        if ($h !== null || $i !== null || $s !== null) {
            $t = 'T'.$h.$i.$s;
        } else {
            $t = '';
        }
        $di =  new \DateInterval("P$p$t");

        return $di;
    }
}
