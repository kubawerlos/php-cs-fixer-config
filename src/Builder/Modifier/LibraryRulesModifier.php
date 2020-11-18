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

namespace PhpCsFixerConfig\Builder\Modifier;

use PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocOnlyAllowedAnnotationsFixer;

/**
 * @internal
 */
final class LibraryRulesModifier
{
    /**
     * @param array<string, mixed> $rules
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $rules): array
    {
        foreach (
            [
                'heredoc_indentation',
                'mb_str_functions',
                NoNullableBooleanTypeFixer::name(),
            ] as $rule
        ) {
            $rules[$rule] = false;
        }

        $rules['header_comment'] = ['header' => '__HEADER_PLACEHOLDER__', 'location' => 'after_open'];
        $rules[PhpdocOnlyAllowedAnnotationsFixer::name()] = ['elements' => [
            'covers',
            'coversNothing',
            'dataProvider',
            'deprecated',
            'implements',
            'internal',
            'method',
            'param',
            'property',
            'requires',
            'return',
            'var',
        ]];

        return $rules;
    }
}
