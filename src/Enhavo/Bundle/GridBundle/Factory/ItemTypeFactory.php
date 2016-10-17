<?php

/**
 * ItemTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

use Enhavo\Bundle\GridBundle\Item\ItemConfigurationCollection;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;

class ItemTypeFactory
{
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
                $className = $item->getModel();
                $itemType = new $className;
                return $itemType;
            }
        }

        throw new \InvalidArgumentException(sprintf('Item type "%s" does not exists'));
    }
}