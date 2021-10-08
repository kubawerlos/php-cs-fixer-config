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

namespace PhpCsFixerConfig\Builder;

use PhpCsFixerConfig\Builder\Modifier\LibraryRulesModifier;
use PhpCsFixerConfig\Builder\Modifier\ProjectRulesModifier;

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
        $array = \var_export($rules->getRules(), true);
        $array = \preg_replace('/\d+\s*=>/', '', $array);
        $array = \str_replace("'__HEADER_PLACEHOLDER__'", '$this->header', $array);

        $path = __DIR__ . '/../Rules/' . $name . '.php';

        $content = \file_get_contents($path);

        $content = \substr($content, 0, \strpos($content, 'public function getRules()'))
            . \sprintf('public function getRules(): array { return %s; }}', $array);

        foreach ($rules->getRules() as $rule => $config) {
            if (!\str_starts_with($rule, 'PhpCsFixerCustomFixers')) {
                continue;
            }
            $content = \str_replace(
                \sprintf("'%s'", $rule),
                \sprintf('Fixer\\%sFixer::name()', \ucfirst(\str_replace([' ', 'PhpCsFixerCustomFixers/'], '', \ucwords(\str_replace('_', ' ', $rule))))),
                $content
            );
        }

        \file_put_contents($path, $content);
    }
}
