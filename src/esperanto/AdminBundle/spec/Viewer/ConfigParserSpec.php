<?php

namespace spec\esperanto\AdminBundle\Viewer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class ConfigParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('esperanto\AdminBundle\Viewer\ConfigParser');
    }

    function it_should_give_back_configs(Request $request)
    {
        $config = [
            'type' => 'viewer.table',
            'columns' => [
                'id' => [
                    'property' => 'id'
                ]
            ]
        ];
        $request->get('_viewer')->willReturn($config);
        $this->parse($request);
        $this->get('type')->shouldReturn('viewer.table');
        $this->get('columns.id.property')->shouldReturn('id');
        $this->getType()->shouldReturn('viewer.table');
    }
}
