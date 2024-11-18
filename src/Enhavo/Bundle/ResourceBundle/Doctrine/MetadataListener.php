<?php

/**
 * DoctrineExtendListener.php
 *
 * @since 06/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Doctrine;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class MetadataListener
{
    public function __construct(
        private readonly array $resources
    )
    {
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        
        foreach ($this->resources as $resource) {
            if ($resource['classes']['model'] === $metadata->getName()) {
                $metadata->setCustomRepositoryClass($resource['classes']['repository']);
            }
        }
    }
}
