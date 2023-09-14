<?php

namespace Enhavo\Bundle\ApiBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Documentation\Writer;
use Enhavo\Component\Type\TypeExtensionInterface;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface EndpointTypeExtensionInterface extends TypeExtensionInterface
{
    public function handleRequest($options, Request $request, Data $data, Context $context);
    public function describe($options, Writer $writer);
}
