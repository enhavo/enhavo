<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitVueTypeExtension extends AbstractVueTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-submit',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [SubmitType::class];
    }
}
