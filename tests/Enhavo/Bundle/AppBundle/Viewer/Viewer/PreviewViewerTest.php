<?php

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\Viewer\PreviewViewer;
use Symfony\Component\HttpFoundation\Response;

class PreviewViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new PreviewViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\Viewer\PreviewViewer', $viewer);
    }

    function testType()
    {
        $viewer = new PreviewViewer();
        $this->assertEquals('preview', $viewer->getType());
    }

    function testCreateView()
    {
        $containerMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $containerMock->method('get')->will($this->returnCallback(function($id) {
            if($id == 'enhavo_app.preview.strategy_resolver') {
                $strategyMock =  $this->getMockBuilder('Enhavo\Bundle\AppBundle\Preview\StrategyInterface')->getMock();
                $strategyMock->method('getPreviewResponse')->willReturn(new Response('content'));

                $strategyResolverMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Preview\StrategyResolver')
                    ->disableOriginalConstructor()
                    ->getMock();
                $strategyResolverMock->method('getStrategy')->willReturn($strategyMock);
                return $strategyResolverMock;
            }
            return null;
        }));

        $optionAccessorMock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\OptionAccessor')->getMock();
        $optionAccessorMock->method('get')->will($this->returnCallback(function($id) {
            if($id == 'strategy') {
                return 'strategy';
            }
            return null;
        }));

        $viewer = new PreviewViewer();
        $viewer->setOptionAccessor($optionAccessorMock);
        $viewer->setContainer($containerMock);

        $view = $viewer->createView();
        $response = $view->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('content', $response->getContent());
    }
}
