<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Response;

trait EndpointFactoryTrait
{
    private FactoryInterface $endpointFactory;

    public function setEndpointFactory(FactoryInterface $factory): void
    {
        $this->endpointFactory = $factory;
    }

    protected function getEndpointFactory(): FactoryInterface
    {
        return $this->endpointFactory;
    }

    protected function createEndpointResponse($request, $options): Response
    {
        return $this->createEndpoint($options)->getResponse($request);
    }

    protected function createEndpoint($options): Endpoint
    {
        return $this->getEndpointFactory()->create($options);
    }
}
