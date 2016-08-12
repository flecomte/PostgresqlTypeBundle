<?php

namespace FLE\Bundle\PostgresqlTypeBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use FLE\Bundle\PostgresqlTypeBundle\Annotation\ManyToAny;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ManyToAnyListener
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader, RegistryInterface $registry)
    {
        $this->reader = $reader;
        $this->registry = $registry;
    }


    private function eachAnnotations ($entity, callable $callable)
    {
        $reflectionClass = new \ReflectionClass($entity);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            $annotation = $this->reader->getPropertyAnnotation($reflectionProperty, ManyToAny::class);
            if ($annotation instanceof ManyToAny) {
                $callable($entity, $annotation, $reflectionProperty);
            }
        }
    }

    private function getJoinTableName ($entity, ManyToAny $annotation, \ReflectionProperty $reflectionProperty, EntityManager $em)
    {
        if ($annotation->table instanceof JoinTable && ($tableName = $annotation->table->name) !== null) {
            return $annotation->table->name;
        } else {
            $namesSpace = $em->getConfiguration()->getEntityNamespaces();
            $className = is_object($entity) ? get_class($entity) : $entity;
            $className = str_replace($namesSpace, '', $className);
            $className = substr($className, 1);
            return strtolower($className).'_'.$reflectionProperty->name;
        }
    }

    public function postLoad (LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        $this->eachAnnotations($entity, function ($entity, ManyToAny $annotation, \ReflectionProperty $reflectionProperty) {
            $reflectionProperty->setValue($entity, new PersistentRelatedEntitiesCollection($this->registry, $entity, $annotation, $reflectionProperty));
        });
    }

    protected function getSoftDeletableCacheId ($className)
    {
        return $className.'\\$GEDMO_SOFTDELETEABLE_CLASSMETADATA';
    }

    protected function isSoftDelete ($entity, EntityManager $em)
    {
        $softDeletableConfig = $em->getConfiguration()->getMetadataCacheImpl()->fetch($this->getSoftDeletableCacheId(get_class($entity)));
        if (isset($softDeletableConfig['softDeleteable']) && isset($softDeletableConfig['fieldName']) && $softDeletableConfig['softDeleteable'] === true) {
            $deleteFieldReflexion = new \ReflectionProperty(get_class($entity), $softDeletableConfig['fieldName']);
            $deleteFieldReflexion->setAccessible(true);
            $oldValue = $deleteFieldReflexion->getValue($entity);
            if ($oldValue === null) {
                return true;
            }
        }
        return false;
    }

    public function preRemove (LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        $this->eachAnnotations($entity, function ($entity, ManyToAny $annotation, \ReflectionProperty $reflectionProperty) use ($event)
        {
            if ($this->isSoftDelete($entity, $event->getEntityManager())) {
                return; // don't delete
            }
            $em = $event->getEntityManager();
            $joinTableName = $this->getJoinTableName($entity, $annotation, $reflectionProperty, $em);

            $parentClass = get_class($entity);
            $parentId = $this->registry->getManagerForClass($parentClass)->getMetadataFactory()->getMetadataFor($parentClass)->getIdentifierValues($entity);

            $rsm = new ResultSetMappingBuilder($em);
            $query = $em->createNativeQuery("DELETE FROM $joinTableName WHERE parent_id = :parentId", $rsm);
            $query->setParameter('parentId', $parentId, 'jsonb');
            $query->execute();
        });
    }

    public function postPersist (LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        $this->eachAnnotations($entity, function ($entity, ManyToAny $annotation, \ReflectionProperty $reflectionProperty) use ($event)
        {
            $con = $event->getEntityManager()->getConnection();
            foreach ($reflectionProperty->getValue($entity) as $relatedEntity) {
                $relatedClass = ClassUtils::getClass($relatedEntity);
                $parentClass = get_class($entity);
                $parentId = $this->registry->getManagerForClass($parentClass)->getMetadataFactory()->getMetadataFor($parentClass)->getIdentifierValues($entity);
                $relatedId = $this->registry->getManagerForClass($relatedClass)->getMetadataFactory()->getMetadataFor($relatedClass)->getIdentifierValues($relatedEntity);
                asort($relatedId);
                if ( ! $relatedId) {
                    throw new \RuntimeException('The identifier for the related entity "'.$relatedClass.'" was empty.');
                }
                $tableName = $this->getJoinTableName($entity, $annotation, $reflectionProperty, $event->getEntityManager());
                $relatedTableName = $event->getEntityManager()->getClassMetadata($relatedClass)->getTableName();

                $con->executeUpdate("INSERT INTO $tableName (parent_id, related_class, related_table, related_id) VALUES (:parentId, :relatedClass, :relatedTable, :relatedId)", [
                    'parentId'     => $parentId,
                    'relatedClass' => $relatedClass,
                    'relatedTable' => $relatedTableName,
                    'relatedId'    => $relatedId,
                ],
                [
                    'parentId'     => 'jsonb',
                    'relatedClass' => 'string',
                    'relatedTable' => 'string',
                    'relatedId'    => 'jsonb',
                ]);
            }
        });
    }

    public function postGenerateSchema (GenerateSchemaEventArgs $event)
    {
        /** @var ClassMetadata $classMetadata */
        foreach ($event->getEntityManager()->getMetadataFactory()->getAllMetadata() as $classMetadata) {
            $this->eachAnnotations($classMetadata->getReflectionClass()->getName(), function ($className, ManyToAny $annotation, \ReflectionProperty $reflectionProperty) use ($event, $classMetadata)
            {
                $schema = $event->getSchema();
                $joinTableName = $this->getJoinTableName($className, $annotation, $reflectionProperty, $event->getEntityManager());
                $table = $schema->createTable($joinTableName);
                $table->addColumn('parent_id', 'jsonb', array('nullable' => false, 'unsigned' => true));
                $table->addColumn('related_class', 'string', array('nullable' => false, 'length' => '150'));
                $table->addColumn('related_table', 'string', array('nullable' => false, 'length' => '150'));
                $table->addColumn('related_id', 'jsonb', array('nullable' => false));
                $table->setPrimaryKey(array('parent_id', 'related_class', 'related_id'));
            });
        }
    }
}