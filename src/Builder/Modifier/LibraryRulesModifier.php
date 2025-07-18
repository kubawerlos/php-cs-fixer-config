<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace PhpCsFixerConfig\Builder\Modifier;

use PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocOnlyAllowedAnnotationsFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTagNoNamedArgumentsFixer;

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
                'mb_str_functions',
                NoNullableBooleanTypeFixer::name(),
            ] as $rule
        ) {
            $rules[$rule] = false;
        }

        $rules['header_comment'] = ['header' => '__HEADER_PLACEHOLDER__', 'location' => 'after_declare_strict'];
        $rules['new_expression_parentheses'] = ['use_parentheses' => true];
        $rules[PhpdocOnlyAllowedAnnotationsFixer::name()] = ['elements' => [
            'covers',
            'coversNothing',
            'dataProvider',
            'deprecated',
            'implements',
            'internal',
            'method',
            'param',
            'phpstan-import-type',
            'phpstan-type',
            'property',
            'psalm-var',
            'requires',
            'return',
            'runInSeparateProcess',
            'template',
            'var',
        ]];
        $rules[PhpdocTagNoNamedArgumentsFixer::name()] = ['directory' => '__GETCWD_SRC_PLACEHOLDER__'];

        return $rules;
    }
}
