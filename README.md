PostgresqlTypeBundle
====================

[![Build Status](https://travis-ci.org/flecomte/PostgresqlTypeBundle.svg)](https://travis-ci.org/flecomte/PostgresqlTypeBundle)

[![Dependency Status](https://www.versioneye.com/user/projects/53d7891b3648f468870002ad/badge.svg)](https://www.versioneye.com/user/projects/53d7891b3648f468870002ad)

[![Coverage Status](https://coveralls.io/repos/flecomte/PostgresqlTypeBundle/badge.png)](https://coveralls.io/r/flecomte/PostgresqlTypeBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/74bd7d10-8f45-4cd5-bcdb-5e537d097d89/small.png)](https://insight.sensiolabs.com/projects/74bd7d10-8f45-4cd5-bcdb-5e537d097d89)

Overview
--------

Add support to postgresql type.

### Supported Types:
- Box
- Cidr
- DateInterval
- DateTime
- DateTime with TimeZone
- Time
- Time with TimeZone
- Multidimentional Array of text: array("first" => "Hello", "last" => "World")
- Array of INT: array(1, 2, 3, 4, 5, 999)



Installation
------------

Add the bunde to your `composer.json` file:

```json
require: {
    "fle/postgresql-type-bundle": "1.*@dev"
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
