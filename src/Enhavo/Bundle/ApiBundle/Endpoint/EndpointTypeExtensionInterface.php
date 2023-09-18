<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Component\Type\TypeExtensionInterface;
use Symfony\Component\HttpFoundation\Request;

interface EndpointTypeExtensionInterface extends TypeExtensionInterface
{
    public function handleRequest($options, Request $request, Data $data, Context $context);
    public function describe($options, Path $path);
}
