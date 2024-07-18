<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionFactory
{
    private ContainerInterface $container;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
    public function create(EntityRepository $repository, array $filters, array $columns, array $configuration): CollectionInterface
    {
        if (isset($configuration['class'])) {
            $class = $configuration['class'];
            unset($configuration['class']);
        } else {
            $class = TableCollection::class;
        }

        if ($this->container->has($class)) {
            $collection = clone $this->container->get($class);
        } else {
            $collection = new $class();
        }

        if (!$collection instanceof CollectionInterface) {
            throw new \Exception();
        }

        $collection->setFilters($filters);
        $collection->setColumns($columns);
        $collection->setRepository($repository);


        $resolver = new OptionsResolver();
        $collection->configureOptions($resolver);
        $options = $resolver->resolve($configuration);
        $collection->setOptions($options);

        return $collection;
    }
}
