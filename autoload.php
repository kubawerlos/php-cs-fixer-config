<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

require __DIR__ . '/vendor/autoload.php';

$phar = __DIR__ . '/vendor/php-cs-fixer/shim/php-cs-fixer.phar';

$pharLoaded = Phar::loadPhar($phar, 'php-cs-fixer.phar');
if (!$pharLoaded) {
    exit(sprintf('Phar "%s" not loaded!' . PHP_EOL, $phar));
}

require_once 'phar://php-cs-fixer.phar/vendor/autoload.php';
