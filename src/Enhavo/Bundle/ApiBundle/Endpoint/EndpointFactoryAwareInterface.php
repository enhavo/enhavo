<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Component\Type\FactoryInterface;

interface EndpointFactoryAwareInterface
{
    public function setEndpointFactory(FactoryInterface $factory): void;
}
