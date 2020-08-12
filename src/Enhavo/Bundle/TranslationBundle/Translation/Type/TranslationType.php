<?php


namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType implements TranslationTypeInterface
{
    public function setTranslation(array $options, $data, string $property, string $locale, $value)
    {

    }

    public function getTranslation(array $options, $data, string $property, string $locale)
    {

    }

    public function translate($object, string $property, string $locale, array $options): void
    {

    }

    public function detach($object, string $property, string $locale, array $options)
    {

    }

    public function delete($object): void
    {

    }

    public function getValidationConstraints(array $options, $data, string $property, string $locale)
    {
        return $options['constraints'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'constraints' => null
        ]);
    }


}
