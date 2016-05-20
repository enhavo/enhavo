<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 12:45
 */

namespace Enhavo\Bundle\AppBundle\Table;

use Doctrine\Common\Collections\ArrayCollection;
use Enhavo\Bundle\AppBundle\Exception\TableWidgetException;

class TableWidgetCollector {

    /**
     * @var TableWidgetInterface[]
     */
    private $collection;

    public function __construct()
    {
        $this->collection = array();
    }

    public function add(TableWidgetInterface $widget)
    {
        $this->collection[$widget->getType()] = $widget;
    }

    /**
     * @return TableWidgetInterface[]
     */
    public function getCollection()
    {
        $collection = [];
        foreach($this->collection as $widget) {
            $collection[] = $widget;
        }
        return $collection;
    }

    public function getWidget($typeName)
    {
        if(isset($this->collection[$typeName])) {
            return $this->collection[$typeName];
        }

        throw new TableWidgetException(sprintf(
            'TableWidget type "%s" not found. Did you mean one of them "%s".',
            $typeName,
            implode(', ', $this->getWidgetNames())
        ));
    }

    protected function getWidgetNames()
    {
        return array_keys($this->collection);
    }
}
