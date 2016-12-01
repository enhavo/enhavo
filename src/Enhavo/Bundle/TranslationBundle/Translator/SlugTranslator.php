<?php
/**
 * SlugTranslator.php
 *
 * @since 28/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;


use Doctrine\ORM\EntityManagerInterface;

class SlugTranslator
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var LocaleResolver
     */
    protected $localeResolver;

    public function __construct(EntityManagerInterface $em, LocaleResolver $localeResolver)
    {
        $this->em = $em;
        $this->localeResolver = $localeResolver;
    }

    public function fetch($slug, $class)
    {
        if($this->localeResolver->isDefaultLocale()) {
            $entity = $this->em->getRepository($class)->findOneBy([
                'slug' => $slug
            ]);
            return $entity;
        }
        
        $translation = $this->em->getRepository('EnhavoTranslationBundle:Translation')->findOneBy([
            'class' => $class,
            'property' => 'slug',
            'translation' => $slug,
            'locale' => $this->localeResolver->getLocale()
        ]);

        if($translation !== null) {
            $id = $translation->getRefId();
            $entity = $this->em->getRepository($class)->find($id);
            return $entity;
        }
        return null;
    }
}