<?php declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace PhpCsFixerConfig;

use Composer\Json\JsonFile;

/**
 * @internal
 */
final class FixInit
{
    public function init(string $cwd): void
    {
        $this->initConfig($cwd);
        $this->initScript($cwd);
    }

    private function initConfig(string $cwd): void
    {
        $path = $cwd . \DIRECTORY_SEPARATOR . '.php-cs-fixer.php';
        if (\file_exists($path)) {
            throw new \RuntimeException(\sprintf('PHP CS Fixer config (at %s) already exists.', $path));
        }
        \file_put_contents(
            $path,
            <<<'PHP'
                <?php declare(strict_types=1);

                require __DIR__ . '/vendor/autoload.php';

                use PhpCsFixer\Finder;
                use PhpCsFixerConfig\Factory;

                return Factory::createForProject()
                    ->setFinder(
                        Finder::create()
                            ->files()
                            ->ignoreDotFiles(false)
                            ->in(__DIR__),
                    );

                PHP,
        );
    }

    private function initScript(string $cwd): void
    {
        $pharPath = \exec('which composer');
        if ($pharPath === '') {
            throw new \RuntimeException('Composer not found!' . \PHP_EOL);
        }

        $pharLoaded = \Phar::loadPhar($pharPath, 'composer.phar');
        if (!$pharLoaded) {
            throw new \RuntimeException(\sprintf('Composer "%s" not loaded!' . \PHP_EOL, $pharPath));
        }
        require_once 'phar://composer.phar/vendor/autoload.php';

        $composerJsonPath = $cwd . \DIRECTORY_SEPARATOR . 'composer.json';
        if (!\file_exists($composerJsonPath)) {
            throw new \RuntimeException(\sprintf('File "%s" does not exist!' . \PHP_EOL, $composerJsonPath));
        }

        $json = new JsonFile($composerJsonPath);
        $jsonData = $json->read();

        if (!\array_key_exists('scripts', $jsonData)) {
            $jsonData['scripts'] = [];
        }
        if (!\array_key_exists('fix', $jsonData['scripts'])) {
            $jsonData['scripts']['fix'] = ['php-cs-fixer fix -vvv'];
        }

        $json->write($jsonData);
    }
}
