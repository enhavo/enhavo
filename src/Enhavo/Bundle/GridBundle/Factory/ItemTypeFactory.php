<?php

/**
 * ItemTypeFactory.php
 *
 * @since 02/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Factory;

class ItemTypeFactory
{
    /**
     * @var array
     */
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * @param $type
     */
    public function create($type)
    {
        if(!isset($this->items[$type])) {
            throw new \InvalidArgumentException(sprintf('Item type "%s" does not exists'));
        }

        $className = $this->items[$type]['model'];
        $itemType = new $className;
        return $itemType;
    }
}