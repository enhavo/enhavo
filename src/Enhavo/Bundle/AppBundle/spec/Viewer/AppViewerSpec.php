<?php

namespace spec\enhavo\AdminBundle\Viewer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use enhavo\AdminBundle\Config\ConfigParser;

class AppViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('enhavo\AdminBundle\Viewer\AppViewer');
    }

    function it_should_return_parameters(ConfigParser $configParser)
    {
        $blocks = 'tabs';
        $actions = 'preview_route';
        $parameters = array();

        $configParser->get('actions')->willReturn($actions);
        $configParser->get('blocks')->willReturn($blocks);
        $configParser->get('parameters')->willReturn($parameters);

        $this->setConfig($configParser);

        $this->getParameters()->shouldHaveKeyWithValue('blocks', $blocks);
        $this->getParameters()->shouldHaveKeyWithValue('actions', $actions);
    }
}
