<?php

namespace Enhavo\Bundle\AppBundle\Viewer\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;
use Enhavo\Bundle\AppBundle\Viewer\Viewer\IndexViewer;

class IndexViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new IndexViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\Viewer\IndexViewer', $viewer);
    }

    function testType()
    {
        $viewer = new IndexViewer();
        $this->assertEquals('index', $viewer->getType());
    }

    function testCreateView()
    {
        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $containerMock->method('getParameter')->will($this->returnCallback(function($id) {
            if($id == 'enhavo_app.stylesheets') {
                return ['path/to/style.css'];
            }
            if($id == 'enhavo_app.javascripts') {
                return ['path/to/main.js',];
            }
            return [];
        }));


        $optionAccessor = new OptionAccessor();

        $configurationMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface')->getMock();
        $configurationMock->method('getTemplate')->willReturn('template');

        $metadataMock = $this->getMockBuilder('Sylius\Component\Resource\Metadata\MetadataInterface')->getMock();
        $metadataMock->method('getApplicationName')->willReturn('acme');
        $metadataMock->method('getHumanizedName')->willReturn('entity');

        $viewer = new IndexViewer();
        $viewer->setConfiguration($configurationMock);
        $viewer->setMetadata($metadataMock);
        $viewer->setOptionAccessor($optionAccessor);
        $viewer->configureOptions($optionAccessor);
        $optionAccessor->resolve([]);

        $viewer->setContainer($containerMock);

        $view = $viewer->createView();
        $this->assertInstanceOf('FOS\RestBundle\View\View', $view);

        $this->assertArraySubset([
            'blocks' => [
                'table' => [
                    'type' => 'table',
                    'table_route' => 'acme_entity_table',
                    'update_route' => 'acme_entity_update',
                ]
            ],
            'actions' => [
                'create' => [
                    'type' => 'create',
                    'route' => 'acme_entity_create',
                ]
            ]
        ], $view->getTemplateData());
    }
}
