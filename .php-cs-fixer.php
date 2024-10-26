<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

require_once __DIR__ . '/vendor/autoload.php';

use PhpCsFixer\Finder;
use PhpCsFixerConfig\Factory;

return Factory::createForLibrary('kubawerlos/php-cs-fixer-config', 'Kuba Werłos', 2020)
    ->setUsingCache(false)
    ->setFinder(
        Finder::create()
            ->files()
            ->ignoreDotFiles(false)
            ->in(__DIR__)
            ->append([__DIR__ . '/fixinit']),
    );
