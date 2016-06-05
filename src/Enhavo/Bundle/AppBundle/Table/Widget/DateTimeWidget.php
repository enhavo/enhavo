<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class DateTimeWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $property = $this->getProperty($item, $options['property']);
        if(!$property instanceof \DateTime) {
            return '';
        }
        $format = isset($options['format']) ? $options['format'] : 'd.m.Y H:i';
        return $property->format($format);
    }

    public function getType()
    {
        return 'datetime';
    }
}