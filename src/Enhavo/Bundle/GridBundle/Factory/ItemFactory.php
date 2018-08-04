<?php

/**
 * ItemTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\AppBundle\DynamicForm\FactoryInterface;
use Enhavo\Bundle\GridBundle\Model\ItemTypeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Enhavo\Bundle\GridBundle\Entity\Item;

class ItemFactory implements FactoryInterface
{
    use ContainerAwareTrait;

    /**
     * @var FactoryInterface
     */
    private $itemTypeFactory;

    public function __construct(FactoryInterface $itemTypeFactory)
    {
        $this->itemTypeFactory = $itemTypeFactory;
    }

    public function createNew()
    {
        $item = new Item();
        $item->setItemType($this->itemTypeFactory->createNew());
        return $item;
    }

    /**
     * @param ItemTypeInterface $itemType
     * @return ItemTypeInterface
     */
    public function duplicate(ItemTypeInterface $itemType)
    {
        $item = new Item();
        $item->setItemType($this->itemTypeFactory->dupicate($itemType));
        return $item;
    }
}