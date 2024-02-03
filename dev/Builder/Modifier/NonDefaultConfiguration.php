<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dev\Builder\Modifier;

use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;

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
        $rules['class_attributes_separation'] = ['elements' => ['const' => 'none', 'method' => 'one', 'property' => 'none', 'trait_import' => 'none']];
        $rules['class_definition'] = ['multi_line_extends_each_single_line' => true, 'single_item_single_line' => true, 'space_before_parenthesis' => true];
        $rules['concat_space'] = ['spacing' => 'one'];
        $rules['increment_style'] = ['style' => 'post'];
        $rules['method_argument_space'] = ['on_multiline' => 'ensure_fully_multiline'];
        $rules['native_constant_invocation'] = ['scope' => 'namespaced', 'strict' => true];
        $rules['native_function_invocation'] = ['include' => ['@all'], 'scope' => 'namespaced', 'strict' => true];
        $rules['no_extra_blank_lines'] = ['tokens' => \array_diff((new \ReflectionClass(NoExtraBlankLinesFixer::class))->getStaticPropertyValue('availableTokens'), ['use_trait'])];
        $rules['no_superfluous_phpdoc_tags'] = ['remove_inheritdoc' => true];
        $rules['numeric_literal_separator'] = ['strategy' => 'no_separator'];
        $rules['php_unit_data_provider_static'] = ['force' => true];
        $rules['php_unit_test_case_static_method_calls'] = ['call_type' => 'self'];
        $rules['phpdoc_line_span'] = ['property' => 'single'];
        $rules['trailing_comma_in_multiline'] = ['after_heredoc' => true, 'elements' => self::trailingCommaInMultilineElements()];
        $rules['whitespace_after_comma_in_array'] = ['ensure_single_space' => true];
        $rules['yoda_style'] = ['equal' => false, 'identical' => false, 'less_and_greater' => false];

        return $rules;
    }

    /**
     * @return list<string>
     */
    private static function trailingCommaInMultilineElements(): array
    {
        $elements = ['arguments', 'arrays'];
        if (\PHP_VERSION_ID >= 80000) {
            $elements[] = 'match';
            $elements[] = 'parameters';
        }

        return $elements;
    }
}
