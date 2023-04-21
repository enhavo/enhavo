<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;

class ComponentVueType extends AbstractVueType
{
    public static function supports(FormView $formView): bool
    {
        return true;
    }

    public function buildView(FormView $view, VueData $data)
    {
        if (isset($view->vars['component'])) {
            $data['component'] = $view->vars['component'];
        }

        if (!isset($view->vars['component_visitors'])) {
            $data['componentVisitors'] = [];
        } else {
            if (!isset($data['componentVisitors'])) {
                $data['componentVisitors'] = [];
            }
            $data['componentVisitors'] = array_merge($data['componentVisitors'], $view->vars['component_visitors']);
        }

        if (isset($view->vars['vue_data'])) {
            /** @var ViewData $vueData */
            $vueData = $view->vars['vue_data'];
            foreach ($vueData as $key => $value) {
                $data[$key] = $value;
            }
        }
    }
}
