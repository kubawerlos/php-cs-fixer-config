<?php

declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Builder\Modifier;

use PhpCsFixerConfig\Builder\Modifier\NonDefaultConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerConfig\Builder\Modifier\NonDefaultConfiguration
 *
 * @internal
 */
final class NonDefaultConfigurationTest extends TestCase
{
    public function testRulesAreSorted(): void
    {
        $nonDefaultConfiguration = new NonDefaultConfiguration();
        $rules = $nonDefaultConfiguration([]);

        $sortedRules = $rules;

        \uksort(
            $sortedRules,
            static function (string $x, string $y): int {
                if (\ctype_upper($x[0]) !== \ctype_upper($y[0])) {
                    return \ctype_upper($x[0]) ? 1 : -1;
                }

                return $x <=> $y;
            }
        );

        self::assertSame($sortedRules, $rules);
    }
}
