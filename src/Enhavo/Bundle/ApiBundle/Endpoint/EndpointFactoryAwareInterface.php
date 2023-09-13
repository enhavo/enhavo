<?php

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Response;

interface EndpointFactoryAwareInterface
{
    public function setEndpointFactory(FactoryInterface $factory): void;

    public function getEndpointFactory(): FactoryInterface;

    public function createEndpointResponse($request, $options): Response;
}
