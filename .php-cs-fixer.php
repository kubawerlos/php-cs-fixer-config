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

return PhpCsFixerConfig\Factory::createForLibrary('kubawerlos/php-cs-fixer-config', 'Kuba Werłos', 2020)
    ->setUsingCache(false)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->files()
            ->in(__DIR__ . '/dev')
            ->in(__DIR__ . '/src')
            ->append([__FILE__]),
    );
