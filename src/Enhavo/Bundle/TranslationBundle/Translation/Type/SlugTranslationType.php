<?php
/**
 * SlugTranslationStrategy.php
 *
 * @since 20/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlugTranslationType extends AbstractTranslationType
{
    public function setTranslation(array $options, $data, string $property, string $locale, $value)
    {
        $this->translator->setTranslation($data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, string $property, string $locale)
    {
        return $this->translator->getTranslation($data, $property, $locale);
    }

    public static function getName(): ?string
    {
        return 'slug';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_fallback' => false
        ]);
    }
}
