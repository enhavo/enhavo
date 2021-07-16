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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemoController
 * @package App\Controller
 * @Route("/form")
 */
class FormController extends AbstractController
{
    private function handleForm(FormInterface $form, Request $request, $template = 'form')
    {
        $formView = $form->createView();
        $vueForm = $this->container->get(VueForm::class);
        $vueData = $vueForm->createData($form->createView());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['form' => $vueData, 'data' => $form->getData()], $form->isValid() ? 201 : 400);
            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['form' => $vueData]);
        }

        return $this->render(sprintf('theme/form/%s.html.twig', $template), [
            'form' => $formView,
            'vue' => $vueData,
            'data' => $form->getData()
        ]);
    }

    /**
     * @Route("/media", name="app_form_media")
     */
    public function mediaAction(Request $request)
    {
        $files = [];

        $form = $this->createForm(MediaType::class, $files, [
            'multiple' => true
        ]);

        return $this->handleForm($form, $request);
    }

    /**
     * @Route("/ajax", name="app_form_ajax")
     */
    public function ajaxAction(Request $request)
    {
        $form = $this->createForm(TextType::class);
        return $this->handleForm($form, $request, 'ajax');
    }

    /**
     * @Route("/submit", name="app_form_submit")
     */
    public function submitAction(Request $request)
    {
        $form = $this->createForm(TextType::class);
        return $this->handleForm($form, $request, 'submit');
    }

    /**
     * @Route("/compound", name="app_demo_form")
     */
    public function compoundFormAction(Request $request)
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
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }
}
