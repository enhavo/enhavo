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

        $viewer = new IndexViewer();
        $viewer->setConfig($configParser);

        $parameters = $viewer->getParameters();
        $this->assertArrayHasKey('blocks', $parameters);
        $this->assertArrayHasKey('actions', $parameters);
        $this->assertArrayHasKey('viewer', $parameters);
    }
}
