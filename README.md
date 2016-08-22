PostgresqlTypeBundle
====================

[![Build Status](https://travis-ci.org/flecomte/PostgresqlTypeBundle.svg)](https://travis-ci.org/flecomte/PostgresqlTypeBundle)

[![Dependency Status](https://www.versioneye.com/user/projects/53d7891b3648f468870002ad/badge.svg)](https://www.versioneye.com/user/projects/53d7891b3648f468870002ad)

[![Coverage Status](https://coveralls.io/repos/flecomte/PostgresqlTypeBundle/badge.png)](https://coveralls.io/r/flecomte/PostgresqlTypeBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/74bd7d10-8f45-4cd5-bcdb-5e537d097d89/small.png)](https://insight.sensiolabs.com/projects/74bd7d10-8f45-4cd5-bcdb-5e537d097d89)

Overview
--------

### Supported Types

Add support to postgresql type.

- Box
- Point
- Cidr
- DateInterval
- DateTime
- DateTime[]
- DateTime with TimeZone
- Time
- Time[]
- Time with TimeZone
- JSON
- JSONB
- int[]: ```array(-2147483648, 1, 2, 3, 4, 5, +2147483647)```
- bigint[]: ```array(-9223372036854775808, 1, 2, 3, 9223372036854775807)```
- text[]: ```array("Hello", "World")```
- text[][]: ```array("first" => "Hello", "last" => "World")```

### Supported Functions

Add support of functions.

- CONTAINTS ```SELECT CONTAINTS(ARRAY[1,2,3], 2);```
- date_trunc ```date_trunc('hour', timestamp '2001-02-16 20:38:40')```

### ManyToAny

Add ManyToAny Annotation

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use FLE\Bundle\PostgresqlTypeBundle\Annotation\ManyToAny;

/**
 * @ORM\Entity
 */
class MyEntity
{
    /**
     * @var mixed
     * @ManyToAny()
     */
    protected $mixedEntity;
    //...
}
```

Installation
------------

Add the bunde to your `composer.json` file:

```json
{
    "require": {
        "fle/postgresql-type-bundle": "1.*@dev"
    }
}
```

Then run a composer update:

```bash
composer.phar update fle/postgresql-type-bundle # to only update the bundle
```

Register the bundle with your kernel in `AppKernel::registerBundles()`:

```php
<?php
$bundles = array(
    // ...
    new FLE\Bundle\PostgresqlTypeBundle\FLEPostgresqlTypeBundle(),
    // ...
);
```

Usage
-----

### Point

To use Point twig template:

```yaml
twig:
    form_themes:
        - 'FLEPostgresqlTypeBundle::Form/point_type_bootstrap3.html.twig'
```
