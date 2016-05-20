<?php

namespace Enhavo\Bundle\AppBundle\Test\Config;

use Enhavo\Bundle\AppBundle\Config\ConfigParser;

class ConfigParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if ConfigParser can be initialize
     *
     */
    function testInitialize()
    {
        $configParser = new ConfigParser();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Config\ConfigParser', $configParser);
    }

    /**
     * Test if ConfigParser returns the correct values
     * for passed parameters. It should be possible to
     * chain config keys to one path. e.g. "viewer.table"
     * for accessing table inside viewer
     *
     */
    function testParsingConfig()
    {
        $request = $this->createRequestMockWithConfig(
            [
                'type' => 'viewer.table',
                'columns' => array(
                    'id' => array(
                        'property' => 'id'
                    )
                )
            ]
        );

        $configParser = new ConfigParser();
        $configParser->parse($request);
        $this->assertEquals('viewer.table', $configParser->get('type'));
        $this->assertEquals('id', $configParser->get('columns.id.property'));
        $this->assertEquals('viewer.table', $configParser->getType());
    }

    /**
     * Test if we get pack right config if a default config was
     * set before. It should merge the config together, but the
     * config, which was set by the request will overwrite the default
     * config
     *
     */
    function testMergingWithDefaultConfig()
    {
        $request = $this->createRequestMockWithConfig(
            [
                'level1' => [
                    'level2' => [
                        'level3' => null,
                        'other' => 'otherValue'
                    ]
                ]
            ]
        );

        $default = array(
            'type' => 'defaultType',
            'level1' => array(
                'level2' => array(
                    'level3' => 'notWorth',
                    'type' => 'defaultLevel2Type'
                )
            )
        );

        /**
         * both array should be merged together to this
         *
         * array(
         *   'type' => 'defaultType',
         *   'level1' => array(
         *     'level2' => array(
         *       'level3' => null,
         *       'type' => 'defaultLevel2Type',
         *       'other' => 'otherValue'
         *     )
         *   )
         * );
         *
         */

        $configParser = new ConfigParser();
        $configParser->parse($request);
        $configParser->setDefault($default);
        $this->assertEquals('defaultType', $configParser->get('type'));

        $this->assertArraySubset(array(
            'level3' => null,
            'other' => 'otherValue',
            'type' => 'defaultLevel2Type'
        ), $configParser->get('level1.level2'));
    }

    /**
     * @param $config
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequestMockWithConfig($config)
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();
        $request->method('get')->with('_viewer')->willReturn($config);
        return $request;
    }
}
