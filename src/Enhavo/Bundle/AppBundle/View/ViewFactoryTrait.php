<?php

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Response;

trait ViewFactoryTrait
{
    private FactoryInterface $viewFactory;

    public function setViewFactory(FactoryInterface $factory): void
    {
        $this->viewFactory = $factory;
    }

    public function getViewFactory(): FactoryInterface
    {
        return $this->viewFactory;
    }

    public function createViewResponse($request, $options): Response
    {
        $view = $this->getViewFactory()->create($options);
        return $view->getResponse($request);
    }
}
