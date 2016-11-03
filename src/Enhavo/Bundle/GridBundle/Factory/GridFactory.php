<?php
namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Sylius\Component\Resource\Factory\Factory;

class GridFactory extends Factory
{
    /**
     * @var ItemTypeFactory
     */
    protected $itemTypeFactory;

    public function __construct($className, ItemTypeFactory $itemTypeFactory)
    {
        parent::__construct($className);
        $this->itemTypeFactory = $itemTypeFactory;
    }

    /**
     * @param Grid|null $originalResource
     * @return Grid
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Grid $newGrid */
        $newGrid = $this->createNew();

        /** @var Item $item */
        foreach($originalResource->getItems() as $item) {
            $newItemType = $this->itemTypeFactory->duplicate($item->getItemType());

            $newItem = new Item();
            $newItem->setOrder($item->getOrder());
            $newItem->setConfiguration($item->getConfiguration());
            $newItem->setType($item->getType());
            $newItem->setItemType($newItemType);
            $newGrid->addItem($newItem);
        }

        return $newGrid;
    }
}
