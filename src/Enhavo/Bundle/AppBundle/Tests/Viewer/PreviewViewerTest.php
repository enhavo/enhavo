<?php

namespace Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\PreviewViewer;

class PreviewViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new PreviewViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\PreviewViewer', $viewer);
    }


    /**
     * Test if we get back the strategy, which we have pass over the configuration before
     */
    function testGetStrategy()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $configParser->method('get')->will($this->returnValueMap([
            ['strategy', 'strategyName'],
        ]));

        $viewer = new PreviewViewer();
        $viewer->setConfig($configParser);

        $this->assertEquals('strategyName', $viewer->getStrategyName());
    }
}
