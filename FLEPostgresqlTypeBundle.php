<?php

namespace FLE\Bundle\PostgresqlTypeBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ORM\EntityManager;

class FLEPostgresqlTypeBundle extends Bundle
{
    public function boot ()
    {
        $this->defineType();
    }

    protected function defineType ()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $conn = $em->getConnection();

        if (!Type::hasType('cidr')) {
            Type::addType('cidr', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Cidr');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('cidr', 'cidr');
        }

        if (!Type::hasType('box')) {
            Type::addType('box', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Box');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('box', 'box');
        }

        if (!Type::hasType('text[]')) {
            Type::addType('text[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayMultiText');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('text[]', 'text[]');
        }

        if (!Type::hasType('integer[]')) {
            Type::addType('integer[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('integer[]', 'integer[]');
        }

        if (!Type::hasType('_int4')) {
            Type::addType('_int4', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_int4', '_int4');
        }

        if (!Type::hasType('jsonb')) {
            Type::addType('jsonb', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Jsonb');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('jsonb', 'jsonb');
        }

        if (!Type::hasType('timetz')) {
            Type::addType('timetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\TimeTz');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('timetz', 'timetz');
        }

        Type::overrideType('datetime', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('datetime', 'datetime');

        Type::overrideType('datetimetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTimeTz');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('datetimetz', 'datetimetz');

        Type::overrideType('time', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Time');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('time', 'time');
    }
}
