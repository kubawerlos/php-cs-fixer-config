<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\AutoReview;

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixerConfig\Builder\Modifier\NonDefaultConfiguration;
use PhpCsFixerCustomFixers\Fixers;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversNothing]
final class RulesTest extends TestCase
{
    public function testOptions(): void
    {
        $fixerFactory = new FixerFactory();
        $fixerFactory->registerBuiltInFixers();
        $fixerFactory->registerCustomFixers(\iterator_to_array(new Fixers()));

        $nonDefaultConfiguration = new NonDefaultConfiguration();
        $configuredFixers = $nonDefaultConfiguration([]);

        $options = [];
        foreach ($fixerFactory->getFixers() as $fixer) {
            if (!$fixer instanceof ConfigurableFixerInterface) {
                continue;
            }

            $fixerConfigurationResolver = \Closure::bind(
                static fn (ConfigurableFixerInterface $fixer): FixerConfigurationResolverInterface => $fixer->getConfigurationDefinition(),
                null,
                $fixer::class,
            )($fixer);
            self::assertInstanceOf(FixerConfigurationResolverInterface::class, $fixerConfigurationResolver);

            foreach ($fixerConfigurationResolver->getOptions() as $option) {
                if (!$option->hasDefault()) {
                    continue;
                }

                if (isset($configuredFixers[$fixer->getName()][$option->getName()])) {
                    $configuredValues = $configuredFixers[$fixer->getName()][$option->getName()];
                } else {
                    $configuredValues = $option->getDefault();
                }

                if (!\is_array($configuredValues) || $option->getAllowedValues() === null) {
                    continue;
                }

                self::assertCount(1, $option->getAllowedValues());

                $allowedValues = $option->getAllowedValues()[0];
                if (!$allowedValues instanceof AllowedValueSubset) {
                    continue;
                }

                \sort($configuredValues, \SORT_FLAG_CASE | \SORT_STRING);
                if ($configuredValues !== $allowedValues->getAllowedValues()) {
                    if (!isset($options[$fixer->getName()])) {
                        $options[$fixer->getName()] = [];
                    }
                    $options[$fixer->getName()][$option->getName()] = true;
                }
            }
        }

        \ksort($options);
        self::assertSame(
            [
                'blank_line_before_statement' => ['statements' => true],
                'no_extra_blank_lines' => ['tokens' => true],
                'single_space_after_construct' => ['constructs' => true],
            ],
            $options,
        );
    }

    private static function getFixer(string $name): FixerInterface
    {
        $fixers = (new FixerFactory())
            ->registerBuiltInFixers()
            ->registerCustomFixers(\iterator_to_array(new Fixers()))
            ->useRuleSet(new RuleSet([$name => true]))
            ->getFixers();

        return $fixers[0];
    }
}
