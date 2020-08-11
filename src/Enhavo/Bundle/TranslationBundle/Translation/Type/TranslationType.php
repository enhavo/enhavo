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

    public function translate($object, $locale): void
    {

    }

    public function detach($object): void
    {

    }

    public function delete($object): void
    {

    }

    public function getValidationConstraints(array $options, $data, $property, $locale)
    {
        return $options['constraints'];
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'constraints' => [],
//            'generators' => null,
//            'allow_fallback' => false
//        ]);
//    }
}
