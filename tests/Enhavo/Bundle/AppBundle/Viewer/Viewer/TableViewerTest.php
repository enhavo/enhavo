<?php

namespace spec\Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Viewer\Viewer\TableViewer;
use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;
use PHPUnit\Framework\TestCase;

class TableViewerTest extends TestCase
{
    function testInitialize()
    {
        $viewer = new TableViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\Viewer\TableViewer', $viewer);
    }

    function testType()
    {
        $viewer = new TableViewer();
        $this->assertEquals('table', $viewer->getType());
    }

    function testCreateView()
    {
        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $containerMock->method('get')->will($this->returnCallback(function($id) {
            if($id == 'router') {
                $routerMock = $this->getMockBuilder('Symfony\Component\Routing\RouterInterface')->getMock();
                $routerMock->method('generate')->willReturn('url');
                return $routerMock;
            }
            if($id == 'sylius.resource_controller.request_configuration_factory') {
                $requestFactoryMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfigurationFactory')
                    ->disableOriginalConstructor()
                    ->getMock();
                $requestFactoryMock->method('createFromRoute')->willReturn(null);
                return $requestFactoryMock;
            }
            return null;
        }));

        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();
        $formMock->method('isSubmitted')->willReturn(false);
        $formMock->method('createView')->willReturn('formViewMock');

        $resource = new EntityMock();

        $optionAccessor = new OptionAccessor();

        $configurationMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfiguration')
            ->disableOriginalConstructor()
            ->getMock();
        $configurationMock->method('isSortable')->willReturn(false);
        $configurationMock->method('getTemplate')->willReturn('template');

        $metadataMock = $this->getMockBuilder('Sylius\Component\Resource\Metadata\MetadataInterface')->getMock();
        $metadataMock->method('getApplicationName')->willReturn('acme');
        $metadataMock->method('getHumanizedName')->willReturn('entity');

        $viewer = new TableViewer();
        $viewer->setConfiguration($configurationMock);
        $viewer->setMetadata($metadataMock);
        $viewer->setForm($formMock);
        $viewer->setResource($resource);
        $viewer->setOptionAccessor($optionAccessor);
        $viewer->configureOptions($optionAccessor);
        $optionAccessor->resolve([]);

        $viewer->setContainer($containerMock);

        $view = $viewer->createView();
        $this->assertInstanceOf('FOS\RestBundle\View\View', $view);

        $this->assertArraySubset([
            'data' => $resource,
            'sortable' =>'',
            'columns' => [
                'id' => [
                    'label' => 'id',
                    'property' => 'id',
                    'type' => 'property'
                ]
            ],
            'batches' => [],
            'batch_route' => 'acme_entity_batch',
            'width' => 12,
            'move_after_route' => 'acme_entity_move_after',
            'move_to_page_route' => 'acme_entity_move_to_page',
        ], $view->getTemplateData());
    }
}