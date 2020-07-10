<?php


namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType implements TranslationTypeInterface
{
    public function setTranslation(array $options, $data, $property, $locale, $value)
    {

    }

    public function getTranslation(array $options, $data, $property, $locale)
    {

    }

    public function getValidationConstraints(array $options, $data, $property, $locale)
    {
        return $options['constraints'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'constraints' => []
        ]);
    }
}
