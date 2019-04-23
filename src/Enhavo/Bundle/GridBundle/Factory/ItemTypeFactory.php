<?php

/**
 * ItemTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\AppBundle\Exception\ResolverException;
use Enhavo\Bundle\GridBundle\Item\Item;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;
use Enhavo\Bundle\FormBundle\DynamicForm\ResolverInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ItemTypeFactory
{
    use ContainerAwareTrait;

    /**
     * @return ItemTypeInterface
     */
    public function createNew($name)
    {
        /** @var Item $item */
        $item = $this->getResolver()->resolveItem($name);
        $factory = $this->getFactory($item);
        return $factory->createNew();
    }

    /**
     * @param ItemTypeInterface $itemType
     * @return ItemTypeInterface
     */
    public function duplicate(ItemTypeInterface $itemType, $name)
    {
        /** @var Item $item */
        $item = $this->getResolver()->resolveItem($name);
        $factory = $this->getFactory($item);
        return $factory->duplicate($itemType);
    }

    /**
     * @return ResolverInterface
     */
    private function getResolver()
    {
        return $this->container->get('enhavo_grid.resolver.item_resolver');
    }

    /**
     * @param Item $item
     * @return FactoryInterface
     * @throws ResolverException
     */
    private function getFactory(Item $item)
    {
        $factoryClass = $item->getFactory();
        if($factoryClass) {
            if ($this->container->has($factoryClass)) {
                $factory = $this->container->get($factoryClass);
            } else {
                /** @var AbstractItemFactory $factory */
                $factory = new $factoryClass($item->getModel());
                $factory->setContainer($this->container);
            }

            return $factory;
        }
        throw new ResolverException(sprintf('Factory for grid item type "%s" is required', $item->getName()));
    }
}