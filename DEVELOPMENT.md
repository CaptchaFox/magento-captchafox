# Development

## Requirements

Magento Open Source or Adobe Commerce 2.4.4 or later, with PHP 8.1 or later and Composer. The module is
installed into it from a working copy of this repository through a Composer path repository, mirrored
like an install from Packagist:

```bash
composer config repositories.captchafox '{"type": "path", "url": "<path to this repository>", "options": {"symlink": false}}'
composer require captchafox/magento:*
bin/magento setup:upgrade
```

After a change, mirror it again (`rm -rf vendor/captchafox/magento && composer install`) and flush the
caches (`bin/magento cache:flush`); in production mode also run `bin/magento setup:di:compile` and
`bin/magento setup:static-content:deploy`. In production mode Magento swallows exceptions thrown while a
block renders and only logs them: check `var/log/system.log` when a widget is missing.

## Test

The repository has no automated tests of its own. End-to-end tests against Magento 2.4.9 and 2.4.4 run
before every release.
