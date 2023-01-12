<?php

namespace Enhavo\Bundle\MediaBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class MediaVueType extends AbstractVueType
{
    public static function supports(FormView $formView): bool
    {
        return in_array('enhavo_media', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['upload'] = null;
        $data['uploadLabel'] = null;
        $data['buttons'] = null;
        $data['maxUploadSize'] = null;
        $data['multiple'] = null;
        $data['sortable'] = null;
        $data['editable'] = null;
        $data['component'] = 'form-media';
    }

    public function finishView(FormView $view, VueData $data)
    {

    }
}
