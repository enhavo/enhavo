<?php
/**
 * ItemConfigurationCollection.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item;


use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class ItemConfigurationCollection
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * @var array
     */
    private $items;

    /**
     * @var ItemConfiguration[]|null
     */
    private $itemConfigurations;

    /**
     * ItemConfigurationCollection constructor.
     *
     * @param TypeCollector $collector
     * @param $items
     */
    public function __construct(TypeCollector $collector, $items)
    {
        $this->collector = $collector;
        $this->items = $items;
    }

    /**
     * @return ItemConfiguration[]
     */
    public function getItemConfigurations()
    {
        if($this->itemConfigurations !== null) {
            return $this->itemConfigurations;
        }
        $itemConfigurations = [];
        foreach($this->items as $name => $item) {
            $type = 'base';
            if(array_key_exists('type', $item)) {
                $type = $item['type'];
            }
            /** @var ConfigurationInterface $configurationType */
            $configurationType = $this->collector->getType($type);
            $itemConfigurations[] = $configurationType->configure($name, $item);
        }
        $this->itemConfigurations = $itemConfigurations;
        return $itemConfigurations;
    }
}