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

use Dev\Builder\Modifier\NonDefaultConfiguration;
use Dev\Builder\Modifier\UnwantedRulesFilter;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixerCustomFixers\Fixers;

/**
 * @internal
 */
final class Rules
{
    /** @var array<string, mixed> */
    private array $rules = [];

    public function __construct()
    {
        $fixersFactory = new FixerFactory();
        $fixersFactory->registerBuiltInFixers();

        foreach ($fixersFactory->getFixers() as $fixer) {
            if ($fixer instanceof DeprecatedFixerInterface) {
                continue;
            }
            $this->rules[$fixer->getName()] = true;
        }

        \ksort($this->rules);

        foreach (new Fixers() as $fixer) {
            if ($fixer instanceof DeprecatedFixerInterface) {
                continue;
            }
            $this->rules[$fixer->getName()] = true;
        }

        $this->apply(new NonDefaultConfiguration());
        $this->apply(new UnwantedRulesFilter());
    }

    public function apply(callable $closure): void
    {
        $this->rules = $closure($this->rules);
    }

    /**
     * @return array<string, mixed>
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
