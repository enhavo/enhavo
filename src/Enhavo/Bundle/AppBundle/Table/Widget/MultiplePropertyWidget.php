<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:11
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

class MultiplePropertyWidget extends ListWidget
{
    public function render($options, $item)
    {
        $list = [];

        $properties = $this->getProperties($options['properties']);
        foreach($properties as $property) {
            $list[] = $this->getProperty($item, $property);
        }

        $separator = $this->getSeparator($options);
        return implode($separator, $list);
    }

    protected function getProperties($options)
    {
        return $options['properties'];
    }

    public function getType()
    {
        return 'multiple_property';
    }

}