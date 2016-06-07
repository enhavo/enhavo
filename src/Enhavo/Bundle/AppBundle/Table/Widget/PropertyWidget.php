<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class PropertyWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        return $this->getProperty($item, $options['property']);
    }

    public function getType()
    {
        return 'property';
    }
}