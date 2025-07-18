<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests\Rules;

use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixerConfig\Builder\Modifier\LibraryRulesModifier;
use PhpCsFixerConfig\Builder\Rules;
use PhpCsFixerConfig\Rules\LibraryRules;
use PhpCsFixerCustomFixers\Fixer\PhpdocTagNoNamedArgumentsFixer;
use PhpCsFixerCustomFixers\Fixers;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LibraryRules::class)]
final class LibraryRulesTest extends TestCase
{
    public function testRulesAreUpToDate(): void
    {
        $libraryRules = new LibraryRules('library name', 'library author', 2000);

        $rules = new Rules();
        $rules->apply(new LibraryRulesModifier());
        $rules->apply(static function (array $rules): array {
            $rules['header_comment']['header'] = \trim('
This file is part of library name.

(c) 2000 library author

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.
        ');
            $rules[PhpdocTagNoNamedArgumentsFixer::name()]['directory'] = \getcwd() . '/src';

            return $rules;
        });

        self::assertSame(
            $rules->getRules(),
            $libraryRules->getRules(),
        );
    }

    public function testRulesBuildInConfig(): void
    {
        $rules = new LibraryRules('library name', 'library author', 2000);

        $ruleSet = new RuleSet($rules->getRules());

        $fixerFactory = new FixerFactory();
        $fixerFactory->registerBuiltInFixers();
        $fixerFactory->registerCustomFixers(\iterator_to_array(new Fixers()));

        self::assertInstanceOf(
            FixerFactory::class,
            $fixerFactory->useRuleSet($ruleSet),
        );
    }
}
