<?php
/**
 * TranslationTableStrategy.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextTranslationType extends AbstractTranslationType
{
    /**
     * @var TextTranslator
     */
    private $translator;

    /**
     * TextTranslationType constructor.
     * @param TextTranslator $translator
     */
    public function __construct(TextTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function setTranslation(array $options, $data, $property, $locale, $value)
    {
        $this->translator->setTranslation($data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, $property, $locale)
    {
        return $this->translator->getTranslation($data, $property, $locale);
    }

    public function getType()
    {
        return 'text';
    }

   public function getValidationConstraints(array $options, $data, $property, $locale)
    {
        return $options['constraints'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'constraints' => []
        ]);
    }
}
