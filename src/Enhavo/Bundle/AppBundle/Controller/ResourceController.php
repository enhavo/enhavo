<?php
/**
 * ResourceController.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use FOS\RestBundle\View\View;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\AppBundle\Viewer\ViewerFactory;

class ResourceController extends BaseController
{
    /**
     * @var ViewerFactory
     */
    protected $viewerFactory;

    /**
     * @var SortingManager
     */
    protected $sortingManger;

    /**
     * @var BatchManager
     */
    protected $batchManager;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        ViewHandlerInterface $viewHandler,
        RepositoryInterface $repository,
        FactoryInterface $factory,
        NewResourceFactoryInterface $newResourceFactory,
        ObjectManager $manager,
        SingleResourceProviderInterface $singleResourceProvider,
        ResourcesCollectionProviderInterface $resourcesFinder,
        ResourceFormFactoryInterface $resourceFormFactory,
        RedirectHandlerInterface $redirectHandler,
        FlashHelperInterface $flashHelper,
        AuthorizationCheckerInterface $authorizationChecker,
        EventDispatcherInterface $eventDispatcher,
        ViewerFactory $viewerFactory,
        SortingManager $sortingManager
    )
    {
        parent::__construct(
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
            $eventDispatcher
        );

        $this->viewerFactory = $viewerFactory;
        $this->sortingManger = $sortingManager;
    }

    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);

        $form = $this->resourceFormFactory->create($configuration, $newResource);

        if ($request->isMethod('POST')) {
            if($form->handleRequest($request)->isValid()) {
                $newResource = $form->getData();
                $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
                $this->repository->add($newResource);
                $this->sortingManger->update($configuration, $this->metadata, $newResource, $this->repository);
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);
            }
        }

        $viewer = $this->viewerFactory->create(
            $configuration,
            $this->metadata,
            $newResource,
            $form,
            'create'
        );

        return $this->viewHandler->handle($configuration, $viewer->createView());
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        //ToDo: Check for workflow update
        //if(!$this->isGranted('WORKFLOW_UPDATE', $resource)) {
        //    return new JsonResponse(null, 403);
        //}

        $resource = $this->findOr404($configuration);

        $form = $this->resourceFormFactory->create($configuration, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            if($form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
                $resource = $form->getData();
                $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->manager->flush();
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);
            }
        }

        $viewer = $this->viewerFactory->create(
            $configuration,
            $this->metadata,
            $resource,
            $form,
            'update'
        );

        return $this->viewHandler->handle($configuration, $viewer->createView());
    }

    public function indexAction(Request $request)
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $viewer = $this->viewerFactory->create(
            $configuration,
            $this->metadata,
            null,
            null,
            'index'
        );

        $view = $viewer->createView();

        return $this->viewHandler->handle($configuration, $view);
    }

    public function previewAction(Request $request)
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $newResource = $this->newResourceFactory->create($configuration, $this->factory);
        $form = $this->resourceFormFactory->create($configuration, $newResource);

        $form->handleRequest($request);

        $viewer = $this->viewerFactory->create(
            $configuration,
            $this->metadata,
            $newResource,
            $form,
            'preview'
        );

        $view = $viewer->createView();

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * {@inheritdoc}
     */
    public function tableAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $viewer = $this->viewerFactory->create(
            $configuration,
            $this->metadata,
            $resources,
            null,
            'table'
        );

        return $this->viewHandler->handle($configuration, $viewer->createView());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function batchAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $result = $this->batchManager->executeBatch($configuration, $this->metadata);

        if ($result) {
            return new JsonResponse(array('success' => true));
        }

        return new JsonResponse(array('success' => false));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function moveAfterAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->sortingManger->moveAfter($configuration, $this->metadata, $this->repository, $request->get('targetId'));

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function moveToPageAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->sortingManger->moveToPage($configuration, $this->metadata, $this->repository, $request->get('page'), $request->get('top'));

        return new JsonResponse();
    }
}