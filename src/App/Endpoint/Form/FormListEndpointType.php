<?php

namespace App\Endpoint\Form;

use App\Form\Type\Form\ItemsType;
use Enhavo\Bundle\FormBundle\Form\Type\ListType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/form/list', name: "app_form_list")]
class FormListEndpointType extends AbstractFormEndpointType
{
    protected function getForm(): FormInterface
    {
        return $this->createFormBuilder(null)
            ->add('items', ListType::class, [
                'entry_type' => ItemsType::class,
                'sortable' => true,
            ])
            ->add('button', SubmitType::class, [
                'label' => 'save'
            ])
            ->setMethod('POST')
            ->getForm();
    }
}
