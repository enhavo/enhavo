<?php

namespace spec\Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Viewer\Viewer\CreateViewer;
use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;

class CreateViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new CreateViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\Viewer\CreateViewer', $viewer);
    }

    function testType()
    {
        $viewer = new CreateViewer();
        $this->assertEquals('create', $viewer->getType());
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
            return null;
        }));

        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();
        $formMock->method('isSubmitted')->willReturn(false);
        $formMock->method('createView')->willReturn('formViewMock');

        $resource = new EntityMock();

        $optionAccessor = new OptionAccessor();

        $configurationMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface')->getMock();
        $configurationMock->method('getTemplate')->willReturn('template');

        $metadataMock = $this->getMockBuilder('Sylius\Component\Resource\Metadata\MetadataInterface')->getMock();
        $metadataMock->method('getApplicationName')->willReturn('acme');
        $metadataMock->method('getHumanizedName')->willReturn('entity');

        $viewer = new CreateViewer();
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
            'buttons' => [
                'cancel' => [
                    'type' => 'cancel',
                ],
                'save' => [
                    'type' => 'save',
                ]
            ],
            'form' => 'formViewMock',
            'tabs' => [
                'entity' => [
                    'label' => 'entity.label.entity',
                    'template' => 'EnhavoAppBundle:Tab:default.html.twig'
                ]
            ],
            'form_template' => 'EnhavoAppBundle:View:tab.html.twig',
            'form_action' => 'url',
            'data' => $resource
        ], $view->getTemplateData());
    }
}
