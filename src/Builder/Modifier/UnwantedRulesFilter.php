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

/**
 * @internal
 */
final class UnwantedRulesFilter
{
    private const array UNWANTED_RULES = [
        'PhpCsFixerCustomFixers/isset_to_array_key_exists',
        'blank_line_after_opening_tag',
        'blank_line_between_import_groups',
        'combine_consecutive_issets',
        'combine_consecutive_unsets',
        'general_attribute_remove',
        'general_phpdoc_annotation_remove',
        'global_namespace_import',
        'group_import',
        'heredoc_closing_marker',
        'linebreak_after_opening_tag',
        'multiline_string_to_heredoc',
        'not_operator_with_space',
        'not_operator_with_successor_space',
        'php_unit_size_class',
        'phpdoc_summary',
        'single_line_comment_spacing', // CommentSurroundedBySpacesFixer used
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
