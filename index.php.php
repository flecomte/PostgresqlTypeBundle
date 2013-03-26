<?php
$em = Doctrine\ORM\EntityManager::create($conn, $config, $evm);

Doctrine\DBAL\Types\Type::addType('cidr', 'FLE\Doctrine\DBAL\Types\Cidr');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('CIDR', 'cidr');

Doctrine\DBAL\Types\Type::overrideType('datetime', 'FLE\Doctrine\DBAL\Types\DateTime');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('DATETIME', 'datetime');