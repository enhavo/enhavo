<?php

namespace Enhavo\Bundle\ShopBundle\Form\Extension;

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ResourceTranslationsExtension extends AbstractTypeExtension
{
    /** @var string[] */
    private $definedLocalesCodes;

    /** @var string */
    private $defaultLocaleCode;

    public function __construct(TranslationLocaleProviderInterface $localeProvider)
    {
        $this->definedLocalesCodes = $localeProvider->getDefinedLocalesCodes();
        $this->defaultLocaleCode = $localeProvider->getDefaultLocaleCode();
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['translation_locales'] = $this->definedLocalesCodes;

        $translationForms = [];

        foreach ($view as $locale => $child) {
            foreach ($child as $key => $childForm) {
                if (!isset($forms[$key])) {
                    $translationForms[$key] = [];
                }
                $translationForms[$key][$locale] = $childForm;
            }
        }

        $view->vars['translation_forms'] = $translationForms;

        return;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ResourceTranslationsType::class];
    }
}
