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

namespace Tests;

use PhpCsFixerConfig\Builder\Modifier\NonDefaultConfiguration;
use PhpCsFixerConfig\Builder\Modifier\UnwantedRulesFilter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerConfig\Builder\Modifier\NonDefaultConfiguration
 * @covers \PhpCsFixerConfig\Builder\Modifier\UnwantedRulesFilter
 *
 * @internal
 */
final class BuilderTest extends TestCase
{
    public function testNonDefaultRulesAreNotRemoved(): void
    {
        $nonDefaultConfiguration = new NonDefaultConfiguration();
        $unwantedRulesFilter = new UnwantedRulesFilter();

        $rules = $nonDefaultConfiguration([]);

        $wantedRules = $unwantedRulesFilter($rules);

        self::assertSame($rules, $wantedRules);
    }
}
