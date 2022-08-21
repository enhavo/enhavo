<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 11:33
 */

namespace App\Controller;

use App\Form\Type\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/person")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/", name="app_theme_person_index")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(PersonType::class);

        $form->handleRequest($request);

        return $this->render('theme/person/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function exportAction(Request $request)
    {
        $response = new Response(sprintf('Export Data (%s %s)', $request->get('from'), $request->get('to')));
        $response->headers->set('Content-Disposition', 'attachment; filename="export.txt"');
        return $response;
    }
}
