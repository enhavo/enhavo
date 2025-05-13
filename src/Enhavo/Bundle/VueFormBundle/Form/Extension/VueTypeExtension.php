<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VueTypeExtension extends AbstractVueTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($view->vars['vue_data'])) {
            $view->vars['vue_data'] = new VueData($view);
        }
    }

    public function finishVueData(FormView $view, VueData $data, array $options)
    {
        $data->set('component', $options['component']);
        $data->set('componentModel', $options['component_model']);
        $data->set('componentVisitors', is_string($options['component_visitors']) ? [$options['component_visitors']] : $options['component_visitors']);
        $data->set('visibleCondition', $options['component_visible_condition']);
        $data->set('rowComponent', $options['row_component']);
        $data->set('widgetComponent', $options['widget_component']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => null,
            'component_model' => null,
            'component_visitors' => [],
            'component_visible_condition' => null,
            'row_component' => null,
            'widget_component' => 'form-widget',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class, ButtonType::class];
    }
}
