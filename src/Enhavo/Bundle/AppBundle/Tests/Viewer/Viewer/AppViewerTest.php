<?php

namespace Enhavo\Bundle\AppBundle\Tests\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\Viewer\BaseViewer;

class AppViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new BaseViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\BaseViewer', $viewer);
    }

    function testType()
    {
        $viewer = new BaseViewer();
        $this->assertEquals('base', $viewer->getType());
    }

    function testView()
    {
        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $containerMock->method('getParameter')->will($this->returnCallback(function($id) {
            if($id == 'enhavo_app.stylesheets') {
                return [
                    'path/to/style.css',
                    'path/to/admin.css',
                ];
            }
            if($id == 'enhavo_app.javascripts') {
                return [
                    'path/to/main.js',
                    'path/to/basic.js',
                ];
            }
            return [];
        }));

        $viewer = new BaseViewer();
        $viewer->setContainer($containerMock);

        $view = $viewer->createView();
        $this->assertInstanceOf('FOS\RestBundle\View\View', $view);
    }
}
