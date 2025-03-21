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

use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\TypeDeclarationSpacesFixer;
use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;

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
        $rules['fully_qualified_strict_types'] = ['import_symbols' => true];
        $rules['increment_style'] = ['style' => 'post'];
        $rules['method_argument_space'] = ['on_multiline' => 'ensure_fully_multiline'];
        $rules['native_constant_invocation'] = ['scope' => 'namespaced', 'strict' => true];
        $rules['native_function_invocation'] = ['include' => ['@all'], 'scope' => 'namespaced', 'strict' => true];
        $rules['no_extra_blank_lines'] = ['tokens' => self::noExtraBlankLinesTokens()];
        $rules['no_superfluous_phpdoc_tags'] = ['remove_inheritdoc' => true];
        $rules['numeric_literal_separator'] = ['strategy' => 'no_separator'];
        $rules['php_unit_data_provider_static'] = ['force' => true];
        $rules['php_unit_test_case_static_method_calls'] = [
            'call_type' => 'self',
            'methods' => [
                'any' => 'this',
                'atLeast' => 'this',
                'atLeastOnce' => 'this',
                'atMost' => 'this',
                'exactly' => 'this',
                'never' => 'this',
                'onConsecutiveCalls' => 'this',
                'once' => 'this',
                'returnArgument' => 'this',
                'returnCallback' => 'this',
                'returnSelf' => 'this',
                'returnValue' => 'this',
                'returnValueMap' => 'this',
                'throwException' => 'this',
            ],
        ];
        $rules['phpdoc_line_span'] = ['property' => 'single'];
        $rules['string_implicit_backslashes'] = ['single_quoted' => 'escape'];
        $rules['trailing_comma_in_multiline'] = ['after_heredoc' => true, 'elements' => self::trailingCommaInMultilineElements()];
        $rules['type_declaration_spaces'] = ['elements' => self::typeDeclarationSpacesElements()];
        $rules['whitespace_after_comma_in_array'] = ['ensure_single_space' => true];
        $rules['yoda_style'] = ['equal' => false, 'identical' => false, 'less_and_greater' => false];

        return $rules;
    }

    /**
     * @return list<string>
     */
    private static function noExtraBlankLinesTokens(): array
    {
        return \array_diff(
            (new \ReflectionClass(NoExtraBlankLinesFixer::class))->getConstant('AVAILABLE_TOKENS'),
            ['use_trait'],
        );
    }

    /**
     * @return list<string>
     */
    private static function trailingCommaInMultilineElements(): array
    {
        $fixerConfigurationResolver = \Closure::bind(
            static fn (TrailingCommaInMultilineFixer $fixer): FixerConfigurationResolverInterface => $fixer->getConfigurationDefinition(),
            null,
            TrailingCommaInMultilineFixer::class,
        )(new TrailingCommaInMultilineFixer());

        return self::getAllowedValuesForOption($fixerConfigurationResolver, 'elements');
    }

    private static function typeDeclarationSpacesElements(): array
    {
        $fixerConfigurationResolver = \Closure::bind(
            static fn (TypeDeclarationSpacesFixer $fixer): FixerConfigurationResolverInterface => $fixer->getConfigurationDefinition(),
            null,
            TypeDeclarationSpacesFixer::class,
        )(new TypeDeclarationSpacesFixer());

        return self::getAllowedValuesForOption($fixerConfigurationResolver, 'elements');
    }

    private static function getAllowedValuesForOption(FixerConfigurationResolverInterface $fixerConfigurationResolver, string $optionName): array
    {
        foreach ($fixerConfigurationResolver->getOptions() as $option) {
            if ($option->getName() !== $optionName) {
                continue;
            }

            $allowedValues = $option->getAllowedValues()[0];
            \assert($allowedValues instanceof AllowedValueSubset);

            return $allowedValues->getAllowedValues();
        }
    }
}
