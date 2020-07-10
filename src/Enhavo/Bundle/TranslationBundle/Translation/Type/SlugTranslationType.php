<?php
/**
 * SlugTranslationStrategy.php
 *
 * @since 20/04/17
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Slug\SlugTranslator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlugTranslationType extends AbstractTranslationType
{
    /** @var SlugTranslator */
    private $slugTranslator;

    /**
     * SlugTranslationType constructor.
     * @param SlugTranslator $slugTranslator
     */
    public function __construct(SlugTranslator $slugTranslator)
    {
        $this->slugTranslator = $slugTranslator;
    }

    public function setTranslation(array $options, $data, $property, $locale, $value)
    {
        $this->slugTranslator->setTranslation($data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, $property, $locale)
    {
        return $this->slugTranslator->getTranslation($data, $property, $locale);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'form_type' => TextType::class,
            'generators' => []
        ]);
    }

    public static function getName(): ?string
    {
        return 'slug';
    }
}
