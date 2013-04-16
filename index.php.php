<?php

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;

$em = EntityManager::create($conn, $config, $evm);
$conn = $em->getConnection();

Type::addType('cidr', 'FLE\Doctrine\DBAL\Types\Cidr');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('CIDR', 'cidr');

Type::addType('box', 'FLE\Doctrine\DBAL\Types\Box');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('BOX', 'box');

Type::addType('time_tz', 'FLE\Doctrine\DBAL\Types\TimeTz');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('TIME_TZ', 'time_tz');

Type::overrideType('datetime', 'FLE\Doctrine\DBAL\Types\DateTime');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('DATETIME', 'datetime');

Type::overrideType('time', 'FLE\Doctrine\DBAL\Types\Time');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('TIME', 'time');