<?php

namespace Enhavo\Bundle\AppBundle\Tests\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\Viewer\AppViewer;

class AppViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new AppViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\Viewer\AppViewer', $viewer);
    }

    function testType()
    {
        $viewer = new AppViewer();
        $this->assertEquals('app', $viewer->getType());
    }

    function testCreateView()
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
            if($id == 'blocks') {
                return ['blockOne', 'blockTwo'];
            }
            if($id == 'actions') {
                return ['actionOne', 'actionTwo'];
            }
            if($id == 'title') {
                return 'title';
            }
            return [];
        }));

        $configurationMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface')->getMock();
        $configurationMock->method('getTemplate')->willReturn('template');

        $viewer = new AppViewer();
        $viewer->setOptionAccessor($optionAccessorMock);
        $viewer->setConfiguration($configurationMock);
        $viewer->setContainer($containerMock);

        $view = $viewer->createView();
        $this->assertInstanceOf('FOS\RestBundle\View\View', $view);

        $this->assertArraySubset([
            'title' => 'title',
            'blocks' => ['blockOne', 'blockTwo'],
            'actions' => ['actionOne', 'actionTwo']
        ], $view->getTemplateData());
    }
}
