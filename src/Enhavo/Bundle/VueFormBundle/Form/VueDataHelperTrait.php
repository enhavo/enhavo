<?php

namespace Enhavo\Bundle\VueFormBundle\Form;

use Enhavo\Bundle\VueFormBundle\Exception\VueFormException;
use Symfony\Component\Form\FormView;

trait VueDataHelperTrait
{
    protected function getVueData(FormView $view): VueData
    {
        if (!isset($view->vars['vue_data'])) {
            throw VueFormException::missingVueData();
        }

        $data = $view->vars['vue_data'];

        if (!$data instanceof VueData) {
            throw VueFormException::missingVueData();
        }
        return $data;
    }
}
