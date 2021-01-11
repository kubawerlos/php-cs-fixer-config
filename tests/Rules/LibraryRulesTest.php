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

namespace Tests\Rules;

use PhpCsFixerConfig\Builder\Modifier\LibraryRulesModifier;
use PhpCsFixerConfig\Builder\Rules;
use PhpCsFixerConfig\Rules\LibraryRules;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerConfig\Rules\LibraryRules
 *
 * @internal
 */
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

            return $rules;
        });

        self::assertSame(
            $rules->getRules(),
            $libraryRules->getRules()
        );
    }
}
