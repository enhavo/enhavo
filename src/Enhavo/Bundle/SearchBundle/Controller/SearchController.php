<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    /**
     * @var ResultConverter
     */
    private $resultConverter;

    /**
     * @var EngineInterface
     */
    private $searchEngine;

    /**
     * SearchController constructor.
     * @param ResultConverter $resultConverter
     */
    public function __construct(ResultConverter $resultConverter, EngineInterface $searchEngine)
    {
        $this->resultConverter = $resultConverter;
        $this->searchEngine = $searchEngine;
    }

    /**
     * Handle search submit
     *
     * @param Request $request
     * @return Response
     */
    public function submitAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);

        $template = $configuration->getTemplate('EnhavoSearchBundle:Admin:Search/result.html.twig');

        $term = $request->get('q', '');

        $filter = new Filter();
        $filter->setTerm($term);

        $results = $this->searchEngine->searchPaginated($filter);
        $results = $this->resultConverter->convert($results, $term);

        return $this->render($template, [
            'results' => $results,
            'term' => $term
        ]);
    }
}
