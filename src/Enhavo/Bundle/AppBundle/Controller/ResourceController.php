<?php
/**
 * SettingController.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseController;
use Sylius\Component\Resource\ResourceActions;

class ResourceController extends BaseController
{
    /** @var FactoryInterface */
    private $viewFactory;

    /**
     * @param FactoryInterface $viewFactory
     */
    public function setViewFactory(FactoryInterface $viewFactory): void
    {
        $this->viewFactory = $viewFactory;
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
        ]);

        return $view->getResponse($request);
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
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'index',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);

        return $view->getResponse();
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
            $this->batchManager->executeBatch($batch, $resources);
        } catch (BatchExecutionException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse();
    }
}
