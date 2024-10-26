<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dev;

use PhpCsFixerConfig\FixInit;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 */
#[CoversClass(FixInit::class)]
final class FixInitTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        self::tearDownAfterClass();
    }

    public static function tearDownAfterClass(): void
    {
        (new Filesystem())->remove(__DIR__ . '/_');
    }

    public function testCreatingNew(): void
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir(__DIR__ . '/_/creating_new');
        $filesystem->dumpFile(__DIR__ . '/_/creating_new/composer.json', '{}');

        (new FixInit())->init(__DIR__ . '/_/creating_new');

        self::assertFileExists(__DIR__ . '/_/creating_new/.php-cs-fixer.php');
        self::assertStringEqualsFile(__DIR__ . '/_/creating_new/composer.json', <<<'JSON'
            {
                "scripts": {
                    "fix": [
                        "php-cs-fixer fix -vvv"
                    ]
                }
            }

            JSON);
    }

    public function testCreatingWhenConfigExists(): void
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir(__DIR__ . '/_/creating_when_config_exists');

        $configPath = \sprintf(__DIR__ . '%1$s_%1$screating_when_config_exists%1$s.php-cs-fixer.php', \DIRECTORY_SEPARATOR);
        $filesystem->dumpFile($configPath, '<?php return $foo;');

        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(\sprintf('PHP CS Fixer config (at %s) already exists.', $configPath));

        (new FixInit())->init(__DIR__ . '/_/creating_when_config_exists');
    }

    public function testCreatingWhenFixScriptExists(): void
    {
        $composerJsonContent = <<<'JSON'
            {
                "scripts": {
                    "fix": [
                        "foo"
                    ]
                }
            }

            JSON;

        $filesystem = new Filesystem();
        $filesystem->dumpFile(__DIR__ . '/_/creating_when_fix_script_exists/composer.json', $composerJsonContent);

        (new FixInit())->init(__DIR__ . '/_/creating_when_fix_script_exists');

        self::assertFileExists(__DIR__ . '/_/creating_when_fix_script_exists/.php-cs-fixer.php');
        self::assertStringEqualsFile(__DIR__ . '/_/creating_when_fix_script_exists/composer.json', $composerJsonContent);
    }
}
