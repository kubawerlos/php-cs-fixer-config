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

use PhpCsFixerCustomFixers\Fixer\NoSuperfluousConcatenationFixer;

/**
 * @internal
 */
final class NonDefaultConfiguration
{
    /**
     * @param array<string, mixed> $rules
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $rules): array
    {
        $rules['align_multiline_comment'] = ['comment_type' => 'all_multiline'];
        $rules['blank_line_before_statement'] = ['statements' => ['return']];
        $rules['class_attributes_separation'] = ['elements' => ['method' => 'one', 'property' => 'one']];
        $rules['class_definition'] = ['multi_line_extends_each_single_line' => true, 'single_item_single_line' => true];
        $rules['concat_space'] = ['spacing' => 'one'];
        $rules['increment_style'] = ['style' => 'post'];
        $rules['method_argument_space'] = ['on_multiline' => 'ensure_fully_multiline'];
        $rules['native_constant_invocation'] = ['scope' => 'namespaced',  'strict' => true];
        $rules['native_function_invocation'] = ['include' => ['@all'], 'scope' => 'namespaced',  'strict' => true];
        $rules['no_extra_blank_lines'] = ['tokens' => ['continue', 'curly_brace_block', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'throw', 'use', 'use_trait']];
        $rules['no_superfluous_phpdoc_tags'] = ['remove_inheritdoc' => true];
        $rules['php_unit_test_case_static_method_calls'] = ['call_type' => 'self'];
        $rules['phpdoc_line_span'] = ['property' => 'single'];
        $rules['yoda_style'] = ['equal' => false, 'identical' => false, 'less_and_greater' => false];
        $rules[NoSuperfluousConcatenationFixer::name()] = ['allow_preventing_trailing_spaces' => true];

        return $rules;
    }
}
