<?php
namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;

interface DuplicateResourceFactoryInterface extends NewResourceFactoryInterface
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
