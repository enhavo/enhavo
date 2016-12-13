<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
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

        // set the current entry to search expression
        $searchExpression = $request->get('q', '');
        $searchTypes = $request->get('entities');
        $searchFields = $request->get('fields', []);

        // check if there are any keywords entered
        if(!empty($searchExpression)) {

            try {
                $engine = $this->container->getParameter('enhavo_search.search.search_engine');
                $searchEngine = $this->get($engine);
                $filter = new PermissionFilter($this->container);

                //get search results
                $result = $searchEngine->search($searchExpression, array($filter), $searchTypes, $searchFields);
                if(empty($result)) {
                    return $this->render($template, array(
                        'results' => $this->get('translator')->trans('search.label.no_results', [], 'EnhavoSearchBundle'),
                        'expression' => $searchExpression
                    ));
                }

                $user = $this->getUser();
                if($user == null || !$user->isAdmin()){
                    $publicResults = [];
                    foreach ($result->getResources() as $resource) {
                        if($resource instanceof Publishable){
                            if($resource->isPublic()){
                                $publicResults[] = $resource;
                            }
                        } else {
                            $publicResults[] = $resource;
                        }
                    }
                    $result->setResources($publicResults);
                }

                // highlighting
                $resourcesBefore = $result->getResources();
                $resourcesBefore = array_filter($resourcesBefore);
                $resourcesAfter = array();
                foreach ($resourcesBefore as $resource) {
                    $resourcesAfter[] = $this->get('enhavo_search_highlight')->highlight($resource, $result->getWords());
                }
                $result->setResources($resourcesAfter);

                //get resource to render
                return $this->render($template, array(
                    'results' => $result->getResources(),
                    'expression' => $searchExpression
                ));
            } catch(SearchEngineException $e) {

                //exception
                return $this->render($template, array(
                    'results' => $e->getMessage(),
                    'expression' => $searchExpression
                ));
            }
        } else {
            //there were no keywords entered
            return $this->render($template, array(
                'results' => $this->get('translator')->trans('search.form.error.blank', [], 'EnhavoSearchBundle'),
                'expression' => $searchExpression
            ));
        }
    }
}
