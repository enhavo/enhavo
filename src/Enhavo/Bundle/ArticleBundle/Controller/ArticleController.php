<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\ArticleBundle\Endpoint\ArticleEndpointType;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractEndpointType
{
    public function __construct(
        private readonly FactoryInterface $endpointFactory,
    )
    {
    }

    public function showResourceAction(Article $contentDocument, Request $request, bool $preview = false): Response
    {
        /** @var Endpoint $endpoint */
        $endpoint = $this->endpointFactory->create([
            'type' => ArticleEndpointType::class,
            'resource' => $contentDocument,
            'preview' => $preview,
        ]);

        return $endpoint->getResponse($request);
    }
}
