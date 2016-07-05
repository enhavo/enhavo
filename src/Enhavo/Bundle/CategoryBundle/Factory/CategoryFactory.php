<?php

/**
 * CategoryFactory.php
 *
 * @since 09/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\CategoryBundle\Factory;

use Enhavo\Bundle\CategoryBundle\Model\CategoryInterface;
use Enhavo\Bundle\CategoryBundle\Model\CollectionInterface;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CategoryFactory extends Factory implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function createWithCollection($arguments)
    {
        if(is_string($arguments)) {
            $collectionName = $arguments;
        } elseif(!isset($arguments['collection'])) {
            throw new \InvalidArgumentException(
                'CategoryFactory called with createWithCollection but no collection name was passed. Use  an array like [\'collection\' => \'collectionName\']'
            );
        } else {
            $collectionName = $arguments['collection'];
        }

        $collectionRepository = $this->container->get('enhavo_category.repository.collection');
        $collection = $collectionRepository->findOneBy([
            'name' => $collectionName
        ]);

        if(empty($collection)) {
            /** @var CollectionInterface $collection */
            $collection = $this->container->get('enhavo_category.factory.collection')->createNew();
            $collection->setName($collectionName);
            $em = $this->container->get('doctrine.orm.default_entity_manager');
            $em->persist($collection);
        }

        /** @var CategoryInterface $category */
        $category = $this->createNew();
        $category->setCollection($collection);
        return $category;
    }
}