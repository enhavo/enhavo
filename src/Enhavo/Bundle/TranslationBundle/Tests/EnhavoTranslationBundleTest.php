<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $this->assertCount(7, $config->getBeforeOptimizationPasses());
        $bundle->build($container);
        $this->assertCount(10, $config->getBeforeOptimizationPasses());
    }
}
