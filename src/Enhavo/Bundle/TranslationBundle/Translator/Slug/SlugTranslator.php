<?php
/**
 * SlugTranslator.php
 *
 * @since 28/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Slug;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Enhavo\Component\Metadata\MetadataRepository;

class SlugTranslator extends TextTranslator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LocaleResolverInterface
     */
    private $localeResolver;

    /**
     * SlugTranslator constructor.
     * @param MetadataRepository $metadataRepository
     * @param $defaultLocale
     * @param EntityResolverInterface $entityResolver
     * @param EntityManagerInterface $em
     * @param LocaleResolverInterface $localeResolver
     */
    public function __construct(MetadataRepository $metadataRepository, $defaultLocale, EntityResolverInterface $entityResolver, EntityManagerInterface $em, LocaleResolverInterface $localeResolver)
    {
        parent::__construct($metadataRepository, $defaultLocale, $entityResolver);
        $this->em = $em;
        $this->localeResolver = $localeResolver;
    }


    public function fetch($class, $slug, $locale = null)
    {
        if($locale === null) {
            $locale = $this->localeResolver->resolve();
        }

        if($locale === $this->getDefaultLocale()) {
            $entity = $this->em->getRepository($class)->findOneBy([
                'slug' => $slug
            ]);
            return $entity;
        }

        /** @var Translation $translation */
        $translation = $this->em->getRepository(Translation::class)->findOneBy([
            'class' => $this->getEntityResolver()->getName($class),
            'property' => 'slug',
            'translation' => $slug,
            'locale' => $locale
        ]);

        if($translation !== null) {
            return $translation->getObject();
        }

        return null;
    }
}
