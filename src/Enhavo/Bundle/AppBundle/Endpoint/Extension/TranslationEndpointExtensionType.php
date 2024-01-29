<?php

namespace Enhavo\Bundle\AppBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\AppBundle\Translation\TranslationDumper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationEndpointExtensionType extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly TranslationDumper $translationDumper,
        private readonly LocaleResolverInterface $localeResolver,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if (empty($options['translations'])) {
            return;
        }

        $domains = [];
        if (is_string($options['translations'])) {
            $domains[] = $options['translations'];
        } else if (is_array($options['translations'])) {
            $domains = $options['translations'];
        }

        $translations = [];
        foreach ($domains as $domain) {
            $translations[$domain] = $this->translationDumper->getTranslations($domain, $this->localeResolver->resolve());
        }

        $data->set('translations', $translations);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translations' => null,
        ]);
    }

    public static function getExtendedTypes(): array
    {
        return [ViewEndpointType::class];
    }
}
