<?php

declare(strict_types=1);

/*
 * This file is part of kubawerlos/php-cs-fixer-config.
 *
 * (c) 2020 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

require __DIR__ . '/../../vendor/autoload.php';

$builder = new PhpCsFixerConfig\Builder\Builder();
$builder->build();

$process = Symfony\Component\Process\Process::fromShellCommandline('php ' . __DIR__ . '/../../vendor/bin/php-cs-fixer fix');
$exitCode = $process->run();

if ($exitCode > 0) {
    throw new RuntimeException($process->getErrorOutput());
}
