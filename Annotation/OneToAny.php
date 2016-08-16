<?php

namespace FLE\Bundle\PostgresqlTypeBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Target("PROPERTY")
 */
class OneToAny extends Annotation
{
    public $cascade = [];

    public $nullable = true;

    public $orphanRemoval = false;
}
