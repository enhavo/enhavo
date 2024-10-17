<?php

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Type\HtmlTagType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlTagVueTypeExtension extends AbstractVueTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-html-tag',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [HtmlTagType::class];
    }
}
