<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace PhpCsFixerConfig;

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixerCustomFixers\Fixers;

/**
 * Class to create PHP CS Fixer config.
 *
 * @no-named-arguments
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
            ->registerCustomFixers(new Fixers())
            ->setParallelConfig(ParallelConfigFactory::detect())
            ->setRiskyAllowed(true)
            ->setRules($rules->getRules())
            ->setUnsupportedPhpVersionAllowed(true);
    }
}
