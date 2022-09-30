<?php

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Response;

interface ViewFactoryAwareInterface
{
    public function setViewFactory(FactoryInterface $factory): void;

    public function getViewFactory(): FactoryInterface;

    public function createViewResponse($request, $options): Response;
}
