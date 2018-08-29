<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AppController
{
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

        $engine = $this->getEngine();

        $filter = new Filter();
        $filter->setTerm($term);

        $results = $engine->searchPaginated($filter);
        $results = $this->convertResults($results, $term);

        return $this->render($template, [
            'results' => $results,
            'term' => $term
        ]);
    }

    /**
     * @return EngineInterface
     */
    private function getEngine()
    {
        $engineName = $this->container->getParameter('enhavo_search.search.engine');
        /** @var EngineInterface $engine */
        $engine = $this->container->get($engineName);
        return $engine;
    }

    /**
     * @param $results
     * @return mixed
     */
    private function convertResults($results, $term)
    {
        $resultConverter = $this->get('enhavo_search.result.result_converter');
        return $resultConverter->convert($results, $term);
    }
}
