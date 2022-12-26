<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VueTypeExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($view->vars['vue_data'])) {
            $view->vars['vue_data'] = new VueData();
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['component'] = $options['component'];

        if (is_string($options['component_visitors'])) {
            $view->vars['component_visitors'] = [$options['component_visitors']];
        } else {
            $view->vars['component_visitors'] = $options['component_visitors'];
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => null,
            'component_visitors' => [],
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
