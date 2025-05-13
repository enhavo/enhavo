<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Form\Type\PersonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/person')]
class PersonController extends AbstractController
{
    #[Route('/', name: 'app_theme_person_index', condition: 'context.getResolver()')]
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
