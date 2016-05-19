<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

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

        // check if there are any keywords entered
        if(!empty($searchExpression)) {

            try {
                $searchEngine = $this->get('enhavo_search_search_engine');
                $result = $searchEngine->search($searchExpression);
                if(empty($result)) {
                    return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                        'data' => 'No results'
                    ));
                }

                return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                    'data' => $result
                ));

            } catch(SearchEngineException $e) {
                return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                    'data' => $e->getMessage()
                ));
            }
        } else {
            //there were no keywords entered
            return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                'data' => 'Please enter some keywords!'
            ));
        }


    }
}
