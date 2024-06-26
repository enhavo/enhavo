<?php
namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;

interface DuplicateResourceFactoryInterface
{
    /**
     * @param RequestConfiguration $requestConfiguration
     * @param FactoryInterface $factory
     * @param ResourceInterface $originalResource
     *
     * @return ResourceInterface
     */
    public function duplicate(\Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration $requestConfiguration, FactoryInterface $factory, ResourceInterface $originalResource);
}
