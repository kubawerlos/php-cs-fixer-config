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

namespace PhpCsFixerConfig\Rules;

/**
 * @internal
 */
interface RulesInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getRules(): array;
}
