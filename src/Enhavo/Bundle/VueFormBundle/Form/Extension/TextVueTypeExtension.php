<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class TextVueType extends AbstractVueType
{
    public static function supports(FormView $formView): bool
    {
        return in_array('text', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['component'] = 'form-simple';
        $data['type'] = 'text';
    }
}
