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
        $em = $this->container->get('doctrine.orm.default_entity_manager'); $em instanceof EntityManager;
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
            Type::addType('text[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayMultiText');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_text', 'text[]');
        }

        if (!Type::hasType('integer[]')) {
            Type::addType('integer[]', 'FLE\Bundle\PostgresqlTypeBundle\Doctrine\DBAL\Types\PgArrayNumeric');
            $conn->getDatabasePlatform()->registerDoctrineTypeMapping('_int', 'integer[]');
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
