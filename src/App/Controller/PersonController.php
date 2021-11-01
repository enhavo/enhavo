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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/person")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(PersonType::class);

        $form->handleRequest($request);

        return $this->render('theme/person/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
