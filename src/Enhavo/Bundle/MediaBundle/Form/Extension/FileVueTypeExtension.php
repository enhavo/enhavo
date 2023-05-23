<?php

namespace Enhavo\Bundle\MediaBundle\Form\Extension;

use Enhavo\Bundle\MediaBundle\Form\Type\FileType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileVueTypeExtension extends AbstractVueTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component_model' => 'MediaItemForm',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [FileType::class];
    }
}
