<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
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

        $engine = $this->container->get('enhavo_search.engine.elastic_search_engine');
        $filter = new Filter();
        $result = $engine->search($filter);
        var_dump($result);
        exit();
        
        return $this->render($template, array(
            'results' => [],
            'expression' => []
        ));
    }
}
