<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.09.17
 * Time: 11:33
 */

namespace App\Controller;

use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemoController
 * @package App\Controller
 * @Route("/demo")
 */
class DemoController extends AbstractController
{
    /**
     * @Route("/media", name="app_demo_media")
     */
    public function mediaAction(Request $request)
    {
        $files = [];

        $form = $this->createForm(MediaType::class, $files, [
            'multiple' => true
        ]);

        if($request->isMethod('post')) {
            $form->submit($request);
        }

        $formView = $form->createView();

        return $this->render('theme/demo/media.html.twig', [
            'form' => $formView
        ]);
    }

    /**
     * @Route("/form", name="app_demo_form")
     */
    public function formAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('date', DateType::class, [])
            ->add('datetime', TextType::class, [])
            ->add('wysiwyg', TextareaType::class, [])
            ->add('choice', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'foo' => 'foo',
                    'bar' => 'bar',
                ]
            ], [])
            ->getForm();

        $formView = $form->createView();
        $vueForm = $this->container->get(VueForm::class);
        $vueData = $vueForm->createData($form->createView());

        return $this->render('theme/demo/form.html.twig', [
            'form' => $formView,
            'vue' => $vueData
        ]);
    }
}
