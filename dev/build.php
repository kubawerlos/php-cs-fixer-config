<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/autoload.php';

$builder = new Dev\Builder\Builder();
$builder->build();

exec('php ' . __DIR__ . '/../vendor/bin/php-cs-fixer fix');
