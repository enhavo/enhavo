<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 12:45
 */

namespace Enhavo\Bundle\AppBundle\Table;

use Doctrine\Common\Collections\ArrayCollection;

class TableWidgetCollector {

    /**
     * @var ArrayCollection
     */
    private $collection;

    public function __construct()
    {
        $this->collection = array();
    }

    public function add($widget)
    {
        $this->collection[] = $widget;
    }

    /**
     * @return TableWidgetInterface[]
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
