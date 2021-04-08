<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Symfony\Component\Form\FormView;

class VueForm
{
    public function createData(FormView $formView)
    {
        return [
            'name' => '',
            'component' => '',
            'value' => '',
            'view' => '',
            'children' => ''
        ];
    }

    public function submit($data)
    {
        return [];
    }
}
