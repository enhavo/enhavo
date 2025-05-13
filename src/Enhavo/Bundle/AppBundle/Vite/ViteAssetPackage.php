<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Vite;

use Symfony\Component\Asset\PackageInterface;

class ViteAssetPackage implements PackageInterface
{
    public function __construct(
        private readonly ViteManager $viteManager,
        private array $builds,
    ) {
    }

    public function getVersion(string $path): string
    {
        return '';
    }

    public function getUrl(string $path): string
    {
        if (!str_starts_with($path, '/')) {
            $path = '/'.$path;
        }

        foreach ($this->builds as $build => $config) {
            if ($this->isSubPath($config['base'], $path)) {
                $file = substr($path, strlen($config['base']));
                if ($this->viteManager->isDev($file, $build)) {
                    return sprintf('http://localhost:%s%s', $config['port'], $path);
                }
            }
        }

        return $path;
    }

    private function isSubPath($basePath, $fullPath): bool
    {
        return str_starts_with($fullPath, $basePath);
    }
}
