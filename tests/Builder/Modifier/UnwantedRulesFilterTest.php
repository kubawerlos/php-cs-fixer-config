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

namespace Tests\Builder\Modifier;

use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet;
use PhpCsFixerConfig\Builder\Modifier\UnwantedRulesFilter;
use PhpCsFixerCustomFixers\Fixers;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerConfig\Builder\Modifier\UnwantedRulesFilter
 *
 * @internal
 */
final class UnwantedRulesFilterTest extends TestCase
{
    public function testRulesAreSorted(): void
    {
        $rules = $this->getRules();

        $sortedRules = $rules;
        \sort($sortedRules);

        self::assertSame($sortedRules, $rules);
    }

    /**
     * @dataProvider provideRuleIsNotDeprecatedCases
     */
    public function testRuleIsNotDeprecated(string $name): void
    {
        self::assertNotInstanceOf(DeprecatedFixerInterface::class, $this->getFixer($name, true));
    }

    public function provideRuleIsNotDeprecatedCases(): iterable
    {
        foreach ($this->getRules() as $name) {
            yield $name => [$name];
        }
    }

    private function getFixer(string $name, $config): FixerInterface
    {
        $fixers = FixerFactory::create()
            ->registerBuiltInFixers()
            ->registerCustomFixers(\iterator_to_array(new Fixers()))
            ->useRuleSet(new RuleSet([$name => $config]))
            ->getFixers();

        return $fixers[0];
    }

    /**
     * @return string[]
     */
    private function getRules(): array
    {
        $reflection = new \ReflectionClass(UnwantedRulesFilter::class);

        return $reflection->getConstant('UNWANTED_RULES');
    }
}
