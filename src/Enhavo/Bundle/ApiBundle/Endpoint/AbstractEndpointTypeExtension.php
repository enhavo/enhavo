<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Component\Type\AbstractTypeExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class AbstractEndpointTypeExtension extends AbstractTypeExtension implements EndpointTypeExtensionInterface, ServiceSubscriberInterface
{
    use EndpointTrait;

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {

    }

    public function describe($options, Path $path)
    {

    }
}
