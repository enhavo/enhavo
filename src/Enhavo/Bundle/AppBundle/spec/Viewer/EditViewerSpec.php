<?php

namespace spec\Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\spec\EntityMock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Enhavo\Bundle\AppBundle\Config\ConfigParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Router;

class EditViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enhavo\Bundle\AppBundle\Viewer\EditViewer');
    }

    function it_should_return_parameters(ConfigParser $configParser, ContainerInterface $container, Router $router)
    {
        $tabs = 'tabs';
        $formActionUrl = 'some_action_url';
        $formDeleteUrl = 'some_delete_url';
        $formTemplate = 'form_template';
        $resource = new EntityMock();
        $form = 'form';
        $buttons = array();
        $parameters = array();

        $configParser->get('tabs')->willReturn($tabs);
        $configParser->get('form.template')->willReturn($formTemplate);
        $configParser->get('form.action')->willReturn('form_action_route');
        $configParser->get('form.delete')->willReturn('form_delete_route');
        $configParser->get('buttons')->willReturn($buttons);
        $configParser->get('parameters')->willReturn($parameters);
        $router->generate('form_action_route', array('id' => 1))->willReturn($formActionUrl);
        $router->generate('form_delete_route', array('id' => 1))->willReturn($formDeleteUrl);
        $container->get('router')->willReturn($router);

        $this->setConfig($configParser);
        $this->setResource($resource);
        $this->setForm($form);
        $this->setContainer($container);

        $this->getParameters()->shouldHaveKeyWithValue('tabs', $tabs);
        $this->getParameters()->shouldHaveKeyWithValue('buttons', $buttons);
        $this->getParameters()->shouldHaveKeyWithValue('form_action', $formActionUrl);
        $this->getParameters()->shouldHaveKeyWithValue('form_template', $formTemplate);
        $this->getParameters()->shouldHaveKeyWithValue('form_delete', $formDeleteUrl);
        $this->getParameters()->shouldHaveKeyWithValue('form', $form);
        $this->getParameters()->shouldHaveKeyWithValue('data', $resource);
        $this->getParameters()->shouldHaveKey('viewer');
    }
}
