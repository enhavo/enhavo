<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 29/09/16
 * Time: 17:29
 */

namespace Enhavo\Bundle\SettingBundle\Provider;

use Enhavo\Bundle\SettingBundle\Provider\ParameterProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ParameterProviderTest extends TestCase
{
    private function getContainerMock()
    {
        $mock = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $mock->method('getParameter')->willReturn('parameter');
        return $mock;
    }

    public function testGetSetting()
    {
        $container = $this->getContainerMock();
        $parameterProvider = new ParameterProvider($container);
        $parameter = $parameterProvider->getSetting('key');
        static::assertEquals('parameter', $parameter);
    }
}