<?php

namespace Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\AppViewer;

class AppViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $appViewer = new AppViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\AppViewer', $appViewer);
    }

    /**
     * Test if we get the default parameters back, after we have define
     * a basic configuration set.
     *
     */
    function testReturnDefaultParameters()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();

        $appViewer = new AppViewer();
        $appViewer->setConfig($configParser);

        $parameters = $appViewer->getParameters();
        $this->assertArrayHasKey('blocks', $parameters);
        $this->assertArrayHasKey('actions', $parameters);
    }
}
