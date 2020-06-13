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

class SlugTranslator
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
     * @var EntityResolverInterface
     */
    private $entityResolver;

    /**
     * @var TextTranslator
     */
    private $textTranslator;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * SlugTranslator constructor.
     *
     * @param EntityManagerInterface $em
     * @param LocaleResolverInterface $localeResolver
     * @param $defaultLocale string
     * @param $entityResolver EntityResolverInterface
     * @param $textTranslator TextTranslator
     */
    public function __construct(
        EntityManagerInterface $em,
        LocaleResolverInterface $localeResolver,
        $defaultLocale,
        EntityResolverInterface $entityResolver,
        TextTranslator $textTranslator
    ) {
        $this->em = $em;
        $this->localeResolver = $localeResolver;
        $this->defaultLocale = $defaultLocale;
        $this->entityResolver = $entityResolver;
        $this->textTranslator = $textTranslator;
    }

    public function setTranslation($entity, $property, $locale, $value): void
    {
        $this->textTranslator->setTranslation($entity, $property, $locale, $value);
    }

    public function getTranslation($entity, $property, $locale): ?string
    {
        return $this->textTranslator->getTranslation($entity, $property, $locale);
    }

    public function fetch($class, $slug, $locale = null)
    {
        if($locale === null) {
            $locale = $this->localeResolver->resolve();
        }

        if($locale === $this->defaultLocale) {
            $entity = $this->em->getRepository($class)->findOneBy([
                'slug' => $slug
            ]);
            return $entity;
        }

        /** @var Translation $translation */
        $translation = $this->em->getRepository(Translation::class)->findOneBy([
            'class' => $this->entityResolver->getName($class),
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
