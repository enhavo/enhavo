<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionVueTypeExtension extends AbstractVueTypeExtension
{
    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['position'] = true;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component_model' => 'PositionForm',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [PositionType::class];
    }
}
