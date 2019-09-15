<?php
/**
 * AutoGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator;

use Enhavo\Bundle\AppBundle\Metadata\MetadataRepository;
use Enhavo\Bundle\AppBundle\Type\CollectorInterface;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleAutoGenerator
{
    /**
     * @var CollectorInterface
     */
    protected $collector;

    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * @var string[]
     */
    private $locales;

    public function __construct(CollectorInterface $collector, MetadataRepository $metadataRepository, $locales, $defaultLocale)
    {
        $this->collector = $collector;
        $this->metadataRepository = $metadataRepository;

        $this->locales = [];
        foreach($locales as $locale) {
            if($defaultLocale != $locale) {
                $this->locales[] = $locale;
            }
        }
    }

    public function generate($resource)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);
        foreach($metadata->getProperties() as $property) {
            if($property->getType() == 'route' || $property->getType() == 'slug') {
                $options = $property->getOptions();
                $generators = isset($options['generators']) ? $options['generators'] : [];
                foreach($generators as $generatorConfig) {
                    $this->executeGenerator($resource, $property->getProperty(), $generatorConfig);
                }
            }
        }
    }

    private function executeGenerator($resource, $property, $generatorConfig)
    {
        $type = $generatorConfig['type'];
        unset($generatorConfig['type']);

        /** @var LocaleGeneratorInterface $generator */
        $generator = $this->collector->getType($type);
        $optionsResolver = new OptionsResolver();
        $generator->configureOptions($optionsResolver);
        $options = $optionsResolver->resolve($generatorConfig);
        foreach($this->locales as $locale) {
            $generator->generate($resource, $property, $locale, $options);
        }
    }
}
