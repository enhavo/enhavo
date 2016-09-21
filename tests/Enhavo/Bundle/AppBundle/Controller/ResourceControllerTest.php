<?php

namespace Enhavo\Bundle\AppBundle\Tests\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceControllerTest extends \PHPUnit_Framework_TestCase
{
    private function getEntityMock()
    {
        return new EntityMock();
    }

    private function getEventDispatcherMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface')->getMock();
        return $mock;
    }

    private function getContainerMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();
        $mock->method('get')->will($this->returnCallback([$this, 'getContainerCallback']));
        $mock->method('has')->willReturn(true);
        return $mock;
    }

    private function getViewerFactoryMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\ViewerFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('create')->willReturn($this->getViewerMock());
        return $mock;
    }

    private function getViewerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Viewer\ViewerInterface')->getMock();
        $mock->method('createView')->willReturn($this->getViewMock());
        return $mock;
    }

    private function getViewMock()
    {
        $mock = $this->getMockBuilder('FOS\RestBundle\View\View')->getMock();
        return $mock;
    }

    private function getAuthorizationCheckerMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('isGranted')->willReturn(true);
        return $mock;
    }

    private function getRepositoryMock()
    {
        $mock = $this->getMockBuilder('Sylius\Component\Resource\Repository\RepositoryInterface')->getMock();
        $mock->method('findOneBy')->willReturn($this->getEntityMock());
        return $mock;
    }

    private function getFactoryMock()
    {
        $mock = $this->getMockBuilder('Sylius\Component\Resource\Factory\FactoryInterface')->getMock();
        $mock->method('createNew')->willReturn($this->getEntityMock());
        return $mock;
    }

    private function getMetadataMock()
    {
        $mock = $this->getMockBuilder('Sylius\Component\Resource\Metadata\MetadataInterface')->getMock();
        $mock->method('createNew')->willReturn($this->getEntityMock());
        return $mock;
    }

    private function getNewResourceFactoryMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface')->getMock();
        $mock->method('create')->willReturn(new EntityMock());
        return $mock;
    }

    private function getObjectManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->getMock();
        return $mock;
    }

    private function getResourcesCollectionProviderMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface')->getMock();
        return $mock;
    }

    private function getResourceFormFactoryMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface')->getMock();
        $mock->method('create')->willReturn($this->getFormMock());
        return $mock;
    }

    private function getFormMock()
    {
        $mock = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;

    }

    private function getSingleResourceProviderMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface')->getMock();
        $mock->method('get')->willReturn(new EntityMock());
        return $mock;
    }

    private function getRedirectHandlerMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface')->getMock();
        return $mock;
    }

    private function getFlashHelperMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface')->getMock();
        return $mock;
    }

    private function getSortingManagerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\SortingManager')
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;
    }

    private function getBatchManagerMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Batch\BatchManager')
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;
    }

    private function getRequestConfigurationFactoryMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface')->getMock();
        $mock->method('create')->willReturn($this->getRequestConfigurationMock());
        return $mock;
    }

    private function getRequestConfigurationMock()
    {
        $mock = $this->getMockBuilder('Enhavo\Bundle\AppBundle\Controller\RequestConfiguration')
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;
    }

    private function getViewHandlerMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface')->getMock();
        $mock->method('handle')->willReturn(new Response());
        return $mock;
    }

    private function getStateMachineMock()
    {
        $mock = $this->getMockBuilder('Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface')->getMock();
        return $mock;
    }

    protected function createResourceController()
    {
        $metadata = $this->getMetadataMock();
        $requestConfigurationFactory = $this->getRequestConfigurationFactoryMock();
        $viewHandler = $this->getViewHandlerMock();
        $repository = $this->getRepositoryMock();
        $factory = $this->getFactoryMock();
        $newResourceFactory = $this->getNewResourceFactoryMock();
        $manager = $this->getObjectManagerMock();
        $singleResourceProvider = $this->getSingleResourceProviderMock();
        $resourcesFinder = $this->getResourcesCollectionProviderMock();
        $resourceFormFactory = $this->getResourceFormFactoryMock();
        $redirectHandler = $this->getRedirectHandlerMock();
        $flashHelper = $this->getFlashHelperMock();
        $authorizationChecker = $this->getAuthorizationCheckerMock();
        $eventDispatcher = $this->getEventDispatcherMock();
        $stateMachine = $this->getStateMachineMock();
        $viewerFactory = $this->getViewerFactoryMock();
        $sortingManager = $this->getSortingManagerMock();
        $batchManager = $this->getBatchManagerMock();

        $controller = new ResourceController(
            $metadata,
            $requestConfigurationFactory,
            $viewHandler,
            $repository,
            $factory,
            $newResourceFactory,
            $manager,
            $singleResourceProvider,
            $resourcesFinder,
            $resourceFormFactory,
            $redirectHandler,
            $flashHelper,
            $authorizationChecker,
            $eventDispatcher,
            $stateMachine,
            $viewerFactory,
            $sortingManager,
            $batchManager
        );

        return $controller;
    }


    public function testCreateAction()
    {
        $controller = $this->createResourceController();

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
        $controller = $this->createResourceController();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $controller->updateAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testIndexAction()
    {
        $controller = $this->createResourceController();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $controller->indexAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }

    public function testPreviewAction()
    {
        $controller = $this->createResourceController();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $controller->previewAction($request);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
    }
}