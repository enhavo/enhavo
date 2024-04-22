<?php

namespace Enhavo\Bundle\AppBundle\Maker\Test;

interface TestClassResolverInterface
{
    public function getClassName(string $originalFqcn): string;
    public function getNamespace(string $originalFqcn): string;
    public function getPath(string $originalFqcn): string;
    public function supports(string $originalFqcn): bool;
}
