<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    //Happens when someone pressed the submit button of the search field.
    public function submitAction(Request $request)
    {
        //set the current entry to searchEspression
        $searchExpression = $request->get('search');

        // check if there are any keywords entered
        if(!empty($searchExpression)) {

            $searchEngine = $this->get('enhavo_search_search_engine');
            //if there are keywords
            $searchEngine->parseSearchExpression($searchExpression);

            //go on if there are not to many AND/OR expressions
            if (!$searchEngine->toManyExpressions) {

                $results = $searchEngine->search();
                if ($results) {

                    //return results
                    return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                        'data' => $results
                    ));
                }
            } else {
                //To many AND/OR expressions
                return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                    'data' => 'There are to many AND/OR expressions!'
                ));
            }
        } else {
            //there were no keywords entered
            return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
                'data' => 'Please enter some keywords!'
            ));
        }

        return $this->render('EnhavoSearchBundle:Default:result.html.twig', array(
            'data' => 'No results'
        ));
    }
}
