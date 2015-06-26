<?php

namespace spec\esperanto\AdminBundle\Viewer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use esperanto\AdminBundle\Viewer\ConfigParser;

class CreateViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('esperanto\AdminBundle\Viewer\CreateViewer');
    }

    function it_should_return_parameters(ConfigParser $configParser)
    {
        $tabs = 'tabs';
        $previewRoute = 'preview_route';
        $createRoute = 'create_route';
        $formTemplate = 'form_template';
        $form = 'form';
        $parameters = array();

        $configParser->get('tabs')->willReturn($tabs);
        $configParser->get('buttons')->willReturn($createRoute);
        $configParser->get('preview_route')->willReturn($previewRoute);
        $configParser->get('form_template')->willReturn($formTemplate);
        $configParser->get('parameters')->willReturn($parameters);

        $this->setConfig($configParser);
        $this->setForm($form);

        $this->getParameters()->shouldHaveKeyWithValue('tabs', $tabs);
        $this->getParameters()->shouldHaveKeyWithValue('preview_route', $previewRoute);
        $this->getParameters()->shouldHaveKeyWithValue('form_template', $formTemplate);
        $this->getParameters()->shouldHaveKeyWithValue('create_route', $createRoute);
        $this->getParameters()->shouldHaveKeyWithValue('form', $form);
    }
}
