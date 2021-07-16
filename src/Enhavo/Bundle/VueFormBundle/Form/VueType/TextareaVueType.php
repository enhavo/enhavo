<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormView;

class TextareaVueType implements VueTypeInterface
{
    public function getComponent(): ?string
    {
        return 'form-textarea';
    }

    public static function getBlocks(): array
    {
        return ['textarea' => 1];
    }

    public function buildView(FormView $view, VueData $data)
    {

    }
}
