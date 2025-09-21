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

use PhpCsFixerCustomFixers\Fixer\PhpdocOnlyAllowedAnnotationsFixer;

/**
 * @internal
 */
final class ProjectRulesModifier
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
                'header_comment',
                'php_unit_internal_class',
                PhpdocOnlyAllowedAnnotationsFixer::name(),
            ] as $rule
        ) {
            $rules[$rule] = false;
        }

        return $rules;
    }
}
