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

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixerCustomFixers\Fixers;

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
        $rules['no_alias_functions'] = ['sets' => self::getFullSet('no_alias_functions', 'sets')];
        $rules['no_extra_blank_lines'] = [
            'tokens' => \array_diff(
                self::getFullSet('no_extra_blank_lines', 'tokens'),
                ['use_trait'],
            ),
        ];
        $rules['no_superfluous_phpdoc_tags'] = ['remove_inheritdoc' => true];
        $rules['no_unneeded_control_parentheses'] = ['statements' => self::getFullSet('no_unneeded_control_parentheses', 'statements')];
        $rules['numeric_literal_separator'] = ['strategy' => 'no_separator'];
        $rules['php_unit_data_provider_static'] = ['force' => true];
        $rules['php_unit_internal_class'] = ['types' => self::getFullSet('php_unit_internal_class', 'types')];
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
        $rules['phpdoc_order_by_value'] = ['annotations' => self::getFullSet('phpdoc_order_by_value', 'annotations')];
        $rules['phpdoc_tag_no_named_arguments'] = ['fix_internal' => false];
        $rules['single_space_around_construct'] = ['constructs_preceded_by_a_single_space' => self::getFullSet('single_space_around_construct', 'constructs_preceded_by_a_single_space')];
        $rules['string_implicit_backslashes'] = ['single_quoted' => 'escape'];
        $rules['trailing_comma_in_multiline'] = [
            'after_heredoc' => true,
            'elements' => self::getFullSet('trailing_comma_in_multiline', 'elements'),
        ];
        $rules['type_declaration_spaces'] = ['elements' => self::getFullSet('type_declaration_spaces', 'elements')];
        $rules['whitespace_after_comma_in_array'] = ['ensure_single_space' => true];
        $rules['yoda_style'] = ['equal' => false, 'identical' => false, 'less_and_greater' => false];

        return $rules;
    }

    private static function getFullSet(string $fixerName, string $optionName): array
    {
        $fixers = (new FixerFactory())
            ->registerBuiltInFixers()
            ->registerCustomFixers(\iterator_to_array(new Fixers()))
            ->useRuleSet(new RuleSet([$fixerName => true]))
            ->getFixers();

        $fixerConfigurationResolver = \Closure::bind(
            static fn (ConfigurableFixerInterface $fixer): FixerConfigurationResolverInterface => $fixer->getConfigurationDefinition(),
            null,
            \get_class($fixers[0]),
        )($fixers[0]);

        foreach ($fixerConfigurationResolver->getOptions() as $option) {
            if ($option->getName() !== $optionName) {
                continue;
            }

            $allowedValueSubset = $option->getAllowedValues()[0];
            \assert($allowedValueSubset instanceof AllowedValueSubset);

            $allowedValues = $allowedValueSubset->getAllowedValues();
            \sort($allowedValues, \SORT_FLAG_CASE | \SORT_STRING);

            return $allowedValues;
        }
    }
}
