<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Search\Filter\PermissionFilter;
use Enhavo\Bundle\SearchBundle\Search\SearchEngineException;
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
        $results = $this->convertResults($results);

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
    private function convertResults($results)
    {
        $resultConverter = $this->get('enhavo_search.result.result_converter');
        return $resultConverter->convert($results);
    }
}
