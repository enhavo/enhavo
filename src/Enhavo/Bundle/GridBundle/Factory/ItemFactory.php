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
     * @var ItemTypeFactory
     */
    private $itemTypeFactory;

    /**
     * @var string
     */
    private $name;

    public function __construct(ItemTypeFactory $itemTypeFactory, $name)
    {
        $this->itemTypeFactory = $itemTypeFactory;
        $this->name = $name;
    }

    public function createNew()
    {
        $item = new Item();
        $item->setItemType($this->itemTypeFactory->createNew($this->name));
        $item->setName($this->name);
        return $item;
    }

    public function duplicate(ItemTypeInterface $itemType)
    {
        $item = new Item();
        $item->setName($item->getName());
        $item->setPosition($item->getPosition());
        $item->setItemType($this->itemTypeFactory->duplicate($itemType, $item->getName()));
        return $item;
    }
}