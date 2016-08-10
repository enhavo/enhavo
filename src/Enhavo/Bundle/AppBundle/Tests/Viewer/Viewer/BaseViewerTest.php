<?php

namespace Enhavo\Bundle\AppBundle\Tests\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\Viewer\BaseViewer;

class BaseViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new BaseViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\Viewer\BaseViewer', $viewer);
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

        $optionAccessorMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\OptionAccessor')->getMock();
        $optionAccessorMock->method('get')->will($this->returnCallback(function($id) {
            if($id == 'stylesheets') {
                return [
                    'path/to/style.css',
                    'path/to/admin.css',
                ];
            }
            if($id == 'javascripts') {
                return [
                    'path/to/main.js',
                    'path/to/basic.js',
                ];
            }
            return [];
        }));

        $configurationMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface')->getMock();
        $configurationMock->method('getTemplate')->willReturn('template');

        $viewer = new BaseViewer();
        $viewer->setContainer($containerMock);
        $viewer->setOptionAccessor($optionAccessorMock);
        $viewer->setConfiguration($configurationMock);

        $view = $viewer->createView();
        $this->assertInstanceOf('FOS\RestBundle\View\View', $view);

        $data = $view->getTemplateData();
        $this->assertArrayHasKey('javascripts', $data);
        $this->assertArrayHasKey('stylesheets', $data);
    }
}
