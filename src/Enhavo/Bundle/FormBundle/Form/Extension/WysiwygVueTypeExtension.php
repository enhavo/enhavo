<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WysiwygVueTypeExtension extends AbstractVueTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-wysiwyg',
            'component_model' => 'WysiwygForm',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [WysiwygType::class];
    }
}
