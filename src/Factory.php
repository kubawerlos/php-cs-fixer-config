<?php

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpCsFixerConfig;

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;

/**
 * Class to create PHP CS Fixer config.
 */
final class Factory
{
    public static function createForLibrary(string $library, string $author, int $startingYear): ConfigInterface
    {
        return self::create(new Rules\LibraryRules($library, $author, $startingYear));
    }

    public static function createForProject(): ConfigInterface
    {
        return self::create(new Rules\ProjectRules());
    }

    private static function create(Rules\RulesInterface $rules): ConfigInterface
    {
        return (new Config())
            ->registerCustomFixers(new \PhpCsFixerCustomFixers\Fixers())
            ->setRiskyAllowed(true)
            ->setRules($rules->getRules());
    }
}
