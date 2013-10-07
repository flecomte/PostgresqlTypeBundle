<?php

namespace FLE\PostgresqlTypeBundle;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WmVpdjBundle extends Bundle
{
    public function boot ()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $this->defineType($em);
    }

    public function defineType (EntityManager $em)
    {
        $conn = $em->getConnection();

        if (!Type::hasType('cidr')) {
            Type::addType('cidr', 'FLE\PostgresqlTypeBundle\Doctrine\DBAL\Types\Cidr');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('CIDR', 'cidr');
        }

        if (!Type::hasType('box')) {
            Type::addType('box', 'FLE\PostgresqlTypeBundle\Doctrine\DBAL\Types\Box');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('BOX', 'box');
        }

        if (!Type::hasType('pg_array')) {
            Type::addType('pg_array', 'FLE\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArray');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('PG_ARRAY', 'pg_array');
        }

        if (!Type::hasType('time_tz')) {
            Type::addType('time_tz', 'FLE\PostgresqlTypeBundle\Doctrine\DBAL\Types\TimeTz');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('TIME_TZ', 'time_tz');
        }

        Type::overrideType('datetime', 'FLE\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('DATETIME', 'datetime');

        Type::overrideType('datetimetz', 'FLE\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTimeTz');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('DATETIMETZ', 'datetimetz');

        Type::overrideType('time', 'FLE\PostgresqlTypeBundle\Doctrine\DBAL\Types\Time');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('TIME', 'time');
    }
}
