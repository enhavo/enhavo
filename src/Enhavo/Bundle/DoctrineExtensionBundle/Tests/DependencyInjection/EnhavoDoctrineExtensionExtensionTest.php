<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 23:32
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
    const CONFIG_DIR = __DIR__.'/../../Resources/config';
}
