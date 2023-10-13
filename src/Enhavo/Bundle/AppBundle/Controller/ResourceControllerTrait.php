<?php

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait ResourceControllerTrait
{
    protected ?FactoryInterface $viewFactory = null;

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
        ]);

        return $view->getResponse($request);
    }

    public function updateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'update',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function duplicateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'duplicate',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function indexAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'index',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function previewAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'preview',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function previewResourceAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'resource_preview',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function tableAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'table',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function listAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'list',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function listDataAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'list_data',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function deleteAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'delete',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }

    public function batchAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->viewFactory->create([
            'type' => 'batch',
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
    }
}
