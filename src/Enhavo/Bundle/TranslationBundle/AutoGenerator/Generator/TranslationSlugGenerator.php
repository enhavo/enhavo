<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator\Generator;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator\SlugGenerator;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;

class TranslationSlugGenerator extends SlugGenerator
{
    /** @var TranslationManager */
    private $translationManager;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * TranslationSlugGenerator constructor.
     * @param TranslationManager $translationManager
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(TranslationManager $translationManager, TranslatorInterface $translator, EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->translationManager = $translationManager;
        $this->translator = $translator;
    }

    public function generate($resource, $options = [])
    {
        parent::generate($resource, $options);

        $locales = $this->translationManager->getLocales();

        foreach ($locales as $locale) {
            if ($locale == $this->translationManager->getDefaultLocale()) {
                continue;
            }

            $value = $this->translator->getTranslation($resource, $options['property'], $locale);

            if ($value !== null) {
                if (!$options['overwrite'] && $this->translator->getTranslation($resource, $options['slug_property'], $locale)) {
                    return;
                }

                $this->translator->setTranslation($resource, $options['slug_property'], $locale, Slugifier::slugify($value));
            }
        }
    }

    public function getType()
    {
        return 'translation_slug';
    }
}
