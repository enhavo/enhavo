<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class ListWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $list = [];

        $itemProperty = $this->getProperty($item, $options['property']);
        foreach($itemProperty as $child) {
            $list[] = $this->getProperty($child, $options['item_property']);
        }

        $separator = $this->getSeparator($options);
        return implode($separator, $list);
    }

    protected function getSeparator($options)
    {
        $separator = ',';
        if(array_key_exists('separator', $options)) {
            $separator = $options['separator'];
        }
        return $separator;
    }

    public function getType()
    {
        return 'list';
    }
}