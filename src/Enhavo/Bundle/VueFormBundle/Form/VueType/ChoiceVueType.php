<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ChoiceVueType implements VueTypeInterface
{
    public function getComponent($options): string
    {
        return 'form-choice';
    }

    public static function getFormTypes(): array
    {
        return [ChoiceType::class => 1];
    }

    public function buildView(FormView $view, FormInterface $form, array $options, VueData $data)
    {

    }

    public function finishView(FormView $view, FormInterface $form, array $options, VueData $data)
    {
        $data['expanded'] = $view->vars['expanded'];
        $data['multiple'] = $view->vars['multiple'];
        $data['choices'] = $view->vars['choices'];
    }
}
