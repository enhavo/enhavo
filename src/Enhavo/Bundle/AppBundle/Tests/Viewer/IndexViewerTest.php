<?php

namespace Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\IndexViewer;

class IndexViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new IndexViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\IndexViewer', $viewer);
    }

    /**
     * Test if we get the default parameters back, after we have define
     * a basic configuration set.
     *
     */
    function testReturnDefaultParameters()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();

        $securityContext = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $securityContext->method('isGranted')->willReturn(true);

        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $container->method('get')->willReturnCallback(function($id) use ($securityContext) {
            if('security.context' == $id) {
                return $securityContext;
            }
            return null;
        });

        $viewer = new IndexViewer();
        $viewer->setContainer($container);
        $viewer->setConfig($configParser);

        $parameters = $viewer->getParameters();
        $this->assertArrayHasKey('blocks', $parameters);
        $this->assertArrayHasKey('actions', $parameters);
        $this->assertArrayHasKey('viewer', $parameters);
    }
}
