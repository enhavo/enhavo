<?php

namespace Enhavo\Bundle\AppBundle\Tests\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;

class ResourceControllerTest extends \PHPUnit_Framework_TestCase
{
    protected function getRouterMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Routing\RouterInterface')->getMock();
        return $mock;
    }

    protected function getTranslatorMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock();
        return $mock;
    }

    protected function getSessionMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\SessionInterface')->getMock();
        return $mock;
    }

    protected function getManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')->getMock();
        return $mock;
    }

    protected function getEventDispatcherMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();
        return $mock;
    }

    protected function getContainerMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $mock->method('get')->will($this->returnCallback([$this, 'getContainerCallback']));
        return $mock;
    }

    protected function getViewerConfigMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Config\ConfigParser')->getMock();
        $mock->method('getType')->willReturn(null);
        $mock->method('parse')->will($this->returnSelf());
        return $mock;
    }

    protected function getViewerFactoryMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\ViewerFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('create')->willReturn($this->getCreateViewerMock());
        return $mock;
    }

    protected function getCreateViewerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\CreateViewer')->getMock();
        return $mock;
    }

    public function getContainerCallback($service)
    {
        if($service === 'router') {
            return $this->getRouterMock();
        }
        if($service === 'translator') {
            return $this->getTranslatorMock();
        }
        if($service === 'session') {
            return $this->getSessionMock();
        }
        if($service === 'manager') {
            return $this->getManagerMock();
        }
        if($service === 'event_dispatcher') {
            return $this->getEventDispatcherMock();
        }
        if($service === 'viewer.config') {
            return $this->getViewerConfigMock();
        }
        if($service === 'viewer.factory') {
            return $this->getViewerFactoryMock();
        }

        return null;
    }

    public function testIndexAction()
    {
        $config = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\Configuration')
            ->disableOriginalConstructor()
            ->getMock();

        $config->method('getServiceName')->willReturn('manager');
        $config->method('isApiRequest')->willReturn(false);

        $controller = new ResourceController($config);
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Controller\ResourceController', $controller);

        $controller->setContainer($this->getContainerMock());

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $controller->createAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }
}