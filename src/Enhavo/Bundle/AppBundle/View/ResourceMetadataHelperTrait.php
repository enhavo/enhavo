<?php

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Sylius\Component\Resource\Metadata\MetadataInterface;

trait ResourceMetadataHelperTrait
{
    public function getRequestConfiguration($options): RequestConfiguration
    {
        return $options['request_configuration'];
    }

    public function getMetadata($options): MetadataInterface
    {
        return $this->getRequestConfiguration($options)->getMetadata();
    }
}
