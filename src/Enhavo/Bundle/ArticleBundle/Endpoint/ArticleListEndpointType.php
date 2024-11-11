<?php

namespace Enhavo\Bundle\ArticleBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

class ArticleListEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ArticleRepository $repository,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $categories = $this->getCategoriesByRequest($request);
        $tags = $this->getTagsByRequest($request);
        $pagination = $request->get('pagination', true);
        $limit = $request->get('limit', 10);

        $articles = $this->repository->findByCategoriesAndTags($categories, $tags, $pagination, $limit);

        if ($articles instanceof Pagerfanta) {
            $page = $request->get('page', 1);
            $articles->setCurrentPage($page);
        }

        $data->get('articles', $this->normalize($articles, null, ['groups' => 'endpoint']));
    }

    public function getCategoriesByRequest(Request $request): array
    {
        return [];
    }

    public function getTagsByRequest(Request $request): array
    {
        return [];
    }
}
