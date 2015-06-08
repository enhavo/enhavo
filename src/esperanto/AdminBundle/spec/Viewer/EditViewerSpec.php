<?php

namespace spec\esperanto\AdminBundle\Viewer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use esperanto\AdminBundle\Viewer\ConfigParser;

class EditViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('esperanto\AdminBundle\Viewer\EditViewer');
    }

    function it_should_return_parameters(ConfigParser $configParser)
    {
        $tabs = 'tabs';
        $previewRoute = 'preview_route';
        $updateRoute = 'update_route';
        $deleteRoute = 'delete_route';
        $formTemplate = 'form_template';
        $resource = 'resource';
        $form = 'form';
        $parameters = array();

        $configParser->get('tabs')->willReturn($tabs);
        $configParser->get('preview_route')->willReturn($previewRoute);
        $configParser->get('update_route')->willReturn($updateRoute);
        $configParser->get('delete_route')->willReturn($deleteRoute);
        $configParser->get('form_template')->willReturn($formTemplate);
        $configParser->get('parameters')->willReturn($parameters);

        $this->setConfig($configParser);
        $this->setResource($resource);
        $this->setForm($form);

        $this->getParameters()->shouldHaveKeyWithValue('tabs', $tabs);
        $this->getParameters()->shouldHaveKeyWithValue('preview_route', $previewRoute);
        $this->getParameters()->shouldHaveKeyWithValue('update_route', $updateRoute);
        $this->getParameters()->shouldHaveKeyWithValue('delete_route', $deleteRoute);
        $this->getParameters()->shouldHaveKeyWithValue('form_template', $formTemplate);
        $this->getParameters()->shouldHaveKeyWithValue('data', $resource);
        $this->getParameters()->shouldHaveKeyWithValue('form', $form);
    }
}
