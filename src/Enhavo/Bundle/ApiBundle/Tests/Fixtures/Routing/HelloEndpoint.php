<?php

namespace Enhavo\Bundle\ApiBundle\Tests\Fixtures\Routing;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/hello', name: 'app_theme_hello', defaults: ['_format' => 'html'])]
#[Route(path: '/api/hello', defaults: ['_format' => 'json', '_describe' => true])]
class HelloEndpoint extends AbstractEndpointType
{
    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $data->set('message', 'hello world!');
    }
}
