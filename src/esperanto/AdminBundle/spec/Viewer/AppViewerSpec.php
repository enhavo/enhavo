<?php

namespace spec\esperanto\AdminBundle\Viewer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AppViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('esperanto\AdminBundle\Viewer\AppViewer');
    }
}
