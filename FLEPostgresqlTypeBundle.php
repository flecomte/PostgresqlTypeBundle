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

        if (!Type::hasType('point')) {
            Type::addType('point', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Point');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'point');
        }

        if (!Type::hasType('text[][]')) {
            Type::addType('text[][]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayAssociativeText');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('text[][]', 'text[][]');
        }

        if (!Type::hasType('__text')) {
            Type::addType('__text', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayAssociativeText');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('__text', '__text');
        }

        if (!Type::hasType('text[]')) {
            Type::addType('text[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayText');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('text[]', 'text[]');
        }

        if (!Type::hasType('_text')) {
            Type::addType('_text', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayText');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_text', '_text');
        }

        if (!Type::hasType('integer[]')) {
            Type::addType('integer[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('integer[]', 'integer[]');
        }

        if (!Type::hasType('_int4')) {
            Type::addType('_int4', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_int4', '_int4');
        }

        if (!Type::hasType('bigint[]')) {
            Type::addType('bigint[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('bigint[]', 'bigint[]');
        }

        if (!Type::hasType('_int8')) {
            Type::addType('_int8', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayInt');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_int8', '_int8');
        }

        if (!Type::hasType('jsonb')) {
            Type::addType('jsonb', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Jsonb');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('jsonb', 'jsonb');
        }

        if (!Type::hasType('json')) {
            Type::addType('json', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Json');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'json');
        }

        if (!Type::hasType('timetz')) {
            Type::addType('timetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\TimeTz');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('timetz', 'timetz');
        }

        Type::overrideType('datetime', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTime');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('datetime', 'datetime');

        if (!Type::hasType('datetime[]')) {
            Type::addType('datetime[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayDateTime');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('datetime[]', 'datetime[]');
        }

        if (!Type::hasType('_timestamp')) {
            Type::addType('_timestamp', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayDateTime');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_timestamp', '_timestamp');
        }

        Type::overrideType('datetimetz', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateTimeTz');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('datetimetz', 'datetimetz');

        Type::overrideType('time', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\Time');
        $conn->getDatabasePlatform()->registerDoctrineTypeMapping('time', 'time');

        if (!Type::hasType('time[]')) {
            Type::addType('time[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayTime');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('time[]', 'time[]');
        }

        if (!Type::hasType('_time')) {
            Type::addType('_time', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\ArrayTime');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_time', '_time');
        }

        if (!Type::hasType('interval')) {
            Type::addType('interval', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\DateInterval');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('interval', 'interval');
        }
    }
}
