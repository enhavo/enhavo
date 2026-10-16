<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Kernel;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\KernelInterface;

class KernelManager
{
    private ?KernelInterface $kernel = null;
    private bool $envLoaded = false;

    public function __construct(
        private readonly string $class,
        private readonly string $environment,
        private readonly bool $debug,
        private readonly ?string $dotenvPath = null,
    ) {
    }

    public function getKernel(): KernelInterface
    {
        if (null === $this->kernel) {
            $this->loadEnv();
            $this->kernel = new ($this->class)($this->environment, $this->debug);
            $this->kernel->boot();
        }

        return $this->kernel;
    }

    public function shutdown(): void
    {
        $this->kernel?->shutdown();
        $this->kernel = null;
    }

    private function loadEnv(): void
    {
        if ($this->envLoaded) {
            return;
        }
        $this->envLoaded = true;

        $_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = $this->environment;
        $_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = $this->debug ? '1' : '0';

        if (null !== $this->dotenvPath) {
            (new Dotenv())->bootEnv($this->dotenvPath);
        }
    }
}
