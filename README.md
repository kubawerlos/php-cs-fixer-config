# kubawerlos/php-cs-fixer-config

[![Latest stable version](https://img.shields.io/packagist/v/kubawerlos/php-cs-fixer-config.svg?label=current%20version)](https://packagist.org/packages/kubawerlos/php-cs-fixer-config)
[![PHP version](https://img.shields.io/packagist/php-v/kubawerlos/php-cs-fixer-config.svg)](https://php.net)
[![CI Status](https://github.com/kubawerlos/php-cs-fixer-config/workflows/CI/badge.svg?branch=main&event=push)](https://github.com/kubawerlos/php-cs-fixer-config/actions)
[![License](https://img.shields.io/github/license/kubawerlos/php-cs-fixer-config.svg)](LICENSE)

Provides a configuration for PHP CS Fixer.


## Installation
```bash
composer require --dev kubawerlos/php-cs-fixer-config
php vendor/bin/fixinit
```


## Usage
Create `.php-cs-fixer.php` file and use `PhpCsFixerConfig\Factory`:
```php
<?php
return PhpCsFixerConfig\Factory::createForLibrary('Library', 'Author', 2020 /* license initial year */)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->files()
            ->in(__DIR__ . '/src')
    );

```
for library or use method `createForProject` for project.
