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
        $data['upload'] = $view->vars['upload'];
        $data['uploadLabel'] = 'Upload';
        $data['buttons'] = null;
        $data['maxUploadSize'] = null;
        $data['editable'] = true;

        $data['component'] = $data['component'] == 'form-list' ? 'form-media' : $data['component'];
        $data['itemComponent'] = $data['itemComponent'] == 'form-list-item' ? 'form-media-item' : $data['itemComponent'];
        $data['componentModel'] = $data['componentModel'] == 'FormList' ? 'MediaForm' : $data['componentModel'];
    }

    public function finishView(FormView $view, VueData $data)
    {

    }
}
