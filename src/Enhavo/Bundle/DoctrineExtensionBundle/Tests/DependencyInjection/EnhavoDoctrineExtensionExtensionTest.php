<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\DependencyInjection;

use Enhavo\Bundle\DoctrineExtensionBundle\DependencyInjection\EnhavoDoctrineExtensionExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EnhavoDoctrineExtensionExtensionTest extends TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $extension = new EnhavoDoctrineExtensionExtensionChild();
        $extension->load([], $container);

        $this->assertEquals([], $container->getParameter('enhavo_doctrine_extension.metadata'));
    }
}

class EnhavoDoctrineExtensionExtensionChild extends EnhavoDoctrineExtensionExtension
{
    public const CONFIG_DIR = __DIR__.'/../../Resources/config';
}
