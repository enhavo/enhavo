<?php

/**
 * ItemTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Item\ItemConfigurationCollection;
use Enhavo\Bundle\GridBundle\Item\ItemFactoryInterface;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ItemTypeFactory
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    protected $itemConfigurations;

    public function __construct(ItemConfigurationCollection $itemConfigurations)
    {
        $this->itemConfigurations = $itemConfigurations;
    }

    /**
     * @param string $type
     * @return ItemTypeInterface
     */
    public function create($type)
    {
        foreach($this->itemConfigurations->getItemConfigurations() as $item) {
            if($item->getName() == $type) {
                $factoryClassName = $item->getFactory();
                /** @var ItemFactoryInterface $factory */
                $factory = new $factoryClassName($item->getModel());
                if($factory instanceof ContainerAwareInterface) {
                    $factory->setContainer($this->container);
                }
                return $factory->create();
            }
        }

        throw new \InvalidArgumentException(sprintf('Item type "%s" does not exists', $type));
    }

    /**
     * @param ItemTypeInterface $itemType
     * @return ItemTypeInterface
     */
    public function duplicate(ItemTypeInterface $itemType)
    {
        $className = get_class($itemType);
        foreach($this->itemConfigurations->getItemConfigurations() as $item) {
            if($item->getModel() == $className) {
                $factoryClassName = $item->getFactory();
                /** @var ItemFactoryInterface $factory */
                $factory = new $factoryClassName($item->getModel());
                if($factory instanceof ContainerAwareInterface) {
                    $factory->setContainer($this->container);
                }
                return $factory->duplicate($itemType);
            }
        }

        throw new \InvalidArgumentException(sprintf('Item type for class "%s" not found', $className));
    }
}