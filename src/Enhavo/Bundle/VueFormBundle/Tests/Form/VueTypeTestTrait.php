<?php

namespace Enhavo\Bundle\VueFormBundle\Tests\Form;

use Enhavo\Bundle\VueFormBundle\Form\Extension\VueTypeExtension;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class VueTypeTestTrait
 * @method getMockBuilder($className): MockBuilder
 */
trait VueTypeTestTrait
{
    protected function createVueTypeExtension($types)
    {
        $vueTypeExtensions = [];
        foreach ($types as $key => $type) {
            $vueTypeExtensions[is_string($key) ? $key : get_class($type)] = $type;
        }

        $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();
        $container->method('get')->willReturnCallback(function ($value) use ($vueTypeExtensions) {
            return $vueTypeExtensions[$value];
        });

        $vueTypeExtension = new VueTypeExtension();
        $vueTypeExtension->setContainer($container);

        foreach ($vueTypeExtensions as $class => $type) {
            $vueTypeExtension->register($class);
        }

        return $vueTypeExtension;
    }
}
