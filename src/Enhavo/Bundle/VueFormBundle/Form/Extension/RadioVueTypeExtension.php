<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class RadioVueType extends AbstractVueType
{
    public static function supports(FormView $formView): bool
    {
        return in_array('radio', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['component'] = 'form-radio';
        $data['componentModel'] = 'FormRadio';
        $data['checked'] = $view->vars['checked'];
    }
}
