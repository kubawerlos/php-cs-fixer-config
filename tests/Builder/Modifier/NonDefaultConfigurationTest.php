<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Builder\Modifier;

use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\FixerConfiguration\DeprecatedFixerOptionInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixerConfig\Builder\Modifier\NonDefaultConfiguration;
use PhpCsFixerCustomFixers\Fixers;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpunit;
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

    #[RequiresPhpunit('>= 11.0')]
    public static function testPhpUnitMethods(): void
    {
        $fixerReflection = new \ReflectionClass(PhpUnitTestCaseStaticMethodCallsFixer::class);
        $availableMethods = \array_keys($fixerReflection->getConstant('STATIC_METHODS'));

        $fixerReflection = new \ReflectionClass(TestCase::class);

        $nonDefaultConfiguration = new NonDefaultConfiguration();
        $configMethods = $nonDefaultConfiguration([])['php_unit_test_case_static_method_calls']['methods'];

        $sortedConfigMethods = $configMethods;
        \ksort($sortedConfigMethods);

        self::assertSame($sortedConfigMethods, $configMethods);

        foreach ($configMethods as $method => $callType) {
            self::assertSame(
                PhpUnitTestCaseStaticMethodCallsFixer::CALL_TYPE_THIS,
                $callType,
                "Method '{$method}' must configured to use dynamic call.",
            );
        }

        foreach ($availableMethods as $method) {
            self::assertSame(
                $fixerReflection->hasMethod($method) && !$fixerReflection->getMethod($method)->isStatic(),
                isset($configMethods[$method]),
                "Method '{$method}' must be configured dynamic call if is not static.",
            );
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
