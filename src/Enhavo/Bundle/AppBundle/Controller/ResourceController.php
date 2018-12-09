<?php
/**
 * SettingController.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface as SyliusRequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceFormFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\StateMachineInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration as SyliusRequestConfiguration;

class ResourceController extends BaseController
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var SortingManager
     */
    private $sortingManger;

    /**
     * @var BatchManager
     */
    private $batchManager;

    /**
     * @var DuplicateResourceFactoryInterface
     */
    private $duplicateResourceFactory;

    /**
     * @var AppEventDispatcher
     */
    private $appEventDispatcher;

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
        StateMachineInterface $stateMachine,
        ResourceUpdateHandlerInterface $resourceUpdateHandler,
        ResourceDeleteHandlerInterface $resourceDeleteHandler,
        ViewFactory $viewFactory,
        SortingManager $sortingManager,
        BatchManager $batchManager,
        DuplicateResourceFactory $duplicateResourceFactory,
        AppEventDispatcher $appEventDispatcher
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
            $eventDispatcher,
            $stateMachine,
            $resourceUpdateHandler,
            $resourceDeleteHandler
        );

        $this->viewFactory = $viewFactory;
        $this->sortingManger = $sortingManager;
        $this->batchManager = $batchManager;
        $this->appEventDispatcher = $appEventDispatcher;
        $this->duplicateResourceFactory = $duplicateResourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);

        $form = $this->resourceFormFactory->create($configuration, $newResource);

        if ($request->isMethod('POST')) {
            if($form->handleRequest($request)->isValid()) {
                $newResource = $form->getData();
                $this->appEventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
                $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
                $this->repository->add($newResource);
                $this->sortingManger->initialize($configuration, $this->metadata, $newResource, $this->repository);
                $this->appEventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);
            }
        }

        $view = $this->viewFactory->create('create', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $newResource,
            'form' => $form
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);

        $form = $this->resourceFormFactory->create($configuration, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            if($form->submit($request, !$request->isMethod('PATCH'))->isValid()) {
                $resource = $form->getData();
                $this->appEventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->manager->flush();
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);
            }
        }

        $view = $this->viewFactory->create('update', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $resource,
            'form' => $form
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    public function duplicateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        $resource = $this->findOr404($configuration);

        if(!$this->newResourceFactory instanceof DuplicateResourceFactoryInterface) {
            throw new \Exception('newResourceFactory should implement DuplicateResourceFactoryInterface');
        }

        $newResource = $this->newResourceFactory->duplicate($configuration, $this->factory, $resource);
        $this->appEventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
        $this->repository->add($newResource);
        $this->sortingManger->initialize($configuration, $this->metadata, $newResource, $this->repository);
        $this->appEventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);

        return $this->redirectHandler->redirectToResource($configuration, $newResource);
    }

    public function indexAction(Request $request): Response
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $view = $this->viewFactory->create('index', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    public function previewAction(Request $request): Response
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->appEventDispatcher->dispatchInitEvent(ResourceEvents::INIT_PREVIEW, $configuration);

        if($request->attributes->has('id')) {
            $resource = $this->singleResourceProvider->get($configuration, $this->repository);
            $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        } else {
            $resource = $this->newResourceFactory->create($configuration, $this->factory);
            $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        }

        $form = $this->resourceFormFactory->create($configuration, $resource);
        $form->handleRequest($request);

        $view = $this->viewFactory->create('preview', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource' => $resource,
            'form' => $form
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * {@inheritdoc}
     */
    public function tableAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = $this->viewFactory->create('table', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resources' => $resources,
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * {@inheritdoc}
     */
    public function listAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = $this->viewFactory->create('list', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resources' => $resources,
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function batchAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);
        $this->batchManager->executeBatch($resources, $configuration);
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function moveAfterAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $resource = $this->findOr404($configuration);
        $this->sortingManger->moveAfter($configuration, $this->metadata, $resource, $this->repository, $request->get('target'));

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function moveToPageAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $resource = $this->findOr404($configuration);
        $this->sortingManger->moveToPage($configuration, $this->metadata, $resource, $this->repository, $request->get('page'), $request->get('top'));

        return new JsonResponse();
    }

    protected function getPermissionRole($type, MetadataInterface $metadata)
    {
        return strtoupper(sprintf('ROLE_%s_%s_%s', $metadata->getApplicationName(), $metadata->getHumanizedName(), $type));
    }

    /**
     * @param SyliusRequestConfiguration $configuration
     * @param string $permission
     *
     * @throws AccessDeniedException
     */
    protected function isGrantedOr403(SyliusRequestConfiguration $configuration, string $permission): void
    {
        if (!$configuration->hasPermission()) {
            $permission = $this->getRoleName($permission);
            if (!$this->authorizationChecker->isGranted($configuration, $permission)) {
                throw $this->createAccessDeniedException();
            }
            return;
        }

        $permission = $configuration->getPermission($permission);
        if (!$this->authorizationChecker->isGranted($configuration, $permission)) {
            throw $this->createAccessDeniedException();
        }

        return;
    }

    private function getRoleName(string $permission): string
    {
        $name = $this->metadata->getHumanizedName();
        $name = str_replace(' ', '_', $name);
        $role = sprintf(
            'role_%s_%s_%s',
            $this->metadata->getApplicationName(),
            $name,
            $permission
        );
        return strtoupper($role);
    }
}