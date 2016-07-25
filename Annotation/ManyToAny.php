<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @Annotation
 * @Annotation\Target("PROPERTY")
 */
class ManyToAny extends Annotation
{
    /**
     * @var JoinTable
     */
    public $table = [];
}
