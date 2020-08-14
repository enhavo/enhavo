<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use Enhavo\Component\Type\AbstractType;

abstract class AbstractTranslationType extends AbstractType implements TranslationTypeInterface
{
    /** @var TranslationTypeInterface */
    protected $parent;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * TextTranslationType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function translate($object, string $property, string $locale, array $options): void
    {
        $this->translator->translate($object, $property, $locale, $options);
    }

    public function detach($object, string $property, string $locale, array $options): void
    {
        $this->translator->detach($object, $property, $locale, $options);
    }

    public function delete($object): void
    {
        $this->translator->delete($object);
    }

    public function setTranslation(array $options, $data, string $property, string $locale, $value)
    {
        $this->parent->setTranslation($options, $data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, string $property, string $locale)
    {
        return $this->parent->getTranslation($options, $data, $property, $locale);
    }

    public function getValidationConstraints(array $options, $data, string $property, string $locale)
    {
        return $this->parent->getValidationConstraints($options, $data, $property, $locale);
    }

    public static function getParentType(): ?string
    {
        return TranslationType::class;
    }
}
