<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\PageBundle\Endpoint\PageEndpointType;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends ResourceController
{
    public function showResourceAction(Request $request, Page $contentDocument, bool $preview): Response
    {
        return $this->createEndpointResponse($request, [
            'type' => PageEndpointType::class,
            'resource' => $contentDocument,
            'preview' => $preview,
            'area' => 'theme',
            'navigation' => true,
            'routes' => ['theme', 'api'],
            'vue_routes' => ['theme']
        ]);
    }
}
