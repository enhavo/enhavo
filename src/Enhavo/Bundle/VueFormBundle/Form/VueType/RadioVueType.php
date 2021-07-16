<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class RadioVueType implements VueTypeInterface
{
    public function getComponent(): ?string
    {
        return 'form-radio';
    }

    public static function getBlocks(): array
    {
        return ['radio' => 1];
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['fullName'] = $view->vars['full_name'];
    }
}
