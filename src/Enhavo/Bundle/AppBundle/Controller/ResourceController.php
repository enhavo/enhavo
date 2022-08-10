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
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceUpdateHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\ResourceActions;
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
    protected $viewFactory;

    /**
     * @var SortingManager
     */
    protected $sortingManger;

    /**
     * @var BatchManager
     */
    protected $batchManager;

    /**
     * @var DuplicateResourceFactoryInterface
     */
    protected $duplicateResourceFactory;

    /**
     * @var AppEventDispatcher
     */
    protected $appEventDispatcher;

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
        $this->updateRequest($request);
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
                $this->appEventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);
                $this->addFlash('success', $this->get('translator')->trans('form.message.success', [], 'EnhavoAppBundle'));
                $route = $configuration->getRedirectRoute(null);
                return $this->redirectToRoute($route, [
                    'id' => $newResource->getId(),
                    'tab' => $request->get('tab'),
                    'view_id' => $request->get('view_id')
                ]);
            } else {
                $this->addFlash('error', $this->get('translator')->trans('form.message.error', [], 'EnhavoAppBundle'));
                foreach($form->getErrors() as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
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
        $this->updateRequest($request);
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);

        $form = $this->resourceFormFactory->create($configuration, $resource);

        $form->handleRequest($request);
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH']) && $form->isSubmitted()) {
            if($form->isValid()) {
                $resource = $form->getData();
                $this->appEventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->manager->flush();
                $this->appEventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);
                $this->addFlash('success', $this->get('translator')->trans('form.message.success', [], 'EnhavoAppBundle'));
                $route = $request->get('_route');
                return $this->redirectToRoute($route, [
                    'id' => $resource->getId(),
                    'tab' => $request->get('tab'),
                    'view_id' => $request->get('view_id')
                ]);
            } else {
                $this->addFlash('error', $this->get('translator')->trans('form.message.error', [], 'EnhavoAppBundle'));
                foreach($form->getErrors() as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
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

        if(!$this->duplicateResourceFactory instanceof DuplicateResourceFactoryInterface) {
            throw new \Exception('newResourceFactory should implement DuplicateResourceFactoryInterface');
        }

        $newResource = $this->duplicateResourceFactory->duplicate($configuration, $this->factory, $resource);
        $this->appEventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
        $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);
        $this->repository->add($newResource);
        $this->appEventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);
        $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);

        $this->addFlash('success', $this->get('translator')->trans('form.message.success', [], 'EnhavoAppBundle'));
        $route = $configuration->getRedirectRoute(null);
        return $this->redirectToRoute($route, [
            'id' => $newResource->getId(),
            'tab' => $request->get('tab'),
            'view_id' => $request->get('view_id')
        ]);
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

        $resource = null;
        if($request->query->has('id')) {
            $request->attributes->set('id', $request->query->get('id'));
            $resource = $this->singleResourceProvider->get($configuration, $this->repository);
        }

        $view = $this->viewFactory->create('preview', [
            'metadata' => $this->metadata,
            'resource' => $resource
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    public function previewResourceAction(Request $request): Response
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->appEventDispatcher->dispatchInitEvent(ResourceEvents::INIT_PREVIEW, $configuration);

        if($request->query->has('id')) {
            $request->attributes->set('id', $request->query->get('id'));
            $resource = $this->singleResourceProvider->get($configuration, $this->repository);
            $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        } else {
            $resource = $this->newResourceFactory->create($configuration, $this->factory);
            $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        }

        $form = $this->resourceFormFactory->create($configuration, $resource);
        $form->handleRequest($request);

        $view = $this->viewFactory->create('resource_preview', [
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
            'request' => $request,
        ]);

        $response = $this->viewHandler->handle($configuration, $view);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function listAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $view = $this->viewFactory->create('list', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * {@inheritdoc}
     */
    public function listDataAction(Request $request): Response
    {
        $request->query->set('limit', 1000000); // never show pagination
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        if(in_array($request->getMethod(), ['POST'])) {
            if ($configuration->isCsrfProtectionEnabled() && !$this->isCsrfTokenValid('list_data', $request->get('_csrf_token'))) {
                throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
            }
            $this->sortingManger->handleSort($request, $configuration, $this->repository);
            return new JsonResponse();
        }

        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);
        $view = $this->viewFactory->create('list_data', [
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resources' => $resources,
            'request' => $request,
        ]);

        $response = $this->viewHandler->handle($configuration, $view);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function deleteAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $response = parent::deleteAction($request);
        if($response instanceof RedirectResponse) {
            $view = $this->viewFactory->create('delete', [
                'metadata' => $this->metadata,
                'request_configuration' => $configuration,
                'request' => $request
            ]);
            return $this->viewHandler->handle($configuration, $view);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function batchAction(Request $request): Response
    {
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $batchConfiguration = $configuration->getBatches();
        $type = $configuration->getBatchType();

        $batch = $this->batchManager->getBatch($type, $batchConfiguration);
        if($batch === null) {
            throw $this->createNotFoundException();
        }

        try {
            $response = $this->batchManager->executeBatch($batch, $resources);
            if ($response !== null) {
                return $response;
            }
        } catch (BatchExecutionException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse();
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

    private function updateRequest(Request $request)
    {
        if($request->getSession() && $request->getSession()->has('enhavo.post')) {
            $postData = $request->getSession()->get('enhavo.post');
            if($postData) {
                foreach($postData as $key => $value) {
                    $request->query->set($key, $value);
                }
                $request->getSession()->remove('enhavo.post');
            }
        }
    }
}
