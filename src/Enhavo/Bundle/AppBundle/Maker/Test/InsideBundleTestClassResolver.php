<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Maker\Test;

class InsideBundleTestClassResolver implements TestClassResolverInterface
{
    public function __construct(
        private string $projectDir,
    ) {
    }

    public function getClassName(string $originalFqcn): string
    {
        $reflector = new \ReflectionClass($originalFqcn);

        return sprintf('%sTest', $reflector->getShortName());
    }

    public function getNamespace(string $originalFqcn): string
    {
        $reflector = new \ReflectionClass($originalFqcn);
        $parts = explode('\\', $reflector->getNamespaceName());

        $newParts = [];
        $isBundle = false;
        foreach ($parts as $part) {
            if ($isBundle) {
                $newParts[] = 'Tests';
                $isBundle = null;
            } elseif (false === $isBundle && preg_match('/.+Bundle$/', $part)) {
                $isBundle = true;
            }
            $newParts[] = $part;
        }

        return implode('\\', $newParts);
    }

    public function getPath(string $originalFqcn): string
    {
        $namespace = $this->getNamespace($originalFqcn);
        $namespacePath = str_replace('\\', '/', $namespace);

        return sprintf('%s/src/%s/%s.php', $this->projectDir, $namespacePath, $this->getClassName($originalFqcn));
    }

    public function supports(string $originalFqcn): bool
    {
        $reflector = new \ReflectionClass($originalFqcn);
        $parts = explode('\\', $reflector->getNamespaceName());

        foreach ($parts as $part) {
            if (preg_match('/.+Bundle$/', $part)) {
                return true;
            }
        }

        return false;
    }
}
