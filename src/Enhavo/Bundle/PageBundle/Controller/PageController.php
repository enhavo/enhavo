<?php

namespace Enhavo\Bundle\PageBundle\Controller;

use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\PageBundle\Endpoint\PageEndpointType;
use Enhavo\Bundle\PageBundle\Entity\Page;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    public function __construct(
        private readonly FactoryInterface $endpointFactory,
    )
    {
    }

    public function showResourceAction(Request $request, Page $contentDocument, bool $preview): Response
    {
        /** @var Endpoint $endpoint */
        $endpoint = $this->endpointFactory->create([
            'type' => PageEndpointType::class,
            'resource' => $contentDocument,
            'preview' => $preview,
        ]);

        return $endpoint->getResponse($request);
    }
}
