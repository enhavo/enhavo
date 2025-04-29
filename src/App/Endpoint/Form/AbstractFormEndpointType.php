<?php

namespace App\Endpoint\Form;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFormEndpointType extends AbstractEndpointType
{
    public function __construct(
        private VueForm $vueForm,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $form = $this->getForm();
        $formView = $form->createView();
        $vueFormData = $this->vueForm->createData($formView);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($request->isXmlHttpRequest()) {
                $context->setResponse(new JsonResponse(['form' => $vueFormData, 'data' => $form->getData()], $form->isValid() ? 201 : 400));
                return;
            }
        }

        if ($request->isXmlHttpRequest()) {
            $context->setResponse(new JsonResponse(['form' => $vueFormData]));
            return;
        }

        $formView = $form->createView();

        $data->add([
            'form' => $this->vueForm->createData($formView),
            'data' => $form->getData(),
        ]);
    }

    abstract protected function getForm(): FormInterface;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => 'theme/form/form.html.twig',
        ]);
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }
}
