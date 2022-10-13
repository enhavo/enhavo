<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\FormView;

class ButtonVueType implements VueTypeInterface
{
    public static function supports(FormView $formView): bool
    {
        return in_array('button', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['rowComponent'] = 'form-button-row';
        $data['component'] = 'form-button';
    }
}
