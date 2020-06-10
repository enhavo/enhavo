<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 28/07/16
 * @author gseidel
 */

namespace Enhavo\Component\DoctrineExtension\Extend;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;

/**
 * Class DoctrineExtendSubscriber
 *
 * Check if a target class was extended and then add a single table inheritance
 * to that class and its metadata.
 *
 * @package Enhavo\Bundle\ArticleBundle\EventListener
 */
class DoctrineExtendSubscriber implements EventSubscriber
{
    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    public function __construct(MetadataRepository $metadataRepository)
    {
        $this->metadataRepository = $metadataRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if($this->metadataRepository->hasMetadata($metadata->getName())) {
           return;
        }

        /** @var Metadata $metadataExtension */
        $metadataExtension = $this->metadataRepository->getMetadata($metadata->getName());

        if ($metadata->getName() != $this->targetClass) {
            return;
        }

        if($this->targetClass == $this->extendedClass || !$this->extend) {
            return;
        }

        $metadata->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_SINGLE_TABLE);
        $metadata->setDiscriminatorColumn([
            'name' => 'discr',
            'type' => 'string',
            'length' => 6
        ]);

        $metadata->addDiscriminatorMapClass('target', $this->targetClass);
        $metadata->addDiscriminatorMapClass('extend', $this->extendedClass);
    }
}
