<?php
namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface DuplicateResourceFactoryInterface
{
    /**
     * @param RequestConfiguration $requestConfiguration
     * @param FactoryInterface $factory
     * @param ResourceInterface $originalResource
     *
     * @return ResourceInterface
     */
    public function duplicate(RequestConfiguration $requestConfiguration, FactoryInterface $factory, ResourceInterface $originalResource);
}
