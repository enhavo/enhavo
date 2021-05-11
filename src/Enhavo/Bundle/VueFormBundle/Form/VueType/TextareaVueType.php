<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TextareaVueType implements VueTypeInterface
{
    public function getComponent($options): string
    {
        return 'form-textarea';
    }

    public static function getFormTypes(): array
    {
        return [TextareaType::class => 1];
    }

    public function buildView(FormView $view, FormInterface $form, array $options, VueData $data)
    {

    }

    public function finishView(FormView $view, FormInterface $form, array $options, VueData $data)
    {

    }
}
