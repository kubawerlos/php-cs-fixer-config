<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dev\Test\Builder\Modifier;

use Dev\Builder\Modifier\UnwantedRulesFilter;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixerCustomFixers\Fixers;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(UnwantedRulesFilter::class)]
final class UnwantedRulesFilterTest extends TestCase
{
    public function testRulesAreSorted(): void
    {
        $rules = self::getRules();

        $sortedRules = $rules;
        \sort($sortedRules);

        self::assertSame($sortedRules, $rules);
    }

    #[DataProvider('provideRuleIsNotDeprecatedCases')]
    public function testRuleIsNotDeprecated(string $name): void
    {
        self::assertNotInstanceOf(DeprecatedFixerInterface::class, $this->getFixer($name));
    }

    public static function provideRuleIsNotDeprecatedCases(): iterable
    {
        foreach (self::getRules() as $name) {
            yield $name => [$name];
        }
    }

    private function getFixer(string $name): FixerInterface
    {
        $fixers = (new FixerFactory())
            ->registerBuiltInFixers()
            ->registerCustomFixers(\iterator_to_array(new Fixers()))
            ->useRuleSet(new RuleSet([$name => true]))
            ->getFixers();

        return $fixers[0];
    }

    /**
     * @return list<string>
     */
    private static function getRules(): array
    {
        $reflection = new \ReflectionClass(UnwantedRulesFilter::class);

        return $reflection->getConstant('UNWANTED_RULES');
    }
}
