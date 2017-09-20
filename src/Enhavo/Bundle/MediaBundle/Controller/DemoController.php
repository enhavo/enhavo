<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 11:33
 */

namespace Enhavo\Bundle\MediaBundle\Controller;


use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller
{
    public function demoAction(Request $request)
    {
        $files = $this->get('doctrine.orm.default_entity_manager')->getRepository('EnhavoMediaBundle:File')->findBy([], [], 10);

        $form = $this->createForm(MediaType::class, $files);

        return $this->render('EnhavoMediaBundle:Demo:demo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}