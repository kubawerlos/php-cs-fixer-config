<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dev\Builder;

use Dev\Builder\Modifier\LibraryRulesModifier;
use Dev\Builder\Modifier\ProjectRulesModifier;

/**
 * @internal
 */
final class Builder
{
    public function build(): void
    {
        $this->buildLibraryRules();
        $this->buildProjectRules();
    }

    private function buildLibraryRules(): void
    {
        $rules = new Rules();
        $rules->apply(new LibraryRulesModifier());

        $this->dumpClass('LibraryRules', $rules);
    }

    private function buildProjectRules(): void
    {
        $rules = new Rules();
        $rules->apply(new ProjectRulesModifier());

        $this->dumpClass('ProjectRules', $rules);
    }

    private function dumpClass(string $name, Rules $rules): void
    {
        $arrayRules = $rules->getRules();

        $arrayRules['trailing_comma_in_multiline'] = [
            'after_heredoc' => true,
            'elements' => '__TRAILING_COMMA_IN_MULTILINE__PLACEHOLDER__',
        ];

        $array = \var_export($arrayRules, true);
        $array = \preg_replace('/\d+\s*=>/', '', $array);
        $array = \str_replace("'__HEADER_PLACEHOLDER__'", '$this->header', $array);
        $array = \str_replace("'__TRAILING_COMMA_IN_MULTILINE__PLACEHOLDER__'", 'self::trailingCommaInMultilineElements()', $array);

        $path = __DIR__ . '/../../src/Rules/' . $name . '.php';

        $content = \file_get_contents($path);

        $getRulesPosition = \strpos($content, 'public function getRules()');
        $getRulesOpeningBracePosition = \strpos($content, '{', $getRulesPosition);
        $getRulesClosingBracePosition = \strpos($content, '}', $getRulesOpeningBracePosition);

        $content = \substr($content, 0, $getRulesOpeningBracePosition)
            . \sprintf('{ return %s; }', $array)
            . \substr($content, $getRulesClosingBracePosition + 1);

        foreach ($rules->getRules() as $rule => $config) {
            if (!\str_starts_with($rule, 'PhpCsFixerCustomFixers')) {
                continue;
            }
            $content = \str_replace(
                \sprintf("'%s'", $rule),
                \sprintf('Fixer\%sFixer::name()', \ucfirst(\str_replace([' ', 'PhpCsFixerCustomFixers/'], '', \ucwords(\str_replace('_', ' ', $rule))))),
                $content,
            );
        }

        \file_put_contents($path, $content);
    }
}
