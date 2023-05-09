<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonVueTypeExtension extends AbstractVueTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-button',
            'row_component' => 'form-button-row',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ButtonType::class];
    }
}
