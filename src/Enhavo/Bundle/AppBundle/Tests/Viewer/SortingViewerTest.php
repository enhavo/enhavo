<?php

namespace Enhavo\Bundle\AppBundle\Tests\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\SortingViewer;

class SortingViewerTest extends \PHPUnit_Framework_TestCase
{
    function testInitialize()
    {
        $viewer = new SortingViewer();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\SortingViewer', $viewer);
    }

    /**
     * Test if we get the sorting parameters back, after we have define
     * a sorting
     *
     */
    function testSettedSortingParameters()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $configParser->method('get')->will($this->returnValueMap(
            [
                ['sorting', 'asc'],
            ]
        ));

        $sortingViewer = new SortingViewer();
        $sortingViewer->setConfig($configParser);

        $parameters = $sortingViewer->getParameters();
        $this->assertArrayHasKey('sorting', $parameters);
        $this->assertEquals('ASC', $parameters['sorting']);
    }

    /**
     * Test if we get back a default sorting parameters
     *
     */
    function testDefaultSortingParameters()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();

        $sortingViewer = new SortingViewer();
        $sortingViewer->setConfig($configParser);

        $parameters = $sortingViewer->getParameters();
        $this->assertArrayHasKey('sorting', $parameters);
        $this->assertEquals('DESC', $parameters['sorting']);
    }

    /**
     * Test if exceptions is thrown after setting a undefined sorting parameter
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     *
     */
    function testUndefinedSortingParameters()
    {
        $configParser = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $configParser->method('get')->will($this->returnValueMap(
            [
                ['sorting', 'thisIsNotDefined'],
            ]
        ));

        $sortingViewer = new SortingViewer();
        $sortingViewer->setConfig($configParser);
        $sortingViewer->getParameters();
    }
}
