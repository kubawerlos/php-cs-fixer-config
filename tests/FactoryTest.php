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

namespace Tests;

use PhpCsFixer\Config;
use PhpCsFixerConfig\Factory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \PhpCsFixerConfig\Factory
 *
 * @internal
 */
final class FactoryTest extends TestCase
{
    public function testCreateForLibrary(): void
    {
        self::assertInstanceOf(
            Config::class,
            Factory::createForLibrary('library name', 'library author', 2000)
        );
    }

    public function testCreateForProject(): void
    {
        self::assertInstanceOf(
            Config::class,
            Factory::createForProject()
        );
    }
}
