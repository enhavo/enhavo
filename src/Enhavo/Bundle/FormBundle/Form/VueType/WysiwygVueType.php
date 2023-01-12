<?php

namespace Enhavo\Bundle\FormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class WysiwygVueType extends AbstractVueType
{
    public static function supports(FormView $formView): bool
    {
        return in_array('enhavo_wysiwyg', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['component'] = 'form-wysiwyg';
        $data['componentModel'] = 'FormWysiwyg';
    }
}
