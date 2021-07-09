<?php

namespace Enhavo\Bundle\TranslationBundle\Tests;

use Enhavo\Bundle\TranslationBundle\EnhavoTranslationBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoTranslationBundleTest extends TestCase
{
    public function test()
    {
        $container = new ContainerBuilder();
        $config = $container->getCompilerPassConfig();
        $bundle = new EnhavoTranslationBundle();
        $this->assertCount(4, $config->getBeforeOptimizationPasses());
        $bundle->build($container);
        $this->assertCount(7, $config->getBeforeOptimizationPasses());
    }
}
