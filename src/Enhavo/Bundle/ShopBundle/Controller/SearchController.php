<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\BetweenQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\Filter\MatchQuery;
use Enhavo\Bundle\SearchBundle\Result\ResultConverter;
use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    public function __construct(
        private ResultConverter $resultConverter,
        private EngineInterface $searchEngine,
        private TemplateManager $templateManager,
        private ProductManager $productManager,
    ) {}

    public function resultAction(Request $request): Response
    {
        $term = $request->get('q', '');
        $page = $request->get('page', 1);
        $maxPerPage = $request->get('maxPerPage', 16);

        $pagination = $this->searchEngine->searchPaginated($this->createFilterQuery($request));

        $pagination->setMaxPerPage($maxPerPage);
        $pagination->setCurrentPage($page);

        $result = $this->productManager->getVariantProxies($pagination);

        return $this->render($this->templateManager->getTemplate('theme/shop/search/result.html.twig'), [
            'results' => $result,
            'pagination' => $pagination,
            'term' => $term,
        ]);
    }

    private function createFilterQuery(Request $request): Filter
    {
        $term = $request->get('q', '');
        $limit = $request->get('limit', 100);

        $filter = new Filter();
        $filter->setTerm($term);
        $filter->setLimit($limit);
        $filter->setClass(ProductVariant::class);

        $filter->addQuery('index', new MatchQuery(true));
        $filter->addQuery('enabled', new MatchQuery(true));

        if ($request->get('onlyDefault')) {
            $filter->addQuery('default', new MatchQuery(true));
        }

        if ($request->get('category')) {
            $filter->addQuery('category', new MatchQuery($request->get('category')));
        }

        if ($request->get('priceFrom') && $request->get('priceTo')) {
            $filter->addQuery('price', new BetweenQuery($request->get('priceFrom'), $request->get('priceTo')));
        } elseif ($request->get('priceFrom')) {
            $filter->addQuery('price', new MatchQuery($request->get('priceFrom'), MatchQuery::OPERATOR_GREATER_EQUAL));
        } elseif ($request->get('priceTo')) {
            $filter->addQuery('price', new MatchQuery($request->get('priceTo'), MatchQuery::OPERATOR_LESS_EQUAL));
        }

        return $filter;
    }
}
