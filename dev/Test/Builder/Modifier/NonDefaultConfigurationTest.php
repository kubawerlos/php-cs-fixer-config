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

use Dev\Builder\Modifier\NonDefaultConfiguration;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\DeprecatedFixerOptionInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixerCustomFixers\Fixers;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Dev\Builder\Modifier\NonDefaultConfiguration
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
            },
        );

        self::assertSame($sortedRules, $rules);
    }

    /**
     * @dataProvider provideRuleCases
     */
    public function testRuleIsNotDeprecated(string $name, $config): void
    {
        self::assertNotInstanceOf(DeprecatedFixerInterface::class, $this->getFixer($name, true));
    }

    /**
     * @dataProvider provideRuleCases
     */
    public function testConfigKeysAreSorted(string $name, $config): void
    {
        $sortedConfig = $config;
        \ksort($sortedConfig);

        self::assertSame($sortedConfig, $config);
    }

    /**
     * @dataProvider provideRuleCases
     */
    public function testRuleIsNotUsingDefaultConfig(string $name, $config): void
    {
        $defaultConfig = [];
        foreach ($this->getFixer($name, true)->getConfigurationDefinition()->getOptions() as $option) {
            if ($option instanceof DeprecatedFixerOptionInterface) {
                continue;
            }

            $defaultConfig[$option->getName()] = $option->getDefault();
        }

        \ksort($defaultConfig);

        self::assertNotSame($defaultConfig, $config);
    }

    public static function provideRuleCases(): iterable
    {
        foreach ((new NonDefaultConfiguration())->__invoke([]) as $name => $config) {
            yield $name => [$name, $config];
        }
    }

    private function getFixer(string $name, $config): FixerInterface
    {
        $fixers = (new FixerFactory())
            ->registerBuiltInFixers()
            ->registerCustomFixers(\iterator_to_array(new Fixers()))
            ->useRuleSet(new RuleSet([$name => $config]))
            ->getFixers();

        return $fixers[0];
    }
}
