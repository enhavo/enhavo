<?php

namespace spec\Enhavo\Bundle\AdminBundle\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class ConfigParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enhavo\Bundle\AdminBundle\Config\ConfigParser');
    }

    function it_should_give_back_configs(Request $request)
    {
        $config = array(
            'type' => 'viewer.table',
            'columns' => array(
                'id' => array(
                    'property' => 'id'
                )
            )
        );
        $request->get('_viewer')->willReturn($config);
        $this->parse($request);
        $this->get('type')->shouldReturn('viewer.table');
        $this->get('columns.id.property')->shouldReturn('id');
        $this->getType()->shouldReturn('viewer.table');
    }

    function it_should_give_back_default_config(Request $request)
    {
        $config = array(
            'level1' => array(
                'level2' => array(
                    'level3' => null,
                    'other' => 'otherValue'
                )
            )
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
        $request->get('_viewer')->willReturn($config);
        $this->parse($request);
        $this->setDefault($default);
        $this->get('type')->shouldReturn('defaultType');
        $this->get('level1.level2.type')->shouldReturn('defaultLevel2Type');

        $this->get('level1.level2')->shouldReturn(array(
            'level3' => null,
            'other' => 'otherValue',
            'type' => 'defaultLevel2Type'
        ));
    }
}
