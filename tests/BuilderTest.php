<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

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

        $nonDefaultRules = $nonDefaultConfiguration([]);

        $wantedRules = $unwantedRulesFilter($nonDefaultRules);

        foreach (\array_keys($nonDefaultRules) as $nonDefaultRuleName) {
            self::assertArrayHasKey($nonDefaultRuleName, $wantedRules, \sprintf('Non-default rules "%s" is not present.', $nonDefaultRuleName));
            self::assertNotFalse($wantedRules[$nonDefaultRuleName], \sprintf('Non-default rules "%s" is disabled.', $nonDefaultRuleName));
        }
    }
}
