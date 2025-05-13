<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractTranslator implements TranslatorInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var EntityResolverInterface */
    protected $entityResolver;

    /** @var DataMap */
    protected $buffer;

    /** @var DataMap */
    protected $originalData;

    /** @var LocaleProviderInterface */
    protected $localeProvider;

    public function __construct(EntityManagerInterface $entityManager, EntityResolverInterface $entityResolver, LocaleProviderInterface $localeProvider)
    {
        $this->entityManager = $entityManager;
        $this->localeProvider = $localeProvider;
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
