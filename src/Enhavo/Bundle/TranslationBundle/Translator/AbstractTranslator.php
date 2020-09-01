<?php


namespace Enhavo\Bundle\TranslationBundle\Translator;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractTranslator implements TranslatorInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var EntityResolverInterface
     */
    protected $entityResolver;

    /**
     * @var DataMap
     */
    protected $buffer;

    /**
     * @var DataMap
     */
    protected $originalData;


    public function __construct(EntityManagerInterface $entityManager, EntityResolverInterface $entityResolver, $defaultLocale)
    {
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
        $this->entityResolver = $entityResolver;

        $this->buffer = new DataMap();
        $this->originalData = new DataMap();
    }

    public function detach($entity, string $property, string $locale, array $options)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $originalValue = $this->originalData->load($entity, $property, null);
        $translationValue = $accessor->getValue($entity, $property);
        $this->setTranslation($entity, $property, $locale, $translationValue);
        $accessor->setValue($entity, $property, $originalValue);

        $this->originalData->delete($entity);
    }

    public function getDefaultValue($entity, string $property)
    {
        $originalValue = $this->originalData->load($entity, $property, null);

        if (null === $originalValue) {
            $accessor = PropertyAccess::createPropertyAccessor();
            return $accessor->getValue($entity, $property);
        }

        return $originalValue;
    }

    public function delete($entity, string $property)
    {
        $translations = $this->getRepository()->findBy([
            'class' => $this->entityResolver->getName($entity),
            'property' => $property,
            'refId' => $entity->getId(),
        ]);

        foreach ($translations as $translation) {
            $this->entityManager->remove($translation);
        }
    }
}
