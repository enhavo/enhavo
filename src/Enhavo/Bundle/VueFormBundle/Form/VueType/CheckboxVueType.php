<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class CheckboxVueType implements VueTypeInterface
{
    public function getComponent(): ?string
    {
        return 'form-checkbox';
    }

    public static function getBlocks(): array
    {
        return ['checkbox' => 1];
    }

    public function buildView(FormView $view, VueData $data)
    {

    }
}
