<?php

namespace Enhavo\Bundle\AppBundle\Tests\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceControllerTest extends \PHPUnit_Framework_TestCase
{
    protected function getEntityMock()
    {
        return new EntityMock();
    }

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
        $mock->method('create')->willReturnCallback(function($type, $default) {
            if($default == 'create') {
                return  $this->getCreateViewerMock();
            }
            if($default == 'preview') {
                return  $this->getPreviewViewerMock();
            }
            if($default == 'edit') {
                return  $this->getEditViewerMock();
            }
            if($default == 'index') {
                return  $this->getIndexViewerMock();
            }
        });
        return $mock;
    }

    protected function getCreateViewerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\CreateViewer')->getMock();
        $mock->method('getTemplate')->willReturn('template');
        return $mock;
    }

    protected function getPreviewViewerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\PreviewViewer')->getMock();
        $mock->method('getTemplate')->willReturn('template');
        $mock->method('getStrategyName')->willReturn('strategy');
        $mock->method('getConfig')->willReturn($this->getViewerConfigMock());
        return $mock;
    }

    protected function getEditViewerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\EditViewer')->getMock();
        $mock->method('getTemplate')->willReturn('template');
        return $mock;
    }

    protected function getIndexViewerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\IndexViewer')->getMock();
        $mock->method('getTemplate')->willReturn('template');
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
        if($service === 'factory') {
            return $this->getFactoryMock();
        }
        if($service === 'form.registry') {
            return $this->getFormRegistryMock();
        }
        if($service === 'form.factory') {
            return $this->getFormFactoryMock();
        }
        if($service === 'fos_rest.view_handler') {
            return $this->getViewHandlerMock();
        }
        if($service === 'repository') {
            return $this->getRepositoryMock();
        }
        if($service === 'enhavo_app.preview.strategy_resolver') {
            return $this->getStrategyResolverMock();
        }

        return null;
    }

    protected function getStrategyResolverMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Preview\StrategyResolver')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getStrategy')->willReturn($this->getStrategyMock());
        return $mock;
    }

    protected function getStrategyMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Preview\StrategyInterface')->getMock();
        $mock->method('getPreviewResponse')->willReturn(new Response());
        return $mock;
    }

    protected function getRepositoryMock()
    {
        $mock = $this->getMockBuilder('Sylius\Component\Resource\Repository\RepositoryInterface')->getMock();
        $mock->method('findOneBy')->willReturn($this->getEntityMock());
        return $mock;
    }

    protected function getViewHandlerMock()
    {
        $mock = $this->getMockBuilder('FOS\RestBundle\View\ViewHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('handle')->willReturn(new Response());
        return $mock;
    }

    protected function getFormFactoryMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Form\FormFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('create')->willReturn($this->getFormMock());
        return $mock;
    }

    protected function getFormMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;
    }

    protected function getFormRegistryMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Form\FormRegistry')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('hasType')->willReturn(true);
        return $mock;
    }

    protected function getConfigMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\Configuration')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getServiceName')->willReturnCallback(function($service) {
            return $service;
        });
        $mock->method('isApiRequest')->willReturn(false);
        $mock->method('getFactoryMethod')->willReturnCallback(function($defaultMethod) {
            return $defaultMethod;
        });
        $mock->method('getFactoryArguments')->willReturnCallback(function($defaultArguments) {
            return $defaultArguments;
        });
        $mock->method('getTemplate')->willReturnCallback(function($path) {
            return $path;
        });
        $mock->method('getRepositoryMethod')->willReturnCallback(function($defaultMethod) {
            return $defaultMethod;
        });
        $mock->method('getRepositoryArguments')->willReturnCallback(function($defaultArguments) {
            return $defaultArguments;
        });
        $mock->method('getCriteria')->willReturnCallback(function($criteria) {
            return $criteria;
        });

        return $mock;
    }

    protected function getFactoryMock()
    {
        $mock = $this->getMockBuilder('Sylius\Component\Resource\Factory\FactoryInterface')->getMock();
        $mock->method('createNew')->willReturn($this->getEntityMock());
        return $mock;
    }


    public function testCreateAction()
    {
        $config = $this->getConfigMock();
        $controller = new ResourceController($config);
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Controller\ResourceController', $controller);

        $controller->setContainer($this->getContainerMock());

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $controller->createAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testUpdateAction()
    {
        $config = $this->getConfigMock();
        $controller = new ResourceController($config);
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Controller\ResourceController', $controller);

        $controller->setContainer($this->getContainerMock());

        $request = new Request();

        $response = $controller->updateAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testIndexAction()
    {
        $config = $this->getConfigMock();
        $controller = new ResourceController($config);
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Controller\ResourceController', $controller);

        $controller->setContainer($this->getContainerMock());

        $request = new Request();

        $response = $controller->indexAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testPreviewAction()
    {
        $config = $this->getConfigMock();
        $controller = new ResourceController($config);
        $this->assertInstanceOf('Enhavo\Bundle\AppBundle\Controller\ResourceController', $controller);

        $controller->setContainer($this->getContainerMock());

        $request = new Request();

        $response = $controller->previewAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }
}