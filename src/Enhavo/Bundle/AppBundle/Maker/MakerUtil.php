<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Maker;

use Enhavo\Bundle\AppBundle\Util\NameTransformer;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MakerUtil
{
    private NameTransformer $nameTransformer;

    public function __construct(
        private KernelInterface $kernel,
    ) {
        $this->nameTransformer = new NameTransformer();
    }

    public function getBundleNameWithoutPostfix($bundle)
    {
        $result = $bundle;
        if ($bundle instanceof BundleInterface) {
            $result = $bundle->getName();
        }

        $matches = [];
        if (preg_match('/^(.+)Bundle$/', $result, $matches)) {
            $result = $matches[1];
        }

        return $result;
    }

    public function getBundlePath($bundleName): string
    {
        return $this->kernel->locateResource(sprintf('@%s', $bundleName));
    }

    public function getBundleUrl($bundleName): string
    {
        return preg_replace('/_/', '/', $this->nameTransformer->snakeCase($this->getBundleNameWithoutPostfix($bundleName)));
    }

    public function getBundleNamespace($bundleName): string
    {
        $bundle = $this->kernel->getBundle($bundleName);
        $class = get_class($bundle);
        $namespace = substr($class, 0, strlen($class) - strlen($bundleName) - 1);

        return $namespace;
    }

    public function getResourceUrl(string $name): string
    {
        return $this->nameTransformer->kebabCase($name);
    }

    public function getRealpath(string $path): string
    {
        return $this->kernel->locateResource($path);
    }

    public function getProjectPath(): string
    {
        return $this->kernel->getProjectDir();
    }
}
