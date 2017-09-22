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
        $em = $this->get('doctrine.orm.default_entity_manager');
        $files = $em->getRepository('EnhavoMediaBundle:File')->findBy([], [], 2);
        //$files = [];

        $form = $this->createForm(MediaType::class, $files);

        if($request->isMethod('post')) {
            $form->submit($request);
            $data = $form->getData();
            if($form->isValid()) {
                $em->flush();
            }
        }

        $formView = $form->createView();

        return $this->render('EnhavoMediaBundle:Demo:demo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}