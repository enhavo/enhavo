<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;


use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\ArticleBundle\Endpoint\ArticleEndpointType;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends ResourceController
{
    use TemplateResolverTrait;

    public function showResourceAction(Article $contentDocument, Request $request, bool $preview = false): Response
    {
        return $this->createEndpointResponse($request, [
            'type' => ArticleEndpointType::class,
            'resource' => $contentDocument,
            'preview' => $preview,
            'area' => 'theme',
            'navigation' => true,
            'routes' => ['theme', 'api'],
            'vue_routes' => ['theme']
        ]);
    }
}
