<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dev\Test\AutoReview;

use PhpCsFixerConfig\Factory;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @internal
 */
#[CoversNothing]
final class SrcCodeTest extends TestCase
{
    public function testOnlyFactoryClassIsPublic(): void
    {
        $finder = Finder::create()
            ->files()
            ->in(__DIR__ . '/../../../src')
            ->notName('build.php');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            require_once $file->getRealPath();
        }

        foreach (\get_declared_classes() as $class) {
            if (\mb_strpos($class, 'PhpCsFixerConfig\\') !== 0) {
                continue;
            }
            $reflectionClass = new \ReflectionClass($class);

            self::assertIsString($reflectionClass->getDocComment(), \sprintf('Class %s does not have doc comment.', $class));

            self::assertSame(
                $class === Factory::class,
                \mb_strpos($reflectionClass->getDocComment(), '@internal') === false,
                \sprintf('Class %s has "@internal" tag incorrectly.', $class),
            );
        }
    }
}
