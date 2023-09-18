<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Path;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EndpointTypeInterface extends TypeInterface
{
    public function handleRequest($options, Request $request, Data $data, Context $context);
    public function getResponse($options, Request $request, Data $data, Context $context): Response;
    public function describe($options, Path $path);
}
