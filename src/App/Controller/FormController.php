<?php

namespace App\Controller;

use App\Form\Type\Form\ItemsNestedType;
use App\Form\Type\Form\ItemsType;
use App\Form\Type\Form\ItemsWysiwygType;
use Enhavo\Bundle\FormBundle\Form\Type\ConditionalType;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/form')]
class FormController extends AbstractController
{
    private function handleForm(FormInterface $form, Request $request, $template = 'form', $theme = false)
    {
        $formView = $form->createView();
        $vueForm = $this->container->get(VueForm::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['form' => $vueForm->createData($form->createView()), 'data' => $form->getData()], $form->isValid() ? 201 : 400);
            }
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['form' => $vueForm->createData($form->createView())]);
        }

        $formView = $form->createView();

        return $this->render(sprintf('theme/form/%s.html.twig', $template), [
            'form' => $formView,
            'vue' => $vueForm->createData($formView),
            'data' => $form->getData(),
            'theme' => $theme
        ]);
    }

    #[Route('/media', name: "app_form_media")]
    public function mediaAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('media', MediaType::class, [
                'multiple' => false
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/media-multiple', name: "app_form_media_multiple")]
    public function mediaMultipleAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('media', MediaType::class, [
                'multiple' => true
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/ajax', name: "app_form_ajax")]
    public function ajaxAction(Request $request)
    {
        $form = $this->createForm(TextType::class);
        return $this->handleForm($form, $request, 'ajax');
    }

    #[Route('/submit', name: "app_form_submit")]
    public function submitAction(Request $request)
    {
        $form = $this->createForm(TextType::class);
        return $this->handleForm($form, $request, 'submit');
    }

    #[Route('/compound', name: "app_demo_form")]
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

    #[Route('/list', name: "app_form_list")]
    public function listAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('items', ListType::class, [
                'entry_type' => ItemsType::class,
                'sortable' => true,
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/list-nested', name: "app_form_list_nested")]
    public function listNestedAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('items', ListType::class, [
                'entry_type' => ItemsNestedType::class,
                'sortable' => true,
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/list-connected', name: "app_form_list_connected")]
    public function listConnectedAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('items', ListType::class, [
                'entry_type' => ItemsType::class,
                'sortable' => true,
                'draggable_group' => 'item'
            ])
            ->add('items2', ListType::class, [
                'entry_type' => ItemsType::class,
                'sortable' => true,
                'draggable_group' => 'item'
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/list-custom', name: "app_form_list_custom")]
    public function listCustomAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('items', ListType::class, [
                'entry_type' => ItemsType::class,
                'sortable' => true,
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request, theme: true);
    }

    #[Route('/diff', name: "app_form_diff")]
    public function diffAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('text', TextType::class, [
                'label' => 'My text'
            ])
//            ->add('button', SubmitType::class, [
//                'label' => 'save'
//            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request, 'diff');
    }

    #[Route('/checkbox', name: "app_form_checkbox")]
    public function checkboxAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('enabled', CheckboxType::class, [
                'label' => 'Enabled',
                'required' => false,
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/text', name: "app_form_text")]
    public function textAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('text', TextType::class, [
                'label' => 'Text',
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/radio', name: "app_form_radio")]
    public function radioAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ]
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/select', name: "app_form_select")]
    public function selectAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ]
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/wysiwyg', name: "app_form_wysiwyg")]
    public function wysiwygAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('text', WysiwygType::class, [
                'label' => 'Text',
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/wysiwyg-list', name: "app_form_wysiwyg_list")]
    public function wysiwygListAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('items', ListType::class, [
                'entry_type' => ItemsWysiwygType::class,
                'sortable' => true,
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request, theme: true);
    }

    #[Route('/poly-collection', name: "app_form_poly_collection")]
    public function polyCollectionAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('items', PolyCollectionType::class, [
                'entry_types' => [
                    'text' => TextType::class,
                    'date' => DateType::class,
                ],
                'entry_types_options' => [
                    'text' => ['label' => 'I am a label'],
                    'date' => ['label' => 'Type in the date'],
                ],
                'entry_type_resolver' => function($data) {
                    return $data instanceof DateType ? 'date' : 'text';
                },
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request);
    }

    #[Route('/conditional', name: "app_form_poly_collection")]
    public function conditionalAction(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Text' => 'text',
                    'Date' => 'date',
                ],
                'component_visitors' => ['type_conditional'],
            ])
            ->add('field', ConditionalType::class, [
                'entry_types' => [
                    'text' => TextType::class,
                    'date' => DateType::class,
                ],
                'entry_types_options' => [
                    'text' => ['label' => 'I am a label'],
                    'date' => ['label' => 'Type in the date'],
                ],
                'entry_type_resolver' => function($data, Form $form) {
                    return $form->getParent()?->get('type')?->getData();
                },
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();

        return $this->handleForm($form, $request, theme: true);
    }
}
