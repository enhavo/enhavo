<?php
namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GridFactory extends Factory
{
    use ContainerAwareTrait;

    /**
     * @var ItemTypeFactory
     */
    private $itemTypeFactory;

    /**
     * @var ItemFactory
     */
    private $itemFactories = [];

    public function __construct($className, ItemTypeFactory $itemTypeFactory)
    {
        $this->itemTypeFactory = $itemTypeFactory;
        parent::__construct($className);
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
            $newItem = $this->getItemFactory($item->getName())->duplicate($item);
            $newGrid->addItem($newItem);
        }

        return $newGrid;
    }

    private function getItemFactory($name)
    {
        if(isset($this->itemFactories[$name])) {
            return $this->itemFactories[$name];
        }

        $this->itemFactories[$name] = new ItemFactory($this->getItemTypeFactory(), $name);
        return $this->itemFactories[$name];
    }

    private function getItemTypeFactory()
    {
        return $this->itemTypeFactory;
    }
}
