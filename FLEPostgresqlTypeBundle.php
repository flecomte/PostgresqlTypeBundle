<?php

namespace FLE\Bundle\PostgresqlTypeBundle;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FLEPostgresqlTypeBundle extends Bundle
{
    public function boot ()
    {
        $this->defineType();
    }

    protected function defineType ()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $conn = $em->getConnection();

        if (!Type::hasType('cidr')) {
            Type::addType('cidr', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Cidr');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('CIDR', 'cidr');
        }

        if (!Type::hasType('box')) {
            Type::addType('box', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Box');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('BOX', 'box');
        }

        if (!Type::hasType('text[]')) {
            Type::addType('text[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayMultiText');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_text', 'text[]');
        }

        if (!Type::hasType('integer[]')) {
            Type::addType('integer[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayNumeric');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_int4', 'integer[]');
        }

        if (!Type::hasType('time_tz')) {
            Type::addType('time_tz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\TimeTz');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('TIME_TZ', 'time_tz');
        }

        Type::overrideType('datetime', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('DATETIME', 'datetime');

        Type::overrideType('datetimetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTimeTz');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('DATETIMETZ', 'datetimetz');

        Type::overrideType('time', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Time');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('TIME', 'time');
    }
}
