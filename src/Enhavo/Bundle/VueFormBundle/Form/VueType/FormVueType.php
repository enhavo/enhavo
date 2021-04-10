<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class FormVueType implements VueTypeInterface
{
    public function getComponent($options): string
    {
        return 'form-form';
    }

    public static function getFormTypes(): array
    {
        return [FormType::class => 1];
    }

    public function buildView(FormView $view, FormInterface $form, array $options, VueData $data)
    {
        $data['name'] = $form->getName();
        $data['value'] = $form->getViewData();
        $data['compound'] = $view->vars['compound'];
        $data['label'] = $view->vars['label'];
    }

    public function finishView(FormView $view, FormInterface $form, array $options, VueData $data)
    {

    }
}
