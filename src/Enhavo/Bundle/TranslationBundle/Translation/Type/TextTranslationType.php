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

class TextTranslationType extends AbstractTranslationType
{
    /** @var TextTranslator */
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

    public static function getName(): ?string
    {
        return 'text';
    }
}
