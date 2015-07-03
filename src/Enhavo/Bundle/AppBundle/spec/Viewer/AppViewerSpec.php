<?php

namespace spec\Enhavo\Bundle\AppBundle\Viewer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Enhavo\Bundle\AppBundle\Config\ConfigParser;

class AppViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enhavo\Bundle\AppBundle\Viewer\AppViewer');
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
