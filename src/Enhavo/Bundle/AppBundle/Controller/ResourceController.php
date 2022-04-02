<?php
/**
 * SettingController.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Sylius\Component\Resource\ResourceActions;

class ResourceController extends BaseController
{
    /** @var FactoryInterface */
    private $viewFactory;

    /** @var AppEventDispatcher */
    private $appEventDispatcher;

    /** @var BatchManager */
    private $batchManager;

    /**
     * @param FactoryInterface $viewFactory
     */
    public function setViewFactory(FactoryInterface $viewFactory): void
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @return FactoryInterface
     */
    protected function getViewFactory(): FactoryInterface
    {
        return $this->viewFactory;
    }

    /**
     * @param AppEventDispatcher $appEventDispatcher
     */
    public function setAppEventDispatcher(AppEventDispatcher $appEventDispatcher): void
    {
        $this->appEventDispatcher = $appEventDispatcher;
    }

    /**
     * @param BatchManager $batchManager
     */
    public function setBatchManager(BatchManager $batchManager): void
    {
        $this->batchManager = $batchManager;
    }

    public function createAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'create',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource_factory' => $this->newResourceFactory,
            'resource_form_factory' => $this->resourceFormFactory,
            'factory' => $this->factory,
            'repository' => $this->repository,
            'event_dispatcher' => $this->eventDispatcher,
            'app_event_dispatcher' => $this->appEventDispatcher,
        ]);

        return $view->getResponse($request);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'update',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resource_factory' => $this->newResourceFactory,
            'resource_form_factory' => $this->resourceFormFactory,
            'factory' => $this->factory,
            'repository' => $this->repository,
            'event_dispatcher' => $this->eventDispatcher,
            'app_event_dispatcher' => $this->appEventDispatcher,
            'single_resource_provider' => $this->singleResourceProvider,
        ]);

        return $view->getResponse($request);
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
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'index',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);

        return $view->getResponse($request);
    }

    public function previewAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'preview',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'single_resource_provider' => $this->singleResourceProvider,
            'repository' => $this->repository,
        ]);

        return $view->getResponse($request);
    }

    public function previewResourceAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'resource_preview',
            'metadata' => $this->metadata,
            'request_configuration' => $configuration,
            'resource_form_factory' => $this->resourceFormFactory,
            'single_resource_provider' => $this->singleResourceProvider,
            'new_resource_factory' =>  $this->newResourceFactory,
            'factory' =>  $this->factory,
            'repository' =>  $this->repository,
            'app_event_dispatcher' => $this->appEventDispatcher,
        ]);

        return $view->getResponse($request);
    }

    /**
     * {@inheritdoc}
     */
    public function tableAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = $this->viewFactory->create([
            'type' => 'table',
            'metadata' => $this->metadata,
            'request_configuration' => $configuration,
            'resources' => $resources,
        ]);

        return $view->getResponse($request);
    }

    /**
     * {@inheritdoc}
     */
    public function listAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $view = $this->viewFactory->create([
            'type' => 'list',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);

        return $view->getResponse($request);
    }

    /**
     * {@inheritdoc}
     */
    public function listDataAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $configuration->getParameters()->set('paginate', false);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = $this->viewFactory->create([
            'type' => 'list_data',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
            'resources' => $resources,
        ]);

        return $view->getResponse($request);
    }

    public function deleteAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'delete',
            'request_configuration' => $configuration,
//            'metadata' => $this->metadata,
//            'resource_factory' => $this->newResourceFactory,
//            'resource_form_factory' => $this->resourceFormFactory,
//            'factory' => $this->factory,
            'repository' => $this->repository,
            'event_dispatcher' => $this->eventDispatcher,
            'app_event_dispatcher' => $this->appEventDispatcher,
            'single_resource_provider' => $this->singleResourceProvider,
        ]);

        return $view->getResponse($request);
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
            $this->batchManager->executeBatch($batch, $resources);
        } catch (BatchExecutionException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse();
    }
}
