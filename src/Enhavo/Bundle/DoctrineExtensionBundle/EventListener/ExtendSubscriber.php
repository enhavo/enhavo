<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 28/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EventListener;

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
 */
class ExtendSubscriber implements EventSubscriber
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

        $extensionMetadata = $this->getMetadata($metadata->getName());
        if($extensionMetadata !== null && $extensionMetadata->getExtends()) {
            return;
        }

        $extendedMetadata  = [];
        $this->collectExtendedMetadata($metadata->getName(), $extendedMetadata);

        if (count($extendedMetadata) === 0) {
            return;
        }

        $metadata->setInheritanceType(ClassMetadata::INHERITANCE_TYPE_SINGLE_TABLE);
        $metadata->setDiscriminatorColumn([
            'name' => 'discr',
            'type' => 'string',
            'length' => 255
        ]);

        $metadata->addDiscriminatorMapClass('root', $metadata->getName());
        foreach ($extendedMetadata as $extendsMetadata) {
            $metadata->addDiscriminatorMapClass($extendsMetadata->getDiscrName(), $extendsMetadata->getClassName());
        }
    }

    /**
     * @param $className
     * @param $extendedMetadata
     */
    private function collectExtendedMetadata($className, &$extendedMetadata)
    {
        $data = $this->metadataRepository->getAllMetadata();
        /** @var Metadata $metadata */
        foreach ($data as $metadata) {
            if ($metadata->getExtends() === $className) {
                $extendedMetadata[] = $metadata;
                $this->collectExtendedMetadata($metadata->getClassName(), $extendedMetadata);
            }
        }
    }

    /*
     * @param $className
     * @return Metadata|null
     */
    private function getMetadata($className)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($className);
        return $metadata;
    }
}
