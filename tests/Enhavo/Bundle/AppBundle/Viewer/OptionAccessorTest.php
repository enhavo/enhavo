<?php

namespace Enhavo\Bundle\AppBundle\Test\Viewer;

use Enhavo\Bundle\AppBundle\Viewer\OptionAccessor;
use PHPUnit\Framework\TestCase;

class OptionAccessorTest extends TestCase
{
    /**
     * Test if ConfigParser can be initialize
     *
     */
    function testInitialize()
    {
        $configParser = new OptionAccessor();
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Viewer\OptionAccessor', $configParser);
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
        $config = [
            'type' => 'viewer.table',
            'columns' => array(
                'id' => array(
                    'property' => 'id'
                )
            )
        ];

        $configParser = new OptionAccessor();
        $configParser->setDefaults([]);
        $configParser->resolve($config);

        $this->assertEquals('viewer.table', $configParser->get('type'));
        $this->assertEquals('id', $configParser->get('columns.id.property'));
        $this->assertEquals('viewer.table', $configParser->get('type'));
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
        $config = [
            'level1' => [
                'level2' => [
                    'level3' => null,
                    'other' => 'otherValue'
                ]
            ]
        ];

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

        $configParser = new OptionAccessor();
        $configParser->setDefaults($default);
        $configParser->resolve($config);

        $this->assertEquals('defaultType', $configParser->get('type'));
        $this->assertArraySubset(array(
            'level3' => null,
            'other' => 'otherValue',
            'type' => 'defaultLevel2Type'
        ), $configParser->get('level1.level2'));
    }
}
