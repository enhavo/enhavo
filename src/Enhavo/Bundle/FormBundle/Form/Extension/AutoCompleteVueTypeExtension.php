<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoCompleteVueTypeExtension extends AbstractVueTypeExtension
{
    public function finishVueData(FormView $view, VueData $data, array $options): void
    {
        $data['url'] = $view->vars['auto_complete_data']['url'];
        $data['route'] = $view->vars['auto_complete_data']['route'];
        $data['routeParameters'] = $view->vars['auto_complete_data']['route_parameters'];
        $data['value'] = $view->vars['auto_complete_data']['value'];
        $data['multiple'] = $view->vars['auto_complete_data']['multiple'];
        $data['count'] = $view->vars['auto_complete_data']['count'];
        $data['minimumInputLength'] = $view->vars['auto_complete_data']['minimum_input_length'];
        $data['placeholder'] = $view->vars['auto_complete_data']['placeholder'];
        $data['idProperty'] = $view->vars['auto_complete_data']['id_property'];
        $data['labelProperty'] = $view->vars['auto_complete_data']['label_property'];
        $data['sortable'] = $view->vars['auto_complete_data']['sortable'];
        $data['editable'] = $view->vars['auto_complete_data']['editable'];
        $data['editRoute'] = $view->vars['auto_complete_data']['edit_route'];
        $data['editRouteParameters'] = $view->vars['auto_complete_data']['edit_route_parameters'];
        $data['frameKey'] = $view->vars['auto_complete_data']['frame_key'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'form-auto-complete',
            'component_model' => 'AutoCompleteForm',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [AutoCompleteEntityType::class];
    }
}
