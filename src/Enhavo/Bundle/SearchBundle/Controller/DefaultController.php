<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Enhavo\Bundle\SearchBundle\Entity\Index;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EnhavoSearchBundle:Default:index.html.twig', array('name' => $name));
    }

    public function submitAction(Request $request)
    {
        //normalize berechnen
        $normalize = 0;
        $result = $this->getDoctrine()
            ->getRepository('EnhavoSearchBundle:Index')
            ->getCalculatedScore($request->get('search'));
        $normalize = (float) $result['calculated_score'];
        $normalization = null;
        if($normalize != 0) {
            $normalization = 1.0 / $normalize;
        }

        if ($this->isSearchExecutable() && $normalization != null) {
            //results suchen und ordnen (hier kriegt man die index id)
            $results = $this->getDoctrine()
                ->getRepository('EnhavoSearchBundle:Index')
                ->getSearchResults($request->get('search'), $normalization);
            //Results über Index in der richtigen Reihenfolge holen (index -> dataset -> artikel)
            $data = array();
            foreach($results as $resultIndex) {
                $currentIndex = $this->getDoctrine()
                    ->getRepository('EnhavoSearchBundle:Index')
                    ->findOneBy(array('id' => $resultIndex['id']));
                $currentDataset = $currentIndex->getDataset();
                $dataForSearchResult = array();
                $dataForSearchResult['type'] = $currentDataset->getType();
                $dataForSearchResult['reference'] = $currentDataset->getReference();
                $data[] = $dataForSearchResult;
            }

            $finalResults = array();
            foreach($data as $resultData) {
                $currentData = $this->getDoctrine()
                    ->getRepository('Enhavo'.ucfirst($resultData['type']).'Bundle:'.ucfirst($resultData['type']))
                    ->findOneBy(array('id' => $resultData['reference']));
                $finalResults[] = $currentData;
            }

            //Jetzt aus type entity generieren

            if ($finalResults) {
                return $this->render('EnhavoSearchBundle:Default:show.html.twig', array(
                    'data' => $finalResults
                ));
            }
        }

        return $this->render('EnhavoSearchBundle:Default:show.html.twig', array(
            'data' => 'No results'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function isSearchExecutable() {
        // Node search is executable if we have keywords or an advanced parameter.
        // At least, we should parse out the parameters and see if there are any
        // keyword matches in that case, rather than just printing out the
        // "Please enter keywords" message.
        return true;//!empty($this->keywords) || (isset($this->searchParameters['f']) && count($this->searchParameters['f']));
    }
}
