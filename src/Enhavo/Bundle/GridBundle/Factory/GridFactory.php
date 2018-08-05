<?php
namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class GridFactory extends Factory
{
    use ContainerAwareTrait;

    /**
     * @var ItemFactory
     */
    private $itemFactories = [];

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
            $item = $this->getItemFactory($item->getName())->duplicate($item->getItemType());
            $newGrid->addItem($item);
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
        return $this->container->get('enhavo_grid.factory.item_type');
    }
}
