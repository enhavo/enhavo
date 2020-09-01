<?php
/**
 * LocaleExtension.php
 *
 * @since 12/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Twig;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TranslationExtension extends AbstractExtension
{
    /**
     * @var TranslationManager
     */
    private $translationManager;

    /**
     * TranslationExtension constructor.
     * @param TranslationManager $translationManager
     */
    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('translation_default_locale', array($this, 'getDefaultLocale')),
            new TwigFunction('translation_locales', array($this, 'getLocales')),
            new TwigFunction('translation_property', array($this, 'getProperty')),
        ];
    }

    public function getDefaultLocale()
    {
        return $this->translationManager->getDefaultLocale();
    }

    public function getLocales()
    {
        return $this->translationManager->getLocales();
    }

    public function getProperty($resource, $property, $locale)
    {
        return $this->translationManager->getProperty($resource, $property, $locale);
    }
}
