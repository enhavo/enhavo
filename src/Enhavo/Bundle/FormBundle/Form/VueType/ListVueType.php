<?php

namespace Enhavo\Bundle\FormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormView;

class ListVueType extends AbstractVueType
{
    public function __construct(
        private VueForm $vueForm
    )
    {
    }

    public static function supports(FormView $formView): bool
    {
        return in_array('enhavo_list', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['border'] = $view->vars['border'];
        $data['sortable'] = $view->vars['sortable'];
        $data['allowDelete'] = $view->vars['allow_delete'];
        $data['allowAdd'] = $view->vars['allow_add'];
        $data['blockName'] = $view->vars['block_name'];
        $data['prototype'] = $this->vueForm->createData($view->vars['prototype']);
        $data['prototypeName'] = $view->vars['prototype_name'];
        $data['index'] = $view->vars['index'];


        $data['component'] = 'form-list';

        $data['itemComponent'] = 'form-list-item';
        $data['onDelete'] = null;
    }

    public function finishView(FormView $view, VueData $data)
    {

    }
}
