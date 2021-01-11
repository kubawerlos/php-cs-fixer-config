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

/**
 * @internal
 */
final class UnwantedRulesFilter
{
    private const UNWANTED_RULES = [
        'class_keyword_remove',
        'combine_consecutive_issets',
        'combine_consecutive_unsets',
        'general_phpdoc_annotation_remove',
        'global_namespace_import',
        'native_constant_invocation', // TODO: move to NonDefaultConfiguration with strict flag after PHP CS Fixer 2.17 is released
        'no_blank_lines_before_namespace',
        'not_operator_with_space',
        'not_operator_with_successor_space',
        'php_unit_size_class',
        'phpdoc_summary',
        'psr0',
    ];

    /**
     * @param array<string, mixed> $rules
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $rules): array
    {
        foreach (self::UNWANTED_RULES as $rule) {
            $rules[$rule] = false;
        }

        return $rules;
    }
}
