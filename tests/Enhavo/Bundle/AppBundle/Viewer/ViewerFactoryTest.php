<?php

namespace spec\Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Mock\EntityMock;
use Enhavo\Bundle\AppBundle\Viewer\ViewerFactory;

class ViewerFactoryTest extends \PHPUnit_Framework_TestCase
{
    function testCreate()
    {
        $viewerMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\ViewerInterface')->getMock();

        $typeCollectorMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Type\TypeCollector')
            ->disableOriginalConstructor()
            ->getMock();
        $typeCollectorMock->method('getType')->willReturn($viewerMock);

        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();

        $factory = new ViewerFactory($containerMock, $typeCollectorMock);


        $configurationMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface')->getMock();
        $configurationMock->method('getViewerOptions')->willReturn([]);

        $metadataMock = $this->getMockBuilder('Sylius\Component\Resource\Metadata\MetadataInterface')->getMock();
        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();
        $resourceMock = new EntityMock();

        $viewer = $factory->create(
            $configurationMock,
            $metadataMock,
            $resourceMock,
            $formMock,
            'type'
        );

        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\ViewerInterface', $viewer);
    }
}
