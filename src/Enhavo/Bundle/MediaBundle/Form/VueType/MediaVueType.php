<?php

namespace Enhavo\Bundle\MediaBundle\Form\VueType;

use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class MediaVueType extends AbstractVueType
{
    public function __construct(
        private MediaManager $mediaManager,
    )
    {
    }

    public static function supports(FormView $formView): bool
    {
        return in_array('enhavo_media', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['upload'] = $view->vars['upload'];
        $data['multiple'] = $view->vars['multiple'];
        $data['uploadLabel'] = 'Upload';
        $data['buttons'] = null;
        $data['maxUploadSize'] = $this->mediaManager->getMaxUploadSize();
        $data['editable'] = true;

        $data['component'] = $data['component'] == 'form-list' ? 'form-media' : $data['component'];
        $data['itemComponent'] = $data['itemComponent'] == 'form-list-item' ? 'form-media-item' : $data['itemComponent'];
        $data['componentModel'] = $data['componentModel'] == 'ListForm' ? 'MediaForm' : $data['componentModel'];
    }

    public function finishView(FormView $view, VueData $data)
    {

    }
}
