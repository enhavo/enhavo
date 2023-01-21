<?php

namespace Enhavo\Bundle\MediaBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormView;

class MediaVueType extends AbstractVueType
{
    public function __construct(
        private VueForm $vueForm
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
        $data['uploadLabel'] = 'Upload';
        $data['buttons'] = null;
        $data['maxUploadSize'] = null;
        $data['multiple'] = $view->vars['multiple'];
        $data['sortable'] = $view->vars['sortable'];
        $data['editable'] = null;
        $data['component'] = 'form-media';
        $data['componentModel'] = 'MediaForm';

//        $data['border'] = $view->vars['border'];
        $data['sortable'] = $view->vars['sortable'];
        $data['allowDelete'] = $view->vars['allow_delete'];
        $data['allowAdd'] = $view->vars['allow_add'];
//        $data['blockName'] = $view->vars['block_name'];
        $data['prototype'] = isset($view->vars['prototype']) ? $this->vueForm->createData($view->vars['prototype']) : null;
        $data['prototypeName'] = $view->vars['prototype_name'];
        $data['index'] = null;
//        $data['draggableGroup'] = $view->vars['draggable_group'];
//        $data['draggableHandle'] = $view->vars['draggable_handle'];
        $data['itemComponent'] = 'form-list-item';
        $data['onDelete'] = null;
    }

    public function finishView(FormView $view, VueData $data)
    {

    }
}
