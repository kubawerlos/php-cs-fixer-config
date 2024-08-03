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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(NonDefaultConfiguration::class)]
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

    #[DataProvider('provideRuleCases')]
    public function testRuleIsNotDeprecated(string $name): void
    {
        self::assertNotInstanceOf(DeprecatedFixerInterface::class, $this->getFixer($name));
    }

    #[DataProvider('provideRuleCases')]
    public function testConfigKeysAreSorted(string $name, array $config): void
    {
        $sortedConfig = $config;
        \ksort($sortedConfig);

        self::assertSame($sortedConfig, $config);
    }

    #[DataProvider('provideRuleCases')]
    public function testRuleIsNotUsingDefaultConfig(string $name, array $config): void
    {
        $defaultConfig = [];
        foreach ($this->getFixer($name)->getConfigurationDefinition()->getOptions() as $option) {
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
        foreach ((new NonDefaultConfiguration())([]) as $name => $config) {
            yield $name => [$name, $config];
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
}
