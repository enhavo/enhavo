<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Enhavo\Bundle\SearchBundle\Search\Filter\PermissionFilter;
use Enhavo\Bundle\SearchBundle\Search\SearchEngineException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    //Happens when someone pressed the submit button of the search field.
    public function submitAction(Request $request)
    {
        //set the current entry to searchEspression
        $searchExpression = $request->get('search');
        $searchTypes = $request->get('entities');
        $searchFields = $request->get('fields');

        // check if there are any keywords entered
        if(!empty($searchExpression)) {

            try {
                $engine = $this->container->getParameter('enhavo_search.search.search_engine');
                $searchEngine = $this->get($engine);
                $filter = new PermissionFilter($this->container);

                //get search results
                $result = $searchEngine->search($searchExpression, array($filter), $searchTypes, $searchFields);
                if(empty($result)) {
                    return $this->render('EnhavoSearchBundle:Search:result.html.twig', array(
                        'data' => 'No results'
                    ));
                }

                //do the highlighting
                $resourcesBefore = $result->getResources();
                $resourcesBefore = array_filter($resourcesBefore);
                $resourcesAfter = array();
                foreach ($resourcesBefore as $resource) {
                    $resourcesAfter[] = $this->get('enhavo_search_highlight')->highlight($resource, $result->getWords());
                }
                $result->setResources($resourcesAfter);

                //get resource to render
                return $this->render('EnhavoSearchBundle:Search:result.html.twig', array(
                    'data' => $result->getResources()
                ));
            } catch(SearchEngineException $e) {

                //exception
                return $this->render('EnhavoSearchBundle:Search:result.html.twig', array(
                    'data' => $e->getMessage()
                ));
            }
        } else {

            //there were no keywords entered
            return $this->render('EnhavoSearchBundle:Search:result.html.twig', array(
                'data' => $this->get('translator')->trans('search.form.error.blank', [], 'EnhavoSearchBundle')
            ));
        }
    }
}
