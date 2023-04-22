<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordVueTypeExtension extends AbstractVueTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-simple',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [PasswordType::class];
    }
}
