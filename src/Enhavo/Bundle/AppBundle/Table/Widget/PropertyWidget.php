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
        $path = $this->getOption('path', $options);
        $resource =  $this->getProperty($item, $options['property']);

        if($path != null) {
            $resource = $this->getProperty($resource , $path);
        }

        return $resource;
    }

    public function getType()
    {
        return 'property';
    }
}